<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class AppUserTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "mobile" => $model->mobile,
            "sex" => $this->type($model->sex),
            "age" => $model->age==0 ? '': $model->age,
            "pincode" => empty($model->pincode)?'暂无':$model->pincode,
            "county" => empty($model->county)?'暂无':$model->county,
            "nickname" => $model->nickname,
            "realname" => $model->realname,
            "headimgurl" => $model->headimgurl,
            "height" => $model->height==0 ? '暂无': $model->height,
            "weight" => $model->weight==0 ? '暂无': $model->weight,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "source" => $model->source == 1 ? '微信注册' : '同步', //1微信注册 2同步,
            "notice_status" => $model->notice_status, //推送状态 1默认推送 2不推送
        ];
    }

    public function type($type){
        switch($type){
            case 0:
                return '未知';
                break;
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
            default:
                return '';
                break;
        }
    }
}