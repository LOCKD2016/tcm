<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class AppUserDetailTransformer extends BaseTransformer
{
    public function transformData($model){

        return [
            "id" => $model->id,
            "mobile" => $model->mobile,
            "sex" => $this->type($model->sex),
            "age" => $model->age==0 ? '': $model->age,
            "pincode" => empty($model->pincode)?'暂无':$model->pincode,
            "county" => empty($model->county)?'暂无':$model->county,
            "nickname" => $model->nickname,
            "realname" => $model->realname,
            "headimgurl" => $model->headimgurl,
            "height" => $model->height==0 ? '暂无': $model->height,
            "weight" => $model->weight==0 ? '暂无': $model->weight,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "clinics" => $this->clinic($model->clinics),
            "notice_status" => $model->notice_status, //推送状态 1默认推送 2不推送
        ];
    }

    public function type($type){
        switch($type){
            case 0:
                return '未知';
                break;
            case 1:
                return '男';
                break;
            case 2:
                return '女';
                break;
            default:
                return '';
                break;
        }
    }

    /**
     * @param $clinic
     */
    public function clinic($clinic)
    {
        $data = [];

        foreach ($clinic as $key => $val) {
            $data[] = $this->clinicTransformer($val);
        }
        
        return $data;

    }

    /**
     * @param $clinic
     */
    public function clinicTransformer($clinic)
    {
        return [
            'id' => $clinic->id,
            'type' => $clinic->type,
            'end_time' => $clinic->end_time,
            'disease' => $clinic->disease,
            'doctor' => $clinic->doctor->name,
            'created_at' => $clinic->created_at ? date('Y-m-d H:i:s',strtotime($clinic->created_at)) : '',
            'prescription' => $clinic->prescription ? $this->prescriptionTransformer($clinic->prescription) : '',
        ];
    }

    /**
     * 药方
     * @param $prescription
     */
    public function prescriptionTransformer($prescription)
    {
        if($prescription) {
            return [
                'id' => $prescription->id,
                'recipe' => $this->recipe($prescription->recipe),
                'recipe_head' => '共'.$prescription->recipe_head['sum']. '剂，每日'.$prescription->recipe_head['dayNum'].'剂，1剂分'.$prescription->recipe_head['takingNum'].'次服用'
            ];
        }

        return [];
    }

    /**
     * @param $recipe
     * @return string
     */
    public function recipe($recipe)
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