<?php

namespace App\Transformers;

use App\Util\Tools;
/**
 * 排班
 * Class ScheduleTransformer
 * @package App\Transformers
 */
class ScheduleTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id, //排班的编号
            'clinique_id' => $model->clinique_id, //诊所编号
            'doctor_id' => $model->doctor_id, //医生编号
            'date' => $model->date,//日期
            'start_time' => $model->start_time,//日期
            'end_time' => $model->end_time,//日期
            'week_day' => Tools::getWeekday($model->date)
        ];
    }

}