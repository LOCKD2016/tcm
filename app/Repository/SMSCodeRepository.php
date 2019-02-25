<?php

namespace App\Repository;

use App\Models\Admtelephones;
use App\Models\AppUser;
use App\Models\Clinique;
use App\Models\Doctor;
use App\Models\Smscode;
use App\Util\Tools;
use function GuzzleHttp\Psr7\str;

/**
 * Class SMSCodeRepository
 * @package App\Repository
 */
class SMSCodeRepository extends Repository
{
    /**
     * @return Smscode
     */
    public function model()
    {
        return new Smscode();
    }


    /**
     * 门诊支付成功，给医生和患者发送短信提醒
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/7 10:35
     * @param $user_id
     * @param $doctor_id
     * @param $bespeak_time
     */
    public function clinic_ispay_send_smscode_to_user_and_doctor($bespeak)
    {
        $user_id = isset($bespeak->user_id) ? $bespeak->user_id : 0;
        $doctor_id = isset($bespeak->doctor_id) ? $bespeak->doctor_id : 0;
        $clinique_id = isset($bespeak->clinique_id) ? $bespeak->clinique_id : 0;
        $bespeak_time = isset($bespeak->start_time) ? $bespeak->start_time : '';
        $user = (new UserRepository())->get_detail_by_id($user_id);
        $doctor = (new DoctorRepository())->get_doctor_by_id($doctor_id);
        $clinique = (new CliniqueRepository())->get_data_by_id($clinique_id);

        $user_name = isset($user->realname) ? $user->realname : '';
        $doctor_name = isset($doctor->name) ? $doctor->name : '';
        $bespeak_time = date('Y-m-d H:i', strtotime($bespeak->start_time));
        $address = isset($clinique->address) && $clinique->address ? $clinique->address : '泰和国医馆';

        if (isset($user->mobile)) {
            $user_message = '【泰和国医】您已成功预约专家号,就诊日期 ' . $bespeak_time . '请携带有效身份证原件准时就诊。就诊地址：北京市朝阳区新东路12号院 首开铂郡南区4号楼B1。';
            Tools::sendSms($user->mobile, $user_message);
        }
        if (isset($doctor->mobile)) {
            $doctor_message = '【泰和国医】 患者姓名：'.$user_name . '就诊日期' . $bespeak_time . '。';
            Tools::sendSms($doctor->mobile, $doctor_message);
        }
    }

    /**
     * 前一天晚上八点通知，短信通知第二天有门诊的患者
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/7 12:01
     * @param $bespeak
     */
    public function remind_user_tomorrow_is_clinic_day($bespeak)
    {
        if (isset($bespeak->user_id))
            $user = (new UserRepository())->get_detail_by_id($bespeak->user_id);
        if (isset($bespeak->doctor_id))
            $doctor = (new DoctorRepository())->get_doctor_by_id($bespeak->doctor_id);
        if (isset($bespeak->clinique_id))
            $clinique = (new CliniqueRepository())->get_data_by_id($bespeak->clinique_id);
        if (isset($user->mobile) && isset($bespeak->start_time)) {
            $doctor_name = isset($doctor->name) ? $doctor->name : '';
            $bespeak_time = date('Y-m-d H:i', strtotime($bespeak->start_time));
            $address = isset($clinique->address) && $clinique->address ? $clinique->address : '泰和国医馆';
            $user_message = '【泰和国医】您预约的专家号就诊日期为 ' . $bespeak_time . '，请携带有效身份证原件准时就诊。就诊地址：北京市朝阳区新东路12号院 首开铂郡南区4号楼B1。';
            Tools::sendSms($user->mobile, $user_message);
        }

    }

    /**
     *  短信通知客服，患者处方支付成功
     * @desc
     * @author Eric
     * @DateTime 2018/3/26 17:52
     * @param $order
     */
    public function send_message_customer_for_recipe_is_pay($order)
    {
        // 查询患者所在诊所
        $clinique_id = 0;
        $user = AppUser::with('cliniques')->find($order->user_id);
        if(isset($user->cliniques()->first()->pivot->clinique_id))
            $clinique_id = $user->cliniques()->first()->pivot->clinique_id;
        if($clinique_id==0)
            $clinique_id = Clinique::first()->id;
        //查询诊所所有的客服信息
        $customer_list = Admtelephones::where('clinique_id', $clinique_id)->where('status', 1)->get();

        //发送短信
        foreach ($customer_list as $k => $v) {
            $user_message = '【泰和国医】患者已经购买药品，需要药房配送。患者姓名：' . $user->realname . '，患者电话：' . $user->mobile . '。';
            if (Tools::isMobile($v->telephone))
                Tools::sendSms($v->telephone, $user_message);
            if (!Tools::isMobile($v->telephone))
                (new ErrorNoteRepository())->storage(['code' => $v->id, 'content' => '患者预约支付成功，短信通知客服，客服手机号非法！']);
        }
    }

    /**
     *  短信通知客服，患者预约支付成功
     * @desc
     * @author Eric
     * @DateTime 2018/3/26 17:52
     * @param $order
     */
    public function send_message_customer_for_bespeak_is_pay($bespeak)
    {
        // 查询患者所在诊所
        $clinique_id = 0;
        $user = AppUser::with('cliniques')->find($bespeak->user_id);
        if(isset($user->cliniques()->first()->pivot->clinique_id))
            $clinique_id = $user->cliniques()->first()->pivot->clinique_id;
        if($clinique_id==0)
            $clinique_id = Clinique::first()->id;
        //查询诊所所有的客服信息
        $customer_list = Admtelephones::where('clinique_id', $clinique_id)->where('status', 1)->get();
        //查询医师姓名
        $doctorName = Doctor::where('id', $bespeak->doctor_id)->value('name') ?: '';
        $bespeak_type = $bespeak->type > 0 ? '门诊预约' : '在线咨询';
        //发送短信
        foreach ($customer_list as $k => $v) {
            $user_message = '【泰和国医】患者姓名：' . $user->realname . '，预约类型：' . $bespeak_type . '，预约日期：' . date('Y-m-d H:i', strtotime($bespeak->start_time)) . '，预约专家：' . $doctorName . '，患者电话：' . $user->mobile . '。';
            if (Tools::isMobile($v->telephone))
                Tools::sendSms($v->telephone, $user_message);
            if (!Tools::isMobile($v->telephone))
                (new ErrorNoteRepository())->storage(['code' => $v->id, 'content' => '患者预约支付成功，短信通知客服，客服手机号非法！']);
        }
    }

}