<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/19
 * Time: 下午19:47
 */

namespace App\Transformers\Api;

class SwiperTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "title" => $model->title,
            "desc" => $model->desc,
            "image" => $model->image,
            "url" => $model->url,
            "status" => $model->status,
        ];
    }
}