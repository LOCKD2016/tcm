<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;


class ExamAnswerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'exam_id' => 'required|exists:exam,id',
            'message_list_id' => 'required|exists:message_lists,id',
            'option' => 'required|array',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'exam_id.required' => '所选问诊单不许为空',
            'exam_id.exists' => '所选问诊单不存在',
            'message_list_id.required' => '您还没有进行诊疗',
            'message_list_id.exists' => '诊疗不存在',
            'option.required' => '所回答试题不许为空',
            'option.json' => '所回答试题类型错误',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('问诊单填写失败', $validator->errors());
    }
}
