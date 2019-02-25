<?php

namespace App\Transformers;
use App\Repository\MessageRepository;
use Carbon\Carbon;

/**
 * Class BespeakTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class BespeakTransformer extends BaseTransformer
{

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'user', 'doctor', 'clinic',
        'inquiry', 'order', 'clinique', 'message'
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
            'clinic_id' => $model->clinic_id,
            'order_id' => $model->order_id,
            'disease' => is_array(json_decode($model->disease, true)) ? json_decode($model->disease, true) : $model->disease,
            'type' => $model->type,//预约类型
            'first' => $model->first,//是否是初诊
            'redundant_first' => $model->redundant_first,//是否是初诊
            'start_time' => $model->start_time,//开始时间
            'end_time' => $model->end_time,//
            'remark' => $model->remark,//客服备注
            'bespeak_code' => $model->bespeak_code,//其他系统需要
            'status' => $model->status,//预约状态
            'time_diff_hours' => Carbon::parse($model->start_time)->diffInMinutes()/60
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
     * 医生
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeDoctor($model)
    {
        $doctor = $model->doctor;

        if (!empty($doctor))

            return $this->item($doctor, new DoctorTransformer());
    }

    /**
     * 诊疗
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeClinic($model)
    {
        $clinic = $model->clinic;

        if (!empty($clinic))

            return $this->item($clinic, new ClinicTransformer());
    }

    /**
     * 订单
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeOrder($model)
    {
        $order = $model->order;

        if (!empty($order))

            return $this->item($order, new OrderTransformer());
    }

    /**
     * 问诊单
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeInquiry($model)
    {
        $inquiry = $model->inquiry;

        if (!empty($inquiry))

            return $this->item($inquiry, new InquiryTransformer());
    }

    /**
     * 诊所
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeClinique($model)
    {
        $clinique = $model->clinique;

        if (!empty($clinique))

            return $this->item($clinique, new CliniqueTransformer());
    }

    /**
     * 消息listid
     * @author Eric
     * return array
     */
    public function includeMessage($model)
    {
        $message = (new MessageRepository())->get_lists_by_user_and_doctor($model->user_id, $model->doctor_id);
        if(!empty($message))
             return $this->item($message, new MessageListTransFormer());
    }
}