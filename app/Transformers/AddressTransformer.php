<?php

namespace App\Transformers;

/**
 * Class AddressTransformer
 * @package App\Transformers
 */
class AddressTransformer extends BaseTransformer
{
    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id, //姓名
            'user_name' => $model->user_name, //姓名
            'mobile' => $model->mobile,
            'country' => $model->country,
            'province' => $model->province,//省份
            'city' => $model->city,//城市
            'district' => $model->district,//区县
            'address' => $model->address,//详细地址
            'is_default' => $model->is_default,//是否是默认
        ];
    }
}