<?php

namespace App\Transformers;

use App\Util\Tools;

/**
 * Class PrescriptionTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class PrescriptionTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'disease' => $model->disease,
            'disease_zh' => $model->disease_zh,
            'disease_en' => $model->disease_en,
            'recipe_head' => $model->recipe_head,
            'recipe' => $model->recipe,
            'recipe_self' => $model->recipe_self,
            'medicinal_type_name' => $model->medicinal_type_name,
            'medicine_price' => $model->medicine_price/100,
            'tisane_price' => $model->tisane_price/100,
            'dispensing_price' => $model->dispensing_price/100,
            'recipe_self_price' => $model->recipe_self_price/100,
            'tisane' => $model->tisane,
            'express' => $model->express,
            'remind' => $model->remind,
            'recipe_remark' => $model->recipe_remark,
            'time' => $model->time,//创建时间
        ];
    }

    /**
     * @author zhoupeng
     * @var array
     */
    protected $availableIncludes = [
        'user', 'doctor', 'order'
    ];

    public function includeUser($model)
    {
        $user = $model->user;

        if(!empty($user)){

            return $this->item($user, new UserTransformer());

        }
    }

    public function includeDoctor($model)
    {
        $doctor = $model->doctor;

        if(!empty($doctor)){

            return $this->item($doctor, new DoctorTransformer());
        }
    }

    public function includeOrder($model)
    {
        $order = $model->order;

        if(!empty($order)){

            return $this->item($order, new OrderTransformer());
        }
    }

}