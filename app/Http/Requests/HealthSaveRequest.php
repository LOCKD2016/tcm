<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Validation\Rule;


class HealthSaveRequest extends FormRequest
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
            'content' => 'required|json',
            'type' => ['required', Rule::in([1, 2, 3, 4, 5])],
            'date' => 'date',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'date.date' => '日期不合法',
            'content.required' => '数据不许为空',
            'content.json' => '填写内容不正确',
            'type.required' => '填写类型为必选项',
            'type.Rule' => '填写类型不正确',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('添加失败', $validator->errors());
    }
}
