<?php

namespace App\Transformers;

/**
 * Class MessageListTransFormer
 * @package App\Transformers
 */
class MessageListTransFormer extends BaseTransformer
{

    /**
     * @var array
     */
    protected $availableIncludes = [
        'doctor', 'user',
    ];

    /**
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'doctor_id' => $model->doctor_id,
            'user_id' => $model->user_id,
            'clinic_id' => $model->clinic_id, //最后一次诊疗的编号ID
            'title' => $model->title, //title
            'last_message' => $model->last_message, //最后消息
            'user_new_num' => $model->user_new_num, //user_new_num
            'doctor_new_num' => $model->doctor_new_num, //doctor_new_num
            'status' => $model->status, //列表状态 0:不能聊天 1:能聊天
            'updated_at' => $model->updated_at->timestamp, //列表时间
        ];
    }

    /**
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeDoctor($model)
    {
        $doctor = $model->doctor;

        if ($doctor)
            return $this->item($doctor, new DoctorTransformer());
    }

    /**
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser($model)
    {
        $user = $model->user;

        if ($user)
            return $this->item($user, new UserTransformer());
    }
}