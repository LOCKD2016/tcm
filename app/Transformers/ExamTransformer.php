<?php

namespace App\Transformers;

use App\Models\AppUser;

/**
 * Class ExamTransformer
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class ExamTransformer extends BaseTransformer
{
    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $availableIncludes = [
        'option', 'answers'
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
            'type' => $model->type,
            'title' => $model->title,
        ];
    }

    /**
     * 获取选项
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeOption($model)
    {
        $options = $model->options;

        //获取所有答案
        $answers = $model->answers;

        if (count($answers)) {//有答案
            //遍历到试题中
            $options->transform(function ($value, $key) use ($answers) {
                //获取答案 并转化为数组
                $answer = $answers->where('question_id', $value->id)->pluck('answer')->toArray();

                if (!empty($answer)) {
                    //json 转化下
                    $json_de_answer = json_decode($answer[0], true);

                    //判断 是否是数组，如果是就返回数组 如果不是就返回原来的
                    if (is_array($json_de_answer)) {
                        $value->answers = $json_de_answer;
                    } else {
                        $value->answers = $answer[0] ?? '';
                    }
                }
                return $value;
            });
        }

        if (count($options))

            return $this->collection($options, new ExamOptionTransformer());
    }

    /**
     * 获取答案
     * @desc 如果登录用户是 微信端
     * @Auth: kingofzihua
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAnswers($model)
    {
        $answers = $model->answers;

        if (isset($answers) && count($answers))

            return $this->collection($answers, new ExamAnswerTransformer());
    }

}