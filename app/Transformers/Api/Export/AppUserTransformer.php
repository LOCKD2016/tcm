<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api\Export;

class AppUserTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "nickname" => $model->nickname,
            "realname" => $model->realname,
            "sex" => $this->type($model->sex),
            "age" => $model->age==0 ? '': $model->age,
            "mobile" => $model->mobile,
            "province" => $model->province.$model->city.$model->area,
            "source" => $model->source == 1 ? '微信注册' : '同步', //1微信注册 2同步,
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