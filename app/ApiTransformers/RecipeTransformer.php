<?php

namespace App\ApiTransformers;


class RecipeTransformer extends BaseTransformer
{


    public function transformData($model)
    {
        return [
            "id" => $model->id,
            "title" => $model->title,
            "content" => $model->content,
            "type" => $model->type,
            "doctor_id" => $model->doctor_id,
        ];
    }




}