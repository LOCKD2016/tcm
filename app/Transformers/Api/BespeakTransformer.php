<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class BespeakTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "order_id" => $model->order_id,
            "doctor" => $this->doctorTransformer($model->doctor),
            "admin" => $this->adminTransformer($model->admin),
            "user" => $this->userTransformer($model->user),
            "order" => $this->orderTransformer($model->order),
            "disease" => $model->disease,
            "type" => $model->type ? '门诊' : '网诊',
            "first" => $model->first,
            "start_time" => $model->start_time,//预约开始时间
            "end_time" => $model->end_time,//预约结束时间
            "redundant_first" => $model->redundant_first,
            "redundant_in" => $model->redundant_in,
            "status" => $this->bespeakStatus($model->status),
            "remark" => empty($model->remark) ? '' : $model->remark,
            "bespeak_code" => $model->bespeak_code,
            "subscribe_time" => $model->subscribe_time,
            "wait_time" => $model->type ? '' : $this->wait($model->created_at, $model->take_time),
            "take_time" => $model->take_time,
            "created_at" => empty($model->created_at) ? '' : $model->created_at->toDateTimeString() ,
        ];
    }

    /**
     * 接诊等待时间
     * @param $start
     * @param $end
     * @return float
     */
    public function wait($start, $end)
    {
        if ($end) {
            return ceil((strtotime($end) - strtotime($start)) / (60));
        } else {
            return ceil((time() - strtotime($start)) / 60);
        }
    }

    //5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消
    public function bespeakStatus($status)
    {
        switch ($status)
        {
            case 5:
                return '待接诊';
                break;
            case 10:
                return '待支付';
                break;
            case 15:
                return '已支付';
                break;
            case 20:
                return '诊疗中';
                break;
            case 25:
                return '诊疗结束';
                break;
            case 30:
                return '医生拒绝接诊';
                break;
            case 35:
                return '已取消';
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
                'status' => $this->orderStatus($order->status),
                'status_code' => $order->status,
                'pay_amount' => round($order->pay_amount/100,2),
                'id' => $order->id,
            ];
        }

        return [
            'status' => '未支付'
        ];
    }

    /**订单的状态 订单的状态 0 未支付 2正在支付 5已支付 9退款中 10已退款
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
                return '支付中';
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