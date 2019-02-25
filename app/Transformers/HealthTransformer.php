<?php

namespace App\Transformers;

/**
 * Class HealthTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class HealthTransformer extends BaseTransformer
{

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id, //用户编号
            'date' => $model->date, //日期时间
            'content' => json_decode($model->content,true), //内容
            'day_avg' => $model->day_avg, //当天平均值
            'type' => $model->type, //类型
        ];
    }

}