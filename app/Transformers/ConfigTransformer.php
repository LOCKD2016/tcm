<?php

namespace App\Transformers;

/**
 * Class ConfigTransformer
 * @package App\Transformers
 */
class ConfigTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'key' => $model->key,
            'value' => $model->value,
            'desc' => $model->desc,
        ];
    }
}