<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api\Export;

class SendPrescriptionTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "order_sn" => $model->order_sn,
            "user" => $model->user->realname,
            "user_name" => $model->user_name,
            "mobile" => $model->mobile,
            "address" => $model->province.$model->city.$model->district.$model->address,
            "prescription" => $this->recipeTransformer($model->prescription->recipe),
            "tisane" => $model->prescription->tisane ? '代煎' : '自煎',
            "express_number" => $model->express_number,
            "desc" => $model->desc,
        ];
    }

    /**
     * @param $user
     * @return array
     */
    public function recipeTransformer($recipe)
    {
        $res = '';
        if (!empty($recipe)) {
            $recipe = array_map(function ($val) {
                if (!isset($val['other'])) {
                    $val['other'] = '';
                }
                return $val['other'] ? $val['name'] . '(' . $val['dosage'] . $val['unit'] . ',' . $val['other'] . ')' :
                    $val['name'] . '(' . $val['dosage'] . $val['unit'] . ')';
            }, $recipe);
            $res = implode(' ', $recipe);
        }

        return $res;

    }

}