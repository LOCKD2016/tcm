<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class DiseaseTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "name" => $model->name,
        ];
    }


}