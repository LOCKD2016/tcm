<?php

namespace App\Transformers;

/**
 * Class DoctorRemarkTransformer
 * @package App\Transformers
 */
class DoctorRemarkTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'content' => $model->content
        ];
    }

}