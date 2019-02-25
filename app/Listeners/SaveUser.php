<?php

namespace App\Listeners;

use App\Events\SaveUser as SaveUserEvents;
use App\Services\SoapServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveUser
{

    public $soapServices;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SoapServices $soapServices)
    {
        $this->soapServices = $soapServices;
    }

    /**
     * Handle the event.
     *
     * @param  SaveUser $event
     * @return void
     */
    public function handle(SaveUserEvents $event)
    {
        //同步到所有的诊所

        $user = $event->user;
        $clinique = $event->clinique;

        //判断下是否已经有啦
        $has = $user->cliniques()->where('clinique_id', $clinique->id)->count();

        if ($has) return;//如果有就停止

        $addData = [
            'CUSTOMER_CODE' => '', //添加的时候是空 属性必须 但是可以为空！！！！！！！！！！
            'CUSTOMER_NAME' => $user->realname,//患者姓名
            'GENDER' => $user->sex == '1' ? "10007" : "10008",//性别 {男："10007", 女："10008"} database:性别 0未知 1男 2女
            'CONTACT' => $user->mobile, //手机号
            'STR_BIRTHDAYDATE' => date('Ymd', strtotime($user->birthday)), //出生日期（yyyyMMdd）
            'Certification_CODE' => $user->idType == '0' ? '2800' : ($user->idType == '2' ? '0000143365' : '0000143366'),//证件类型{身份证: "2800",护照: "0000143365",其他类型: "0000143366"}
            'Certification_NUM' => $user->idNo, //证件号码
            'Address_LINE' => $user->province . '' . $user->city . '' . $user->area,//家庭住址
            'MED_RECORD_CODE' => '',// 病历号
            'OPREATOR' => '移动端创建用户', //操作人员姓名
            'CREATE_COMPANY_CODE' => $clinique->code,//创建公司编号(默认编号GS_O1)
        ];

        $res = $this->soapServices->addUser($addData);

        if ($res['state'] === 1) { //添加成功
            $user->cliniques()->attach($clinique->id, ['code' => $res['data']['CUSTOMER_CODE']]);
        }else{
            if($res['message'] == "此客户已存在!"){
                $user->cliniques()->attach($clinique->id, ['code' => $res['data']['CUSTOMER_CODE']]);
            }
        }

    }
}
