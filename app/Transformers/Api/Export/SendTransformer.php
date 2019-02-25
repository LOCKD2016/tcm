<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api\Export;

class sendTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "order_sn" => $model->order_sn,
            "user" => $model->user->realname,
            "user_name" => $model->user_name,
            "mobile" => $model->mobile,
            "address" => $model->country.$model->province.$model->city.$model->district.$model->address,
            "prescription" => '',
            "tisane" => '',
            "express_number" => $model->express_number,
            "desc" => $model->desc,
        ];
    }

    /**
     * 支付方式
     * @param $type
     * @return string
     */
    public function payType($type)
    {
        switch ($type)
        {
            case 0:
                return '未付款';
                break;
            case 1:
                return '微信';
                break;
            case 5:
                return '免费订单';
                break;
        }
    }
    //1门诊 2网诊 3药方 4商品
    public function orderType($type)
    {
        switch ($type)
        {
            case 1:
                return '门诊';
                break;
            case 2:
                return '网诊';
                break;
            case 3:
                return '药方';
                break;
            case 4:
                return '商品';
                break;
        }
    }
    /**
     * @param $user
     * @return array
     */
    public function bespeakTransformer($order)
    {
        if($order)
        {
            return $this->doctorTransformer($order->doctor);
        }

        return '';
    }

    /**
     * @param $user
     * @return array
     */
    public function recipeTransformer($recipe)
    {
        if($recipe)
        {
            return [
                'id' => $recipe->id,
                'recipe' => $recipe->recipe,
                'tisane' => $recipe->tisane,
                'express' => $recipe->express,
            ];
        }

        return [];
    }

    /**
     * @param $doctor
     * @return array
     */
    public function doctorTransformer($doctor)
    {
        if($doctor)
        {
            return $doctor->name;
        }

        return '';
    }


    /**
     * @param $user
     * @return array
     */
    public function userTransformer($user)
    {
        if($user)
        {
            return [
                'id' => $user->id,
                'realname' => $user->realname,
            ];
        }

        return [];
    }



    /**订单的状态 0 未支付 2正在支付 5已支付 9退款中 10已退款
     * @param $status
     */
    public function orderStatus($status)
    {
        switch ($status)
        {
            case 0:
                return '未支付';
                break;
            case 2:
                return '正在支付';
                break;
            case 5:
                return '已支付';
                break;
            case 9:
                return '退款中';
                break;
            case 10:
                return '已退款';
                break;
        }
    }

}