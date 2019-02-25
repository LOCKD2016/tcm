<?php
/**
 * Created by PhpStorm.
 * User: vliang
 * Date: 16/9/10
 * Time: 下午10:47
 */

namespace App\Transformers\Api;

class CommentTransformer extends BaseTransformer
{
    public function transformData($model)
    {
        return [
            "id" => $model->id,
            "disease" => $model->disease,
            "content" => $model->content,
            "condition" => $this->condition($model->condition),
            "manner" => 'star_' . $model->manner,
            "effect" => $model->effect,
            "status" => $model->status,
            "doctor" => $this->doctorTransformer($model->doctors),
            "user" => $this->userTransformer($model->user),
            "created_at" => !empty($model->created_at) ? $model->created_at->toDateTimeString() : '',
        ];
    }

    /**
     * @param $condition
     * 1:痊愈: 2:有效 3：无效 4：恶化
     */
    public function condition($condition)
    {
        switch ($condition) {
            case 1:
                return '痊愈';
                break;
            case 2:
                return '有效';
                break;
            case 3:
                return '无效';
                break;
            case 4:
                return '恶化';
                break;
        }
    }

    /**
     * @param $doctor
     * @return array
     */
    public function doctorTransformer($doctor)
    {
        if ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
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
        if ($user) {
            return [
                'id' => $user->id,
                'realname' => $user->realname,
            ];
        }

        return [];
    }


}