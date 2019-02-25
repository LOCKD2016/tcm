<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/19
 * Time: 下午19:47
 */

namespace App\Transformers\Api;

class RoleTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "name" => $model->name,
            "display_name" => $model->display_name,
            "description" => $model->description,
            "auth" => $model->auth,
        ];
    }
}