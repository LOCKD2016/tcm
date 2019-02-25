<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 发送验证码
 * Class LoginRequest
 * @package App\Http\Requests
 */
class SMSCodeRequest extends FormRequest
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
            'mobile' => 'bail|required|regex:/^1[345678][0-9]{9}$/',
            'type' => 'bail|required|in:1,2,3,4',

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号码格式不正确',
            'type.required' => '验证码类型为必选项',
            'type.in' => '验证码类型不正确',
        ];

    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('发送失败', $validator->errors());
    }
}
