<?php

namespace App\Transformers;

/**
 * Class GoodTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class GoodTransformer extends BaseTransformer
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
            'name' => $model->name,
            'name' => $model->name,
            'number' => $model->pivot->number,
            'amount' => $model->pivot->amount / 100,//(单位:元)
            'attr' => $model->pivot->attr,
            'extend' => $model->pivot->extend,
        ];
    }
}