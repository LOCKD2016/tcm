<?php
namespace App\Transformers\Platform;

/**
 * Class PrescriptionTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class PrescriptionTransformer extends BaseTransformer
{
    /**
     * @Auth: Nnn
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'doctor' => $this->DoctorTransformer($model->doctor),
            'user' => $this->UserTransformer($model->user),
            'disease' => $model->disease,
            'recipe_head' => $model->recipe_head,
            'recipe' => $model->recipe,
            'recipe_self' => $model->recipe_self,
            'medicinal_type_name' => $model->medicinal_type_name,
            'medicine_price' => $model->medicine_price,
            'tisane_price' => $model->tisane_price,
            'dispensing_price' => $model->dispensing_price,
            'recipe_self_price' => $model->recipe_self_price,
            'tisane' => $model->tisane,
            'express' => $model->express,
            'remind' => $model->remind,
            'recipe_remark' => $model->recipe_remark,
            'created_at' => !empty($model->created_at) ? $model->created_at->toDateTimeString() : '',
        ];
    }

    /**
     * 医生数据
     * @param $model
     * @return array
     */
    public function DoctorTransformer($model)
    {
        if($model)
        {
            return [
                'name' => $model->name,
                'customer_code' => count($model->cliniques) ? $model->cliniques[0]->pivot->code : ''
            ];
        }

        return [];
    }

    /**
     * 用户数据
     * @param $model
     * @return array
     */
    public function UserTransformer($model)
    {
        if($model)
        {
            return [
                'name' => $model->realname,
                'customer_code' => $model->customer_code
            ];
        }

        return [];
    }

}