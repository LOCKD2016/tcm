<?php
namespace App\Transformers;


/**
 * Class DiseaseTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class DiseaseTransformer extends BaseTransformer
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
            'sort' => $model->sort,
        ];
    }

}