<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class ClinicDetailTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "disease" => $model->disease,
            "content" => $model->content,
            "type" => $model->type ? '门诊' : '网诊',
            "first" => $model->first ? '复诊' : '初诊',
            "end_time" => $model->end_time,
            "status" => $model->status,
            "doctor" => $this->doctorTransformer($model->doctor),
            "user" => $this->userTransformer($model->user),
            "bespeak" => $this->bespeakOrderTransformer($model->bespeak),
            "message" => $model->messageList,
            "prescription" => $this->prescriptionTransformer($model->prescription),
            "created_at" => !empty($model->created_at) ? $model->created_at->toDateTimeString() : '',
        ];
    }

    public function prescriptionTransformer($prescription)
    {
        if($prescription)
        {
            return [
                'id' => $prescription->id,
                'order' => $prescription->order,
                'recipe' => $prescription->recipe,
                'medicinal_type_name' => $prescription->medicinal_type_name,
                'recipe_self' => $prescription->recipe_self,
                'disease' => $prescription->disease,
                'recipe_head' => $prescription->recipe_head,
                'recipe_remark' => $prescription->recipe_remark,
            ];
        }
    }
    /**
     * @param $message
     */
    public function messageListTransformer($messageList)
    {
        if($messageList)
        {
            return [
                'messageDetail' => $messageList->message
            ];
        }
    }
    /**
     * @param $doctor
     * @return array
     */
    public function doctorTransformer($doctor)
    {
        if($doctor)
        {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
            ];
        }

        return [];
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
                'sex' => $this->sex($user->sex),
                'birthday' => $user->birthday,
            ];
        }

        return [];
    }

    /**
     * @param $user
     * @return array
     */
    public function bespeakOrderTransformer($bespeak)
    {
        if($bespeak)
        {
            return [
                'order' => $this->orderTransformer($bespeak->order),
            ];
        }

        return [

        ];
    }

    /**
     * @param $user
     * @return array
     */
    public function orderTransformer($order)
    {
        if($order)
        {
            return [
                'order_sn' => $order->order_sn,
            ];
        }

        return [

        ];
    }

    /**订单的状态 0 未支付 1已支付 2退款中 3已退款
     * @param $status
     */
    public function orderStatus($status)
    {
        switch ($status)
        {
            case 0:
                return '未支付';
                break;
            case 1:
                return '已支付';
                break;
            case 2:
                return '退款中';
                break;
            case 3:
                return '已退款';
                break;
        }
    }

    /**
     * 性别
     * @param $sex
     */
    public function sex($sex)
    {
        switch ($sex)
        {
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