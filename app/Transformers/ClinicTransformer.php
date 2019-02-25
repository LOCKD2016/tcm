<?php

namespace App\Transformers;

/**
 * Class ClinicTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class ClinicTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'user', 'bespeak', 'inquiry', 'exam', 'comments', 'prescription', 'doctor'
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
            'disease' => is_array(json_decode($model->disease, true)) ? json_decode($model->disease, true) : $model->disease,
            'comment' => (int)$model->comment,
            'content' => $model->content,
            'type' => $model->type,
            'first' => $model->first, //0:初诊 1:复诊
            'end_time' => $model->end_time, //结束问诊时间
            'status' => $model->status, //诊疗状态
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
     * 预约
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeBespeak($model)
    {
        $bespeak = $model->bespeak;

        if (!empty($bespeak))

            return $this->item($bespeak, new BespeakTransformer());
    }

    /**
     * 标准问诊单
     * @param $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeInquiry($model)
    {
        $inquiry = $model->inquiry;

        if ($inquiry)

            return $this->item($inquiry, new InquiryTransformer());
    }

    /**
     * 个性化问诊单
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeExam($model)
    {
        $exams = $model->exams()->limit(3)->get();

        if (count($exams))
            //$json_de_answer = json_decode($exams[0], true);
            return $this->collection($exams, new ExamAnswerTransformer());
    }

    /**
     * 诊疗评价
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeComments($model)
    {
        $comments = $model->comments;

        if (count($comments)) {
            return $this->collection($comments, new CommentTransformer());
        }
    }

    /**
     * 处方信息
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePrescription($model)
    {
        $prescription = $model->prescription;

        if (count($prescription)) {

            return $this->item($prescription, new PrescriptionTransformer());
        }

    }

    public function includeDoctor($model)
    {
        $doctor = $model->doctor;

        if(count($doctor)){
            return $this->item($doctor, new DoctorTransformer());
        }
    }

}