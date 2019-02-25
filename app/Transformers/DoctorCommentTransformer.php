<?php

namespace App\Transformers;

/**
 * Class DoctorCommentTransFormer
 * @package App\Transformers
 */
class DoctorCommentTransformer extends BaseTransformer
{


    /**
     * @Auth: lx
     * @var array
     */
    protected $availableIncludes = [
        'user'
    ];

    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,//疾病名称
            'disease' => $model->disease,//疾病名称
            'condition' => $model->condition,//病情
            'manner' => $model->manner, //评价星级
            'effect' => $model->effect, //疗效
            'content' => $model->content, //结束时间
            'status' => $model->status, //审核状态
            'time' => date('Y-m-d H:i:s', strtotime($model->created_at)), //审核状态
        ];
    }

    /**
     * 用户11
     * @Auth: lx
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