<?php

namespace App\Transformers;

/**
 * 医生的分组
 * Class GroupTransformer
 * @package App\Transformers
 */
class GroupTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'num' => (int) $model->num,
        ];
    }

}