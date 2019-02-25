<?php

namespace App\Listeners;

use App\Events\OrderPayment;
use App\Repository\ExamRepository;
use App\Repository\ClinicRepository;
use App\Repository\JPushRepository;
use App\Repository\MessageRepository;
use App\Repository\SMSCodeRepository;
use App\Repository\TemplateRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * 网诊预约
 * Class WebBespeak
 * @package App\Listeners
 */
class WebBespeak
{
    /**
     * @var ClinicRepository
     */
    public $clinicRepository;

    /**
     * @var MessageRepository
     */
    public $messageRepository;

    /**
     * @var ExamRepository
     */
    public $examRepository;

    /**
     * Create the event listener.
     * @param ClinicRepository $clinicRepository
     * @param MessageRepository $messageRepository
     * @return void
     */
    public function __construct(ClinicRepository $clinicRepository, MessageRepository $messageRepository, ExamRepository $examRepository)
    {
        $this->clinicRepository = $clinicRepository;
        $this->messageRepository = $messageRepository;
        $this->examRepository = $examRepository;
    }

    /**
     * Handle the event.
     * @desc 网诊预约
     *      1、创建诊疗信息
     *      2、修改预约状态为已支付
     *      3、聊天列表数据修改状态
     *
     * @param  OrderPayment $event
     * @return void
     */
    public function handle(OrderPayment $event)
    {
        $order = $event->order;

        if ($order->order_type == '2') { //网诊

            $bespeak = $order->bespeak;//通过订单获取诊疗信息

            //生成诊疗
            $clinic = $this->clinicRepository->create([
                'bespeak_id' => $bespeak->id,
                'user_id' => $bespeak->user_id,
                'doctor_id' => $bespeak->doctor_id,
                'type' => '0', //网诊
                'first' => $bespeak->first, //是否是初诊
                'status' => '5',//诊疗中
            ]);

            //预约的标准问诊单 关联诊疗
            $bespeak->inquiry()->update(['clinic_id' => $clinic->id]);

            //修改诊疗状态为已支付 并且 预约关联诊疗
            $bespeak->loadEditData(['status' => '15', 'clinic_id' => $clinic->id])->save();

            //获取聊天记录列表
            $message_lists = $this->messageRepository->get_lists_by_user_and_doctor($bespeak->user_id, $bespeak->doctor_id);

            //第一次诊疗 没有列表要 创建
            if (empty($message_lists)) {
                $message_lists = $this->messageRepository->list_create([
                    'doctor_id' => $bespeak->doctor_id,
                    'user_id' => $bespeak->user_id,
                ]);
            }

            if (empty($message_lists)) return false;//如果列表还是空的 就说明程序错误 终止!

            //修改聊天列表 可以聊天 并且 最后一次诊疗
            $message_lists->loadEditData(['clinic_id' => $clinic->id, 'status' => '1'])->save();

            //用户关联医生
            $event->user->followDoctor($bespeak->doctor_id, 1);

            //获取标准问诊单的信息 作为医生和用户扩展字段显示 还有发送标准问诊单消息
            $inquiry = $bespeak->inquiry;

            if (!empty($inquiry)) {
                $extend = $inquiry->disease;

                //发送普通问诊单的消息
                $this->messageRepository->send_inquiry_message($message_lists->id, $inquiry->id, $inquiry->created_at);
            } else {
                $extend = null;
            }

            //医生关联用户
            $doctor = $bespeak->doctor;
            $doctor->relevance_user($bespeak->user_id, $extend);

            //发送系统消息
            $this->messageRepository->send_system_message($message_lists->id, '医生已接诊', $bespeak->take_time);


            //<!-------------------------------- 获取门诊的病历和药方，分别只取两条,并将这些信息发送到聊天页面中---------->

            //首先判断当前医生是否有查看药方权限
            if(isset($doctor->read_recipe) && $doctor->read_recipe==1){

                // 保存并发送门诊处方信息，暂时不通过医生编号筛选
                $clinique_recipe = (new ClinicRepository())->get_clinique_recipe_by_user_and_doctor_and_save($bespeak);

                if(count($clinique_recipe['first']))
                    $this->messageRepository->send_clinique_recipe_message($message_lists->id, $clinique_recipe['first']);
                if(count($clinique_recipe['second']))
                    $this->messageRepository->send_clinique_recipe_message($message_lists->id, $clinique_recipe['second']);

                // 保存并发送门诊病历(即问诊单)信息，暂时不通过医生编号筛选
                $clinique_exam = (new ClinicRepository())->get_clinique_exam_by_user_and_doctor_and_save($bespeak);

                if(count($clinique_exam))
                    $this->messageRepository->send_clinique_exam_message($message_lists->id, $clinique_exam);
            }

            //<!-------------------------------- 获取门诊的病历和药方，分别只取两条,并将这些信息发送到聊天页面中---------->

            //发送个性化问诊单消息
            $user = $bespeak->user;

            //初诊发送问诊单
            if(isset($inquiry->type) && $inquiry->type==1){
                //获取所要发送的问诊单类型
                $exam_type = $this->examRepository->get_exam_type($user);

                //获取问诊单编号
                $exam = $this->examRepository->get_data_by_doctor_and_type($bespeak->doctor_id, $exam_type);

                if (empty($exam)) {//没有获取到 可能这个医生没有 获取系统的
                    $exam = $this->examRepository->get_data_by_doctor_and_type(0, $exam_type);
                }

                if (empty($exam)) {//如果还没有获取到 可能这个后台没有配置 获取最原始默认数据
                    $exam = $this->examRepository->get_data_by_doctor_and_type(0, 0);
                }

                if ($exam) //如果还没有 那tmd 就不发了
                    $this->messageRepository->send_exam_message($message_lists->id, $exam->id);
            }


            (new SMSCodeRepository())->send_message_customer_for_bespeak_is_pay($bespeak);//短信通知客服预约已支付
            (new TemplateRepository)->send_pay_common_message($order);// 发送支付成功模板消息
            (new JPushRepository())->remaind_doctor_net_patient_ispay($bespeak->doctor_id);// 网诊支付成功提示医师
        }
    }
}
