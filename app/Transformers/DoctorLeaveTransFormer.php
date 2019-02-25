<?php

namespace App\Transformers;

/**
 * Class DoctorLeaveTransFormer
 * @package App\Transformers
 */
class DoctorLeaveTransFormer extends BaseTransformer
{

    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'doctor_id' => $model->doctor_id,
            'day' => $model->day, //天数
            'start_time' => $model->start_time, //开始时间
            'end_time' => $model->end_time, //结束时间
            'status' => $model->status, //审核状态
        ];
    }
}