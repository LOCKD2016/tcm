<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class DoctorTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "photoSUrl" => $model->head_img_L,
            "mobile" => $model->mobile,
            "name" => $model->name,
            "is_clinic" => $model->clinic ? '开通' : '关闭',
            "clinic" => $model->clinic,
            "loginName" => $model->loginName,
            "code" => $model->code,
            "status" => $this->status($model->status),
            "apply" => $model->status,
            "source" => $model->source,
            "qualification_auth" => empty($model->qualification_auth) ? 1 :2,
            "net_chat" => $this->net_chat($model->web),
            "web" => $model->web,
        ];
    }

    public function status($model){
        if($model ==0){
            return '待审核';
        }elseif ($model == 1){
            return "审核通过";
        }else{
            return "审核不通过";
        }
    }
    public function net_chat($model){
        if($model ==0){
            return '关闭';
        }elseif ($model == 1){
            return "开通";
        }
    }

}