<?php

namespace App\Http\WxControllers;

use App\Models\Orders;
use App\Util\Exp;
use App\Util\Tools;
use App\Events\OrderPayment;
use App\Repository\GoodRepository;
use App\Repository\DoctorRepository;
use App\Repository\OrdersRepository;
use App\Repository\BespeakRepository;
use App\Repository\AddressRepository;
use App\Transformers\OrderTransformer;
use App\Repository\PrescriptionRepository;
use App\Http\Requests\PrescriptionPayRequest;

/**
 * 订单管理
 * Class OrdersController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class OrdersController extends Controller
{

    /**
     * @Auth: kingofzihua
     * @var OrdersRepository
     */
    protected $model;

    /**
     * @Auth: kingofzihua
     * OrdersController constructor.
     * @param OrdersRepository $order
     */
    public function __construct(OrdersRepository $order)
    {
        $this->model = $order;
    }

    /**
     * 订单列表
     * @Auth: kingofzihua
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->model->get_data_list_by_user(\Auth::id());

        return $this->response()->paginator($lists, new OrderTransformer());
    }

    /**
     * @Auth: kingofzihua
     * @param $order_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($order_id)
    {
        $detail = $this->model->get_data_by_id($order_id);

        return $this->response()->item($detail, new OrderTransformer());
    }

    /**
     * 预约生成订单
     * @desc
     * @Auth: kingofzihua
     * @param GoodRepository $goodRepository
     * @param BespeakRepository $bespeakRepository
     * @param DoctorRepository $doctorRepository
     * @param $bespeak_id
     * @param $bespeak_id
     * @return mixed
     */
    public function bespeak(GoodRepository $goodRepository, BespeakRepository $bespeakRepository, DoctorRepository $doctorRepository, $bespeak_id)
    {
        //查询是否生成过订单
        $bespeak = $bespeakRepository->get_data_by_id($bespeak_id);

        if (empty($bespeak)) {
            return $this->error(404, '订单创建失败，该预约不存在');
        } else {
            $order = $this->model->get_data_by_id($bespeak->order_id);
        }

        if ($bespeak['status'] == 5){
            return $this->error(403, '医生未接诊');
        }

        //没有生成过订单
        if (!isset($order)) {

            //区分下 是门诊还是网诊
            $type = $bespeak->type == '1' ? 'clinicBespeak' : 'webBespeak';

            //生成订单
            $good = $goodRepository->get_good_data_by_type($type);

            if (!$good) { //商品不存在 被删除 或者是被禁用
                return $this->error(404, '该商品已下架');
            }

            $doctor = $doctorRepository->get_data_by_id($bespeak->doctor_id);

            //区分下 是门诊还是网诊
            if ($bespeak->type == '1') {
//                //门诊 定金 读取数据库配置
//                $amount = intval($good->amount) >= 0 ? intval($good->amount) : 10000;//防止后台配置错误 默认一百元,
                //门诊改为同步过来的医生的门诊费用
                $amount = $doctor->clinic_amount ?: 20000;
            } else if($bespeak->type == '0'){
                //网诊 获取价格
                $amount = $doctor->web_amount ?: 20000;
            }else{
                $amount = $doctor->video_amount ?: 50000;
            }

            \DB::beginTransaction();//开启事务，好多sql 保证全部都执行

            //生成订单
            $order = $this->model->create([
                'user_id' => \Auth::id(),
                'order_sn' => Tools::getOrderSn(),
                'body' => '预约支付订单',
                'order_type' => $bespeak->type == '1' ? '1' : '2', //门诊预约订单
                'amount' => $amount,
                'payable_amount' => $amount, //应付金额
            ]);

            //添加商品
            $goods = $order->addGoods($good->id, [
                'name' => $good->name,
                'number' => '1',
                'amount' => $amount,
            ]);

            //当订单创建失败并且 添加完商品后
            if (!$order) {
                \DB::rollBack(); //事务回滚
                return $this->error(500, '订单创建失败，请稍候重试');
            }

            //记录订单操作日志
            $this->model->add_order_log($order->id, '1', '用户生成订单');

            //预约关联订单
            $bespeak->loadEditData(['order_id' => $order->id])->save();

            \DB::commit(); //提交事务

        } elseif ($order->status >= 5 && $order->pay_status) { //订单已经存在 判断下是否已经支付
            return $this->error(403, '此预约的订单已支付!');
        }

        $toPay = 1;//用来判断是否是需要进行支付的 默认是需要进行支付的

        //实际应付金额 为0的 不应该去支付了
        if ($order->payable_amount === 0) {//订单金额为0 不去支付了，直接支付

            $toPay = 0;

            $order->loadEditData(['pay_amount' => $order->payable_amount, 'pay_status' => '1', 'pay_type' => '5', 'status' => '5'])->save(); //直接修改订单为已支付状态 0元 不需要支付

            event(new OrderPayment($order));
        }

        return $this->success(['order_id' => $order->id, 'order_sn' => $order->order_sn, 'toPay' => $toPay], '订单已生成');
    }

    /**
     * 药方支付
     * @param PrescriptionPayRequest $request [
     *          'tisane',// 是否代煎
     *          'express',// 是否快递
     *          'address_id',//地址编号
     *          'recipe_self_price',//自备的价格
     * ]
     * @param PrescriptionRepository $prescriptionRepository
     * @param AddressRepository $addressRepository
     * @param GoodRepository $goodRepository
     * @param $prescription_id
     * @return mixed
     */
    public function prescription(PrescriptionPayRequest $request, PrescriptionRepository $prescriptionRepository, GoodRepository $goodRepository, AddressRepository $addressRepository, $prescription_id)
    {
        $prescription = $prescriptionRepository->get_data_by_id($prescription_id);

        //判断下是否是当前用户的
        if (empty($prescription) || $prescription->user_id != \Auth::id()) return $this->error(403, '没有访问权限');

        $recipe_head = $prescription->recipe_head;//处方头部 {sum:"总帧数",dayNum:"每日服用剂数",takingNum:"每剂服用次数",sumWeight:"每剂重量"}

        //获取药方数量
        $num = $recipe_head['sum'] ?? 1; //默认为1付

        \DB::beginTransaction(); //开启事务

        //查询药方有没有生成订单
        if ($prescription->order_id) {
            //已经生成订单了
            $order = $this->model->get_data_by_id($prescription->order_id);

            if ($order->status >= 5 && $order->pay_status) { //订单已经存在 判断下是否已经支付
                return $this->error(403, '此药方的订单已支付!');
            }
        } else {
            //生成订单
            $order = $this->model->create(['user_id' => \Auth::id(), 'order_sn' => Tools::getOrderSn(), 'body' => '药方支付订单', 'order_type' => '3',]);

            $prescription->order_id = $order->id;
        }

        $order_amount = $prescription->medicine_price; //初始的价格是药方的价格

        //判断是否有调剂费 dispensing
        if ($prescription->dispensing_price) {
            $dispensing_good = $goodRepository->get_good_data_by_type('dispensing');

            if (empty($dispensing_good)) return $this->error(500, '没有查询到调剂费');

            $dispensing_amount = $prescription->dispensing_price;//从药方中获取调剂费

            //添加商品
            $dispensing_order_good = $order->addGoods($dispensing_good->id, ['name' => $dispensing_good->name, 'number' => 1, 'amount' => $dispensing_amount,]);

            $order_amount += $dispensing_amount;
        }

        //判断是否有代煎 tisane_price
        if ($request->tisane) {//有代煎
            //获取代煎的价格
            $price_good = $goodRepository->get_good_data_by_type('tisane');

            if (empty($price_good)) return $this->error(500, '没有查询到代煎费');

            $price_amount = $price_good->amount * $num; //按照每付 收费

            //添加商品
            $price_order_good = $order->addGoods($price_good->id, ['name' => $price_good->name, 'number' => $num, 'amount' => $price_amount,]);

            $prescription->tisane = 1;//需要代煎
            $prescription->tisane_price = $price_amount;//代煎费
            $order_amount += $price_amount;
        }else{
            $prescription->tisane = 0;//需要代煎
        }


        //判断是否有自备的药材 recipe_self_price
        if ($request->recipe_self_price) {
            $self_price_good = $goodRepository->get_good_data_by_type('self_price');

            if (empty($self_price_good)) return $this->error(500, '没有查询到自备');

            $self_price_amount = $request->recipe_self_price;//自备的药费 前台算吧

            //添加商品
            $self_price_order_good = $order->addGoods($self_price_good->id, ['name' => $self_price_good->name, 'number' => 1, 'amount' => -$self_price_amount,]);

            $prescription->recipe_self_price = $self_price_amount;//自备药材费
            $order_amount -= $self_price_amount;
        }

        //判断是否需要快递 express
        if ($request->express && $request->address_id) {
            $express_good = $goodRepository->get_good_data_by_type('express');

            if (empty($express_good)) return $this->error(500, '没有查询到快递');

            //获取地址信息
            $address = $addressRepository->get_data_by_id($request->address_id);

            if (empty($address)) return $this->error(500, '没有查询到地址');

            //根据省份获取 价格
            $areas = $addressRepository->get_amount_by_province($address->province);

            if (empty($areas)) { //没有查到 就默认为0
                $express_amount = 0;
            } else {//查询到了 计算 快递费

                //判断重量是否超出
                if ($areas->weight >= $recipe_head['sum'] * $recipe_head['sumWeight']) {
                    //没有超出 只有快递费
                    $express_amount = $areas->amount;
                } else {
                    //超出
                    $express_amount = $areas->amount + ($recipe_head['sum'] * $recipe_head['sumWeight'] - $areas->weight) * $areas->price;
                }
            }

            //添加商品
            $express_order_good = $order->addGoods($express_good->id, ['name' => $express_good->name, 'number' => 1, 'amount' => $express_amount,]);

            $prescription->express = 1;//是否需要快递
            $order_amount += $express_amount;

            //有收货地址 加入到订单
            $order->user_name = $address->user_name;
            $order->country = $address->country;
            $order->province = $address->province;
            $order->city = $address->city;
            $order->district = $address->district;
            $order->address = $address->address;
            $order->mobile = $address->mobile;
            $order->address_id = $request->address_id?:0;
        }else{
            $prescription->express = 0;//是否需要快递
            $order->address_id = 0;
        }

        $prescription_save = $prescription->save();

        if(!$prescription_save){
            \DB::rollBack();
            return $this->error(500, '药方保存失败');
        }

        //'amount' => $amount,
        //'payable_amount' => $amount,//应付金额
        $order->amount = $order->payable_amount = $order_amount;

        if ($order->save()) {
            \DB::commit(); //事务提交

            $toPay = 1;//用来判断是否是需要进行支付的 默认是需要进行支付的

            //实际应付金额 为0的 不应该去支付了
            if ($order->payable_amount === 0) {//订单金额为0 不去支付了，直接支付

                $toPay = 0;

                $order->loadEditData(['pay_amount' => 0, 'pay_status' => '1', 'pay_type' => '5', 'status' => '5'])->save(); //直接修改订单为已支付状态 0元 不需要支付

                event(new OrderPayment($order));
            }

            return $this->success(['order_id' => $order->id, 'order_sn' => $order->order_sn, 'toPay' => $toPay], '订单已生成');
        }

        \DB::rollBack();

        return $this->error(500, '订单创建失败');
    }

    /**
     *  获取快递信息
     * @param $order_id
     */
    public function express($order_id)
    {
        $order = Orders::where('id', $order_id)->first();
        if (!count($order))
            return $this->error(101, '没有找到该订单的信息');
        if (!$order->express_company || !$order->express_number)
            return $this->error(101, '没有找到快递信息');
        $detail = (new Exp($order->express_company, $order->express_number))->query();
        $detail['order'] = collect($order, new \App\Transformers\Api\Export\OrderTransformer());
        return $this->success($detail);
    }

}


