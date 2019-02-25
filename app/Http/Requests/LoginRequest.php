<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 登录的验证
 * Class LoginRequest
 * @package App\Http\Requests
 */
class LoginRequest extends FormRequest
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
        $rule = [
            'mobile' => 'bail|required|regex:/^1[34578][0-9]{9}$/',
            'type' => 'bail|required|in:plain,quick,debug', //plain:普通  quick:快速
        ];

        if ($this->type == 'plain') { //普通登录
            $rule['password'] = 'bail|required';

        } elseif ($this->type == 'quick') { //快速登录
            $rule['code'] = 'bail|required|smscode:3,1';

        } else { //其他登录方式 应该是错误 必须包含 kingofzihua 不然报错 其实主要是为了报错
            $rule['error'] = 'bail|filled:kingofzihua';
        }

        return $rule;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号码格式不正确',
            'type.required' => '登录类型为必选项',
            'type.in' => '登录类型不正确',
            'password.required' => '登录密码不许为空',
            'code.required' => '验证码不许为空',
            'code.smscode' => '验证码错误',
            'error.filled' => '( ⊙ o ⊙ ) debug模式登录失败啊！',
        ];

    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('登录失败', $validator->errors());
    }
}
