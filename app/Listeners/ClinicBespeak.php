<?php

namespace App\Listeners;

use App\Events\OrderPayment;
use App\Repository\SMSCodeRepository;
use App\Repository\TemplateRepository;
use App\Repository\JPushRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClinicBespeak
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        if ($order->order_type == '1') { //门诊
            $bespeak = $order->bespeak;

            //修改诊疗状态为已支付
            $bespeak->loadEditData(['status' => '15'])->save();

            (new TemplateRepository)->send_pay_common_message($order);// 发送门诊支付成功模板消息
            (new SMSCodeRepository())->clinic_ispay_send_smscode_to_user_and_doctor($bespeak);//短信通知患者和医生
            (new SMSCodeRepository())->send_message_customer_for_bespeak_is_pay($bespeak);//短信通知客服预约已支付
            (new JPushRepository())->remaind_doctor_clinic_patient_ispay($bespeak->doctor_id);// 网诊支付成功提示医师
        }
    }
}
