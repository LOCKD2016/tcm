<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\ApiTransformers;

use App\Util\YunZhongYi;

class UserTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "mobile" => $model->mobile,
            "birthday" => $model->birthday,
            "sex" => $this->type($model->sex),
            "age" => $model->age==0 ? '': $model->age,
            "pincode" => $model->pincode,
            "country" => $model->country,
            "nickname" => $model->nickname,
            "realname" => $model->realname,
            "headimgurl" => $model->headimgurl,
            "height" => $model->height==0 ? '': $model->height,
            "weight" => $model->weight==0 ? '': $model->weight,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "im_token" => $model->im_token,
            "notice_status" => $model->notice_status, //推送状态 1默认推送 2不推送
            "card" => $model->card, //会员卡信息
            "vip" => $this->vip($model->card), //会员卡类型 0没有会员卡 1家庭卡 2VIP卡
            "com_num" => empty($model->com_num)?0:$model->com_num, //待评价次数
            "score" => empty($model->score)?0:$model->score, //积分
        ];
    }

    public function type($type){
        switch($type){
            case 0:
                return '';
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
    public function vip($card){
        if(isset($card) && !empty($card)){
            return $card->card_type;
        }else{
            return 0;
        }
    }

    public function card($card){
        if($card){
            return (new YunZhongYi())->get_card_data_by_api($card->card_no);
        }
    }

}