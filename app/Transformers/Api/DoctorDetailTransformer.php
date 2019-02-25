<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

use App\Models\Config;

class DoctorDetailTransformer extends BaseTransformer
{
    public function transformData($model){
        return [
            "id" => $model->id,
            "clinique_id" => $model->clinique_id,
            "name" => $model->name,
            "mobile" => $model->mobile,
            "birthday" => $model->birthday,
            "sex" => $model->sex,
            "address" => $model->address,
            "head_img_L" => $model->head_img_L,
            "intro" => $model->intro,
            "code" => $model->code,
            "desc" => $model->desc,
            "nation" => $model->nation,
            "native" => $model->native,
            "idType" => $model->idType,
            "idNo" => $model->idNo,
            "title" => Config::getTitle(),
            "titleId" => $model->title,
            "profession_auth" => explode(',',json_decode($model->profession_auth)),
            "qualification_auth" => explode(',', json_decode($model->qualification_auth)),
            "major_qualification_auth" => explode(',', json_decode($model->major_qualification_auth)),
            "web" => $model->web,
            "web_amount" => round($model->web_amount/100, 2),
            "video_amount" => round($model->video_amount/100, 2),
            "diseases" => $model->diseases,
            "sections" => $model->sections,
            "schedules" => $this->schedules($model),
            "cliniques" => $model->cliniques,
            "leave" => $model->leave,
            "level" => $model->level,
            "diy_level" => $model->diy_level,
            "use_diy" => $model->use_diy,
            "read_recipe" => $model->read_recipe,
        ];
    }

    public function schedules($model)
    {
        $schedules = $model->schedulesClinique;

        if(count($schedules))
        {
            $data = [];

            foreach($schedules as $key => $val)
            {
                $data[$key]['start_time'] = date('Y-m-d H:i:s', strtotime($val->start_time));
                $data[$key]['end_time'] = date('Y-m-d H:i:s', strtotime($val->end_time));
                $data[$key]['clinque'] = $val->clinque;
            }

            return $data;
        }

        return [];

    }
}