<?php

namespace App\Transformers;


/**
 * Class InquiryTransform
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class InquiryTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'user',
    ];

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,//用户编号
            'bespeak_id' => $model->bespeak_id,//预约编号
            'clinic_id' => $model->clinic_id,//诊疗编号
            'disease' => is_array(json_decode($model->disease, true)) ? json_decode($model->disease, true) : $model->disease,
            'type' => $model->type, //问诊单类型
            'desc' => $model->desc,
            'time' => $model->time,//创建时间
        ];
    }

    /**
     * 用户
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser($model)
    {
        $user = $model->user;

        if (!empty($user))

            return $this->item($user, new UserTransformer());
    }

}