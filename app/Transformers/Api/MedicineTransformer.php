<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class MedicineTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "name" => $model->name,
            "unit" => $model->unit,
            "amount" => $model->amount / 100,
            "type" => $this->type($model->type),
            "created_at" => !empty($model->created_at) ? $model->created_at->toDateTimeString() : '',
        ];
    }

    /**
     * 类型 1单品 2套餐 3其他
     * @param $doctor
     * @return array
     */
    public function type($type)
    {
        switch ($type)
        {
            case 1:
                return '单品';
                break;
            case 2:
                return '套餐';
                break;
            case 3:
                return '其他';
                break;
            default:
                return '未知';
                break;
        }
    }

}