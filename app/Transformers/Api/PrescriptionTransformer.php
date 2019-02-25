<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class PrescriptionTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "medicinal_type_name" => $model->medicinal_type_name,
            "recipe_self" => $model->recipe_self,
            "disease" => $model->disease,
            "recipe_head" => $model->recipe_head,
            "recipe" => $model->recipe,
            "medicine_price" => $model->medicine_price,
            "tisane_price" => $model->tisane_price,
            "dispensing_price" => $model->dispensing_price,
            "recipe_self_price" => $model->recipe_self_price,
            "price_time" => $model->price_time,
            "tisane" => $model->tisane,
            "send" => $model->send,
            "is_send" => $model->send ? '已发送' : '未发送',
            "express" => $model->express,
            "is_price" => $model->is_price,
            "priceStatus" => $this->price($model->is_price),
            "remind" => $model->remind,
            "remind_time" => $model->remind_time,
            "recipe_remark" => $model->recipe_remark,
            "admin_remark" => $model->admin_remark,
            "admin" => $this->adminTransformer($model->admin),
            "doctor" => $this->doctorTransformer($model->doctor),
            "order" => $this->orderTransformer($model->order),
            "user" => $this->userTransformer($model->user),
            "created_at" => !empty($model->created_at) ? $model->created_at->toDateTimeString() : '',
        ];
    }

    public function price($status)
    {
        // 0:未划价 1:已划价 3:已支付 4:已发货 5:已经到货 6:药方过期 7退款中 8已退款 9:拒绝开方
        switch ($status)
        {
            case 0:
                return '未划价';
                break;
            case 1:
                return '已划价';
                break;
            case 3:
                return '已支付';
                break;
            case 4:
                return '已发货';
                break;
            case 5:
                return '已经到货';
                break;
            case 6:
                return '药方过期';
                break;
            case 7:
                return '退款中';
                break;
            case 8:
                return '已退款';
                break;
            case 9:
                return '拒绝开方';
                break;
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
     * @param $admin
     * @return array
     */
    public function adminTransformer($admin)
    {
        if($admin)
        {
            return [
                'user_id' => $admin->user_id,
                'user_name' => $admin->user_name,
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
    public function orderTransformer($order)
    {
        if($order)
        {
            return [
                'user_name' => $order->user_name,
                'country' => $order->country,
                'province' => $order->province,
                'city' => $order->city,
                'district' => $order->district,
                'address' => $order->address,
                'mobile' => $order->mobile,
                'pay_amount' => $order->pay_amount,
                'status' => $this->orderStatus($order->status),
            ];
        }

        return [
            'status' => '未支付'
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