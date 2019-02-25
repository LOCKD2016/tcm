<?php

namespace App\Transformers;

/**
 * Class CommentTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class CommentTransformer extends BaseTransformer
{
    /**
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
            'id' => $model->id,//编号
            'disease' => $model->disease, //医生给的疾病名称
            'condition' => $model->condition, //病情  1:痊愈: 明显好转 3：好转 4：没变化 等等
            'manner' => $model->manner, //态度 对应的几颗星
            'effect' => $model->effect, //疗效
            'content' => $model->content, //评论内容
            'status' => $model->status, //暂存类型 0 未审核 1审核通过 2审核不通过
            'time' => $model->time,//时间
        ];
    }

    /**
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser($model)
    {
        $user = $model->user;

        if ($user)
            return $this->item($user, new UserTransformer());
    }

}