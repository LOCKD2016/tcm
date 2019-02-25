<?php

namespace App\Listeners;

use App\Events\OrderPayment;
use App\Repository\OrdersRepository;
use App\Repository\SMSCodeRepository;
use App\Repository\TemplateRepository;
use App\Repository\PrescriptionRepository;
use App\Repository\JPushRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 订单支付成功
 * Class OrderPay
 * @package App\Listeners
 */
class OrderPay
{
    public $ordersRepository;

    /**
     * Create the event listener.
     * @param OrdersRepository $ordersRepository
     * @return void
     */
    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    /**
     * Handle the event.
     *
     * @param  OrderPayment $event
     * @return void
     */
    public function handle(OrderPayment $event)
    {
        $order = $event->order;
        //写日志
        $this->ordersRepository->add_order_log($event->order->id, '2', '订单支付完毕');

        if ($order->order_type == 3 || $order->order_type == 4) {
            if ($order->order_type == 3) {
                (new PrescriptionRepository())->set_pay_status_by_order_id($order->id);//设置药方已支付状态
                (new PrescriptionRepository())->add_prescription_unit($order->id);//给药方添加单位 药方表和消息表都要更新
            }
            (new SMSCodeRepository())->send_message_customer_for_recipe_is_pay($order); //发送短信通知客服
            (new TemplateRepository)->send_pay_common_message($order);// 发送支付成功模板消息
        }
    }
}
