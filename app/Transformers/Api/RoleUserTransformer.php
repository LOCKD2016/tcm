<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class RoleUserTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "user_id" => $model->user_id,
            "user_name" => $model->user_name,
            "user_realname" => $model->user_realname,
            "user_email" => $model->user_email,
            "user_phone" => $model->user_phone,
            "user_address" => $model->user_address,
            "group_name" => $model->group_name,
            "role_id" => $model->role_id,
            "user_last_login_time" => $model->user_last_login_time,
        ];
    }
}