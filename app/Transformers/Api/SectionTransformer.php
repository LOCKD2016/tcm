<?php
namespace App\Transformers\Api;

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
            'status' => $model->status, //名称
            'disease' => $model->disease, //名称
        ];
    }
}