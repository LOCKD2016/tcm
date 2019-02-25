<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\ApiTransformers;


class DoctorLeaveTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "leave_day" => $model->leave_day,
            "start_time" => date('Y-m-d H:i',strtotime($model->start_time)),
            "end_time" => date('Y-m-d H:i',strtotime($model->end_time)),
        ];
    }

}