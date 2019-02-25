<?php

namespace App\Transformers;

/**
 * Class ExamAnswerTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class ExamAnswerTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'option'
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
            'user_id' => $model->user_id,
            'clinic_id' => $model->clinic_id,
            'exam_id' => $model->exam_id,
            'question_id' => $model->question_id, //问题的编号
            'question' => $model->question,//问题的标题
            //'answer' => is_array($model->answer) ? json_decode($model->answer,true) : $model->answer, //问题的答案
            'answer' => is_array(json_decode($model->answer, true)) ? json_decode($model->answer, true) : $model->answer, //问题的答案
            'time' => $model->time,//时间
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeOption($model)
    {
        $option = $model->option;

        if (count($option))

            return $this->item($option, new ExamOptionTransformer());
    }
}