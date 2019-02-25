<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class LogsTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "user_id" => $model->user_id,
            "ip" => $model->ip,
            "created_at" => $model->created_at->toDateTimeString(),
            "user_name" => $model->user_name,
            "user_realname" => $model->user_realname,
            "updated_at" => $model->updated_at->toDateTimeString(),
            "group_name" => $model->group_name,
            "address" => $model->address,
        ];
    }
}