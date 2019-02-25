<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;


class ExamSaveRequest extends FormRequest
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
            'title' => 'required',
            'type' => 'required|in:1,2,3',
            'option' => 'required|json',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => '问诊单标题不许为空',
            'type.required' => '问诊单类型不许为空',
            'option.required' => '问诊单题目不许为空',
            'option.json' => '问诊单题目类型不正确',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('问诊单创建失败', $validator->errors());
    }
}
