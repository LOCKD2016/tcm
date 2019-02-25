<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 医生忘记密码
 * Class DoctorRegisterRequest
 * @package App\Http\Requests
 */
class DoctorForgetPasswordRequest extends FormRequest
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
            'mobile' => 'bail|required|regex:/^1[34578][0-9]{9}$/|exists:doctors',
            'password' => 'bail|required|min:6|confirmed',
            'code' => 'bail|required|smscode:2,1',
        ];
    }

    /**
     * 错误消息
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required' => '手机号不许为空',
            'mobile.exists' => '手机号尚未注册',
            'mobile.regex' => '手机号格式不正确',
            'password.required' => '密码不许为空',
            'password.min' => '密码不能小于6位',
            'password.confirmed' => '两次输入密码不一致',
            'code.required' => '验证码必须',
            'code.smscode' => '验证码错误',
        ];
    }


    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('找回失败', $validator->errors());
    }
}
