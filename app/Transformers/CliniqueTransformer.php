<?php

namespace App\Transformers;

/**
 * Class CliniqueTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class CliniqueTransformer extends BaseTransformer
{
    /**
     * @var array
     */
    protected $availableIncludes = [
        'schedules',
    ];

    /**
     * @var array
     */
    protected $defaultIncludes = [
//        'schedules'
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
            'name' => $model->name,
            'content' => $model->content,
            'address' => $model->address,
            'telephone' => $model->telephone,
            'isSchedules' => $model->isSchedules,//加了个判断是否有排班，其实这种写法并不好
        ];
    }

    /**
     * 排班 医生排版可以设置为schedules属性
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSchedules($model)
    {
        $schedules = $model->schedules;

        if ($schedules)

            return $this->collection($schedules, new ScheduleTransformer());
    }
}