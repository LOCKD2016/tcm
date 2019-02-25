<?php

namespace App\ApiTransformers;


class BespeakClinicTransformer extends BaseTransformer
{


    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'disease' => $model->disease, //病名
            'time'=>date("H:i",strtotime($model->start_time)), //预约时间
            'user' => $this->user($model->user)
        ];
    }

    public function user($user)
    {
        if($user)
        {
            return [
                'id' => $user->id,
                'realname' => $user->realname,
                'sex' => $user->sex,
                'headimgurl' => $user->headimgurl,
            ];
        }
        return [];
    }


}