<?php

namespace App\ApiTransformers;


class BespeakTransformer extends BaseTransformer
{


    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'disease' => $model->disease,//疾病名称
            'remark' => $model->remark,//备注
            'first' => $model->first,//1出诊,2复诊
            //'user' => $this->user($model->user)
            "family_id" => $model->user->id, //诊疗人的id
            "family_name" => $model->user->realname, //诊疗人的名
            "family_sex" => $model->user->sex($model->user_clinic->sex), //诊疗人的性别
            "family_age" => $model->user->age ?: "未知", //诊疗人的名年龄
            "family_headimg" => $model->user->headimgurl, //诊疗人的头像
        ];
    }

//    public function user($user)
//    {
//        if($user)
//        {
//            return [
//                'id' => $user->id,
//                'realname' => $user->realname,
//                'sex' => $user->sex,
//                'headimgurl' => $user->headimgurl,
//                'birthday' => $user->birthday,
//            ];
//        }
//        return [];
//    }


}