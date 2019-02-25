<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class DoctorCountTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "doctor" => $model->doctor,
            "bespeaks" => $model->bespeaks,
            "accept" => $model->accept,
        ];
    }

}