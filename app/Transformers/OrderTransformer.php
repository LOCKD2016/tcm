<?php

namespace App\Transformers;
use App\Models\AppUser;

/**
 * Class OrderTransforme
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class OrderTransformer extends BaseTransformer
{

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'user', 'goods', 'bespeak', 'prescription'
    ];

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'order_sn' => $model->order_sn, //订单号
            'body' => $model->body, //商品描述
            'pay_time' => $model->pay_time, //订单支付时间
            'amount' => $model->amount / 100, //商品的总金额(元)
            'pay_amount' => $model->pay_amount / 100,//实际支付金额(元)
            'payable_amount' => $model->payable_amount / 100,//应支付金额(元)
            'refund_amount' => $model->refund_amount / 100, //已经退款金额(元)
            'order_type' => $model->order_type,//订单类型
            'pay_type' => $model->pay_type, //支付方式
            'pay_status' => $model->pay_status, //用户是否支付
            'status' => $model->status, //订单的状态
            'extend' => $model->extend, //扩展字段
            'desc' => $model->desc, //订单备注
            'address_id' => $model->address_id, //用户地址id
            'prescription' => $model->prescription, //药方信息
            'order_user' => [ //订单里面的用户信息
                'name' => $model->user_name ?? '',
                'country' => $model->country ?? '',
                'province' => $model->province ?? '',
                'city' => $model->city ?? '',
                'district' => $model->district ?? '',
                'address' => $model->address ?? '',
                'mobile' => $model->mobile ?? '',
            ],
            'express' => [ //订单 物流
                'express_company' => $model->express_company ?? '',//物流公司
                'express_number' => $model->express_number ?? '', //快递单号
                'shipping_time' => $model->shipping_time ?? '',//订单配送时间
            ],
            'clinique' => $this->getUserClinique($model->user_id),
        ];
    }

    /**
     * 用户
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser($model)
    {
        $user = $model->user;

        if (!empty($user))
            return $this->item($user, new UserTransformer());
    }

    /**
     * 商品
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeGoods($model)
    {
        $goods = $model->goods;

        if (!empty($goods))
            return $this->collection($goods, new GoodTransformer());
    }

    /**
     * 预约
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeBespeak($model)
    {
        if (in_array($model->order_type, [1, 2]) && $model->bespeak) { //网诊才有预约
            $bespeak = $model->bespeak;

            if (!empty($bespeak))
                return $this->item($bespeak, new BespeakTransformer());
        }
    }

    /**
     * 药方
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includePrescription($model)
    {
        if (($model->order_type == 3) && $model->prescription) { //药方信息
            $prescription = $model->prescription;

            if (!empty($prescription))
                return $this->item($prescription, new PrescriptionTransformer());
        }
    }

    /**
     *  根据用户ID获取用户诊所信息
     * @desc
     * @author Eric
     * @DateTime 2018/3/26 18:37
     * @param $user_id
     */
    public function getUserClinique($user_id)
    {
        $user = AppUser::with('cliniques')->find($user_id);
        return $user->cliniques()->first();
    }

}