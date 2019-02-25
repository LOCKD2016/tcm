<?php
namespace App\Transformers;

/**
 * Class SwiperTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class SwiperTransformer extends BaseTransformer
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
            'title' => $model->title,
            'desc' => $model->desc,
            'image' => $model->image,
            'url' => $model->url,
        ];
    }

}