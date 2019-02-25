<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\ApiTransformers;


class PrescriptionTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "title" => $model->title,
            "detail" => $model->detail,
            "type" => $model->type,
            "doctor_id" => $model->doctor_id,
        ];
    }
}