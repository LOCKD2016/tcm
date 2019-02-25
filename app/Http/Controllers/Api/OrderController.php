<?php

namespace App\Http\Controllers\Api;

use App\Models\Orders;
use App\Util\Exp;
use Illuminate\Http\Request;
use App\Repository\OrdersRepository;
use App\Http\WxControllers\PayController;
use App\Transformers\Api\OrderTransformer;
use App\Repository\TemplateRepository;
use App\Transformers\Api\PrescriptionOrderTransformer;

class OrderController extends ApiController
{
    /**
     * @var Orders
     */
    protected $orders;

    protected $page = 10;

    /**
     * OrderController constructor.
     * @param Orders $orders
     */
    public function __construct(Orders $orders)
    {
        $this->orders = $orders;
    }

    /**
     * 预约订单列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function beaspeakOrderList(Request $request)
    {
        $data = $request->all();

        $sql = $this->orders
            ->orderType($data['search']['order_type'])
            ->with('bespeak', 'user')
            ->orderSn($data['search']['order_sn'] ?? '')
            ->orderUser($data['search']['user_name'] ?? '')
            ->orderStatus($data['search']['clinic_type'] ?? '')
            ->orderPayType($data['search']['pay_type'] ?? '')
            ->orderPayTime($data['search']['pay_time'] ?? '')
            ->orderBy('id', 'desc');

        //是否是导出
        if (isset($data['search']['export']) && $data['search']['export']) {
            return $lists = $sql->get();
        }

        $lists = $sql->paginate($data['search']['cur_total']);

        return $this->response()->paginator($lists, new OrderTransformer());
    }

    /**
     * 药方订单列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function prescriptionOrderList(Request $request)
    {
        $data = $request->all();

        $sql = $this->orders
            ->with('prescription.clinic')
            ->orderType($data['search']['order_type'])
            ->orderSn($data['search']['order_sn'] ?? '')
            ->orderUser($data['search']['user_name'] ?? '')
            ->orderStatus($data['search']['clinic_type'] ?? '')
            ->orderPayType($data['search']['pay_type'] ?? '')
			->orderPayTime($data['search']['pay_time'] ?? '')
			->orderBy('id', 'desc');

        //是否是导出
        if (isset($data['search']['export']) && $data['search']['export']) {
            return $lists = $sql->get();
        }

        $lists = $sql->paginate($data['search']['cur_total']);

        return $this->response()->paginator($lists, new PrescriptionOrderTransformer());
    }

    /**
     * 药方发货列表
     * @param Request $request
     */
    public function preSendList(Request $request)
    {
        $data = $request->all();

        $sql = $this->orders
            ->orderType($data['search']['type'])
            ->orderSn($data['search']['order_sn'] ?? '')
            ->orderUser($data['search']['name'] ?? '')
            ->orderPayStatus($data['search']['pay_status'] ?? '')
            ->orderConsignee($data['search']['user_name'] ?? '')
            ->prescriptionExpress($data['search']['express'] ?? '')
            ->orderBy('id', 'desc');

        //是否是导出
        if (isset($data['search']['export']) && $data['search']['export']) {
            return $lists = $sql->get();
        }

        $lists = $sql->paginate($data['search']['cur_total']);

        return $this->response()->paginator($lists, new OrderTransformer());
    }

    /**
     * 药方发货操作
     * @param $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $order = $this->orders->find($id);

        if (!$order) {
            $this->error(100, '订单不存在');
        }
        // 判断快递号是否存在
        $alreay_has_express_number = Orders::where('express_number', $request['express_number'])->count();
        if($alreay_has_express_number)
            return $this->error(101,'快递单号已存在');

        $update = $this->orders->updateData($id, $request->all());

        if ($update) {
            $order_detail = Orders::find($id);
            (new TemplateRepository())->send_goods_to_user_remind_message($order_detail);// 订单发货通知模板消息
            return $this->success(200, '操作成功');
        }

        return $this->error(100, '操作失败');
    }

    /**
     * 申请退款
     * @param $order_id
     * @param $refund_amount
     */
    public function refund(Request $request)
    {
        if(!$request->refund_amount)
        {
            return $this->error(100, '请输入正确的退款金额');
        }

        $order = Orders::find($request->order_id);

        if (!$order) {
            return $this->error('404', '订单不存在');
        }

        if ($order->status > 5) {
            return $this->error('101', '订单已退款或正在退款中');
        }

        $ret = (new PayController(new OrdersRepository()))->refund($order, $request->refund_amount);
        return $ret;
        if($ret['status']) {
            return $this->success();
        }

        return $this->error(100, $ret['msg']);
    }

    /**
     * 订单类型 1门诊 2网诊 3药方 4商品
     * 数据管理--经营统计
     * @return mixed
     */
    public function deal()
    {
        $data = Orders::whereIn('order_type', [1, 2, 3])->where('status', '>', 0)->get();
        $count = [];
        $count['clinic_amount'] = ($data->where('order_type', 1)->sum('pay_amount') - $data->where('order_type', 1)->sum('refund_amount')) / 100;//门诊
        $count['net_amount'] = ($data->where('order_type', 2)->sum('pay_amount') - $data->where('order_type', 2)->sum('refund_amount')) / 100;//网费
        $count['recipe_amount'] = ($data->where('order_type', 3)->sum('pay_amount') - $data->where('order_type', 3)->sum('refund_amount')) / 100;//药方
        $count['total'] = $count['clinic_amount'] + $count['net_amount'] + $count['recipe_amount'];

        return $this->success($count);
    }

    /**
     *  获取快递进度
     * @desc
     * @author Eric
     * @DateTime 2018/3/22 16:53
     * @param $order_id
     */
    public function getexpress($order_id)
    {
        $order = Orders::find($order_id);
        if(isset($order->express_company) && isset($order->express_number))
            return ((new Exp($order->express_company, $order->express_number))->query());
    }

    /**
     *  获取快递公司信息
     * @desc
     * @author Eric
     * @DateTime 2018/3/22 17:00
     * @return array
     */
    public function getexpresscompany()
    {
        return (new Exp('',''))->getComs();
    }

}
