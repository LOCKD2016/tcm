<?php
namespace App\Transformers;

/**
 * Class SectionTransformers
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class SectionTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id, //编号
            'name' => $model->name, //名称
        ];
    }
}