<?php
/**
 * Created by PhpStorm.
 * User: alex807
 * Date: 17/3/22
 * Time: 下午17:34
 */

namespace App\ApiTransformers;

class FamilyTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "i" => $model->i,
            "id" => $model->id,
            "mobile" => $model->mobile,
            "nickname" => $model->nickname,
            "realname" => $model->realname,
            "headimgurl" => $model->headimgurl,
            "birthday" => $model->birthday,
            "height" => $model->height,
            "weight" => $model->weight,
            "country" => $model->country,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "age" => $model->age,
            "pincode" => $model->pincode,
            "sex" => $this->sex($model->sex)
        ];
    }

    public function sex($model){
        switch($model){
            case 0:
                return '未知';
                break;
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
        }
    }
}