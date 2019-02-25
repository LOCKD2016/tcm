<?php

namespace App\Transformers;

/**
 * Class ExamOptionTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class ExamOptionTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'type' => $model->type,
            'option' => $model->option,
            'must' => $model->must,
            'sort' => $model->sort,
            'answers' => $model->answers, //用户所答题的答案
        ];
    }
}