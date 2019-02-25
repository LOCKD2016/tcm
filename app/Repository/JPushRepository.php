<?php
namespace App\Repository;

use App\Models\Clinique;
use App\Models\Doctor;
use App\Models\Error_note;
use App\Services\JPushServices;

class JPushRepository extends Repository{


    /**
     * 获取jpush_key
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 16:31
     * @param $doctor_id
     * @return mixed
     */
    public function get_JPush_key($doctor_id)
    {
        return $this->model->where('id',$doctor_id)->value('jpush_key');
    }

    public function get_doctor_detail($doctor_id)
    {
        return $this->model->find($doctor_id);
    }
    /**
     * 提示医师接诊推送
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 15:32
     * @param $user_id //用户id
     */
    public function remind_doctor_accept_clinic($doctor_id)
    {
        $jpush_key = $this->get_JPush_key($doctor_id);
        $doctor = $this->get_doctor_detail($doctor_id);

        if(!$jpush_key)
            (new ErrorNoteRepository)->storage(['code'=>$doctor_id, 'content'=> $doctor->name.'医师缺少极光推送key']); //记录错误信息

        $getCli = $this->getClinique()['name'];

        $data = [
            'title' => '【'.$getCli.'】接诊通知',//标题//原名泰和大国医
            'content' => '您有正在等待接诊的患者，请点击查看',//内容
            'extras' => [//扩展字段
                'type' => '1',
                'txt' => [
                    'type' => 'web',
                    'con' => '您有正在等待接诊的患者，请点击查看',
                ]
            ],
        ];
        (new JPushServices())->send($jpush_key,$data);
    }

    /**
     * 提示医生网诊已支付
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 17:09
     */
    public function remaind_doctor_net_patient_ispay($doctor_id)
    {
        $jpush_key = $this->get_JPush_key($doctor_id);
        $doctor = $this->get_doctor_detail($doctor_id);

        if($jpush_key)
            (new ErrorNoteRepository)->storage(['code'=>$doctor_id, 'content'=> $doctor->name.'医师缺少极光推送key']); //记录错误信息
        //systemType:ios noticeclicked-content==={"type":0,"value":{"content":"患者网诊预约支付成功","extra":{"_j_business":1,"_j_uid":6566355933},"extras":{"_j_business":1,"_j_uid":6566355933}}}

        $data = [
            'title' => '患者信息推送',
            'content' => '患者网诊预约支付成功',
            'extras' => [
                'type' => 1,
                'txt' => [
                    'type' => 'online',
                    'con' => '患者网诊预约支付成功'
                ]
            ],
        ];

        (new JPushServices())->send($jpush_key,$data);
    }

    /**
     * 提示医生门诊已支付
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 17:09
     */
    public function remaind_doctor_clinic_patient_ispay($doctor_id)
    {
        $jpush_key = $this->get_JPush_key($doctor_id);
        $doctor = $this->get_doctor_detail($doctor_id);

        if($jpush_key)
            (new ErrorNoteRepository)->storage(['code'=>$doctor_id, 'content'=> $doctor->name.'医师缺少极光推送key']); //记录错误信息

        $data = [
            'title' => '患者信息推送',
            'content' => '患者门诊预约支付成功',
            'extras' => [
                'type' => 1,
                'txt' => [
                    'type' => 'online',
                    'con' => '患者门诊预约支付成功'
                ]
            ],
        ];

        (new JPushServices())->send($jpush_key,$data);
    }

    /**
     * 提示医师患者已填写完问诊单
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 17:15
     * @param $doctor_id
     */
    public function remind_doctor_patient_complete_exam($doctor_id, $user_id,$message_list_id)
    {
        $jpush_key = $this->get_JPush_key($doctor_id);
        $doctor = $this->get_doctor_detail($doctor_id);
        $user = (new UserRepository)->get_detail_by_id($user_id);

        if($jpush_key)
            (new ErrorNoteRepository)->storage(['code'=>$doctor_id, 'content'=> $doctor->name.'医师缺少极光推送key']); //记录错误信息

        $getCli = $this->getClinique()['name'];

        $data = [
            'title' => '【'.$getCli.'】看诊通知',//【诊所名字】+看诊通知//原名泰和大国医
            'content' => $user->realname.'已提交问诊单，请点击查看。',
            'extras' => [
                'type' => 1,
                'txt' => [
                    'type' => 'clinic',
                    'con' => '患者网诊预约已提交问诊单',
                    'message_list_id' => $message_list_id
                ]
            ],
        ];

        (new JPushServices())->send($jpush_key,$data);
    }

    public function getClinique()
    {
        $clinique = Clinique::where('code','GS_01')->first();
        return $clinique;
    }

    public function model()
    {
        return new Doctor();
    }

}