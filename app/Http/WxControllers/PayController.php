<?php

namespace App\Http\WxControllers;

use App\Util\Tools;
use DB;
use App\Models\Orders;
use App\Events\OrderPayment;
use App\Models\Refund;
use Carbon\Carbon;
use Illuminate\Http\Request;
use EasyWeChat\Payment\Order;
use Illuminate\Support\Facades\Log;
use App\Repository\OrdersRepository;
use Illuminate\Support\Facades\Cache;
use EasyWeChat\Foundation\Application;
use Illuminate\Support\Facades\Request as R;

/**
 * 支付
 * Class PayController
 * @package App\Http\WxControllers
 */
class PayController extends Controller
{
    public $orderModel;

    public $payment;

    /**
     * @var Application
     */
    public $app;

    /**
     * PayController constructor.
     */
    public function __construct(OrdersRepository $ordersRepository)
    {
        /**
         * 1.配置
         */
        $this->wxApp = new Application(config('wechat'));
        $this->tcmApp = new Application(config('wechat_app'));
        if(Tools::isTCMUserApp()){
            $this->app = $this->tcmApp;
        }else{
            $this->app = $this->wxApp;
        }
        $this->orderModel = $ordersRepository;
        $this->payment = $this->app->payment;
    }

    /**
     * 微信支付
     * @auth kingofzihua
     * @param $order_id
     * @return array|string
     */
    public function wechat($order_id)
    {
        //获取订单详情
        $order = $this->orderModel->get_data_by_id($order_id);

        if (!$order) {
            return $this->error(404, '订单不存在!');
        }

        if ($order->status >= 5 && $order->pay_status) { //已经支付
            return $this->error(403, '订单已支付!');
        }

        // 处理免费订单
        if($order->payable_amount <= 0){
            //更新订单状态
            $order->loadEditData([
                'pay_type' => '5', 'status' => 5, 'pay_status' => 1,
                'pay_amount' => $order->payable_amount, //实际支付金额
                'pay_time' => Carbon::now(), //支付时间
            ])->save(); //微信支付完毕

            event(new OrderPayment($order));

            return $this->success(['free_order'=>1]);
        }

        //判断下是否是微信支付 并且已经发起过支付了
        if (($order->pay_type == '1' || $order->pay_type == '3') && $order->status == '2') { //已经吊起微信支付了 但是没有进行支付
            if($order->pay_type == '1'){
                //关闭订单
                $close_res = $this->wxApp->payment->close($order->order_sn);
            }else{
                $close_res = $this->tcmApp->payment->close($order->order_sn);
            }

            if ($close_res->return_code == 'SUCCESS' && $close_res->result_code == 'SUCCESS') { //成功关闭
                //重新生成新的订单编号
                $new_order_sn = $this->orderModel->get_unique_order_sn($order);

                //判断下 如果失败 说明有问题直接报错
                if ($new_order_sn) {
                    $order->order_sn = $new_order_sn;
                } else {
                    return $this->error(500, '请稍后重试!');
                }
            }
        }

        //创建微信订单
        $attributes = [
            //'spbill_create_ip' => request()->getClientIp(),
            'body' => $order->body ?? '订单支付',
            'detail' => $order->detail ?? '够买商品',
            'out_trade_no' => $order->order_sn,// 订单编号放在这个里边
//                'total_fee' => $order->total_fee, // 单位：分
            'total_fee' => $order->amount, // 单位：分w
        ];
        // JSAPI，NATIVE，APP...
        if(Tools::isTCMUserApp()){
            $attributes['trade_type'] ='APP';
        }else{
            // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            $attributes['trade_type'] ='JSAPI';
            $attributes['openid'] =R::cookie('openid');
        }

        //统一下单
        $payOrder = new Order($attributes);
        $result = $this->payment->prepare($payOrder);

        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') { //判断下是否成功
            $prepayId = $result->prepay_id;
        } else {
            Log::error('订单' . $order->order_sn . '微信支付请求失败', $result->toArray());
        }

        if (!isset($prepayId)) { //如果失败了，不发起支付
            return $this->error(500, '微信支付请求失败!');
        }


        if(Tools::isTCMUserApp()){
            //修改订单状态
            $order->loadEditData(['pay_type' => '3', 'status' => 2])->save(); //微信支付中
            return $this->payment->configForAppPayment($prepayId);
        }else{
            //修改订单状态
            $order->loadEditData(['pay_type' => '1', 'status' => 2])->save(); //微信支付中
            return $this->payment->configForJSSDKPayment($prepayId);
        }
    }

    /**
     * 备份之前冰冰写的代码 防止后期有问题 但是不能直接用了 只能用来看流程的
     * @param $order_info
     * @param bool $first
     * @return array|bool|string
     */
    public function wechatBak($order_info, $first = true)
    {
        //获取微信支付实例
        $payment = $this->app->payment;

        //从缓存中读取订单编号
        $cacheKey = "prepay_id:{$order_info['order_sn']}";
        $prepayId = Cache::get($cacheKey);

        //判断 如果已经有 prepayId 了直接发起支付就好啦
        if (!$prepayId) { //没有 要生成

            //不是第一次支付 要关闭订单
            if (!$first) {
                //关闭订单
                $ret = $payment->close($order_info['order_sn']);
                if ($ret->return_code == 'SUCCESS' && $ret->result_code == 'SUCCESS') {
                    $order = Orders::findUnpayedBySN($order_info['order_sn']);
                    if ($order && $sn = $order->generateOrderSn()) {
                        $order_info['order_sn'] = $sn;
                    }
                }
            }

            /**
             * 2.创建订单
             * 正常模式
             */
            $openid = R::cookie('openid');
            $attributes = [
                'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
                'body' => $order_info['body'],
                'detail' => $order_info['detail'],
                'out_trade_no' => $order_info['order_sn'],// 订单编号放在这个里边
//                'total_fee' => bcmul($order_info['total_fee'], 100, 0), // 单位：分
                'total_fee' => 1, // 单位：分w
                'openid' => $openid, // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            ];

            $order = new Order($attributes);

            /**
             * 3.统一下单
             */
            $result = $payment->prepare($order);
            if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
                $prepayId = $result->prepay_id;
                Cache::put($cacheKey, $prepayId, 115);//115分钟
            } else {
                //失败
                Log::info('pay---error--', $result->toArray());
            }
        }
        if (!$prepayId) {
            return false;
        }
        return $payment->configForJSSDKPayment($prepayId);
    }


    /**
     * 微信支付回调地址
     * @param Request $request
     * @param $type
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function notify(Request $request, $type)
    {
        \Log::info('微信支付回调数据', array_merge($request->all(), ['type' => $type]));

        if ($type == 'wx.web' || $type=='wx.app') { //路由里面配置了
            if($type == 'wx.web'){
                $pay_type = 1;
                $this->app = $this->wxApp;
            }else{
                $pay_type = 3;
                $this->app = $this->tcmApp;
            }
            $response = $this->app->payment->handleNotify(function ($notify, $successful) use ($pay_type) {

                //如果是成功的
                if ($successful) {
                    //json => array
                    $order_arr = json_decode($notify, true);
                    $out_trade_no = $order_arr['out_trade_no'];//订单号
                    //回调成功的逻辑

                    $order = $this->orderModel->get_data_by_order_sn($out_trade_no);

                    //订单没找到 告诉微信，我已经处理完了，别再通知我了
                    if (empty($order)) return 'Order not exist.';

                    //判断是否 已经处理过了
                    if (!empty($order->wx_order_no) && $order->status == 5 && $order->pay_time) return true;

                    //更新订单状态
                    $update = $order->loadEditData([
                        'pay_type' => $pay_type, 'status' => 5, 'pay_status' => 1,
                        'pay_amount' => $order_arr['total_fee'], //实际支付金额
                        'wx_order_no' => $order_arr['transaction_id'], //微信订单
                        'pay_time' => Carbon::now(), //支付时间
                    ])->save(); //微信支付完毕

                    //触发 订单支付的 事件  用来处理其他的 部分 比如预约 药方 诊疗 等等 记录订单完成的日志也在里面
                    event(new OrderPayment($order));

                    return $update;
                }
            });
            return $response;
        }
    }

    /**
     * 测试支付
     * @param OrdersRepository $ordersRepository
     * @param $order_id
     * @return mixed
     */
    public function testPay(OrdersRepository $ordersRepository, $order_id)
    {
        $order = $ordersRepository->get_data_by_id($order_id);

        if (!$order) {
            return $this->error(404, '订单不存在!');
        }

        if ($order->status >= 5 && $order->pay_status) { //已经支付
            return $this->error(403, '此订单已支付!');
        }

        //订单支付
        $order->loadEditData(['pay_amount' => $order->payable_amount, 'pay_status' => '1', 'pay_type' => '5', 'status' => '5'])->save(); //直接修改订单为已支付状态 0元 不需要支付

        //触发事件
        event(new OrderPayment($order));

        return $this->success(['order_id' => $order->id], '订单支付成功了！');
    }

    /**
     * 退款
     * @param $order_sn
     * @param int $refund_amount
     */
    public function refund($order, $refund_amount)
    {
        $payment = $this->app->payment;

        $refund_msg = $payment->refund($order->order_sn, $order->order_sn . '1', $order->pay_amount, $refund_amount);

        $return_info = $payment->queryRefundByTransactionId($order->wx_order_no);

        //添加到退款记录表中
        DB::beginTransaction();

        $this->refund_log($order, $return_info);

        if ($return_info->return_code == 'SUCCESS' && $return_info->result_code == 'SUCCESS') {
            $order->status = 10;//订单的状态 0 未支付 2正在支付 5已支付 9退款中 10已退款
            $order->refund_amount = $return_info->refund_fee;//退款金额
            $order->refund_time = date('Y-m-d H:i:s');//退款金额

            if ($order->save()) {
                DB::commit();
                return ['status' => 1, 'msg' => '退款成功!'];
            };

            DB::rollBack();
            return ['status' => 1, 'msg' => '退款失败'];
        }
        if ($return_info->return_code == 'FAIL' || $return_info->result_code == 'FAIL') {
            Log::info('wechat refund fail', ['order_sn' => $order->order_sn, 'msg' => $return_info->err_code]);
            file_put_contents(public_path() . '/refundfail.txt', $refund_msg);

            DB::commit();
            return ['status' => 0, 'msg' => '退款失败!' . $return_info->err_code_des];
        }

    }

    //添加到退款记录表
    public function refund_log($order, $return_info)
    {
        //添加到退款记录表
        $refundData = [
            'order_id' => $order->id,
            'admin_id' => \Auth::id(),
            'fee' => $return_info->refund_fee ?? '',
            'schedule' => $return_info->return_code == 'SUCCESS' && $return_info->result_code == 'SUCCESS'? 1 : 2,
            'type' => \Auth::id(),
            'source' => 1,
            'error_note' => $return_info->return_code == 'SUCCESS' ? $return_info->err_code_des : $return_info->return_msg,
        ];

        (new Refund())->create_refund_data($refundData);
    }

}
