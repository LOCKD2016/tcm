<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 注册的验证
 * Class LoginRequest
 * @package App\Http\Requests
 */
class UserRegisterRequest extends FormRequest
{
    /**
     * UserRegisterRequest constructor.
     */
    public function __construct()
    {
    }

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
            'type' => 'bail|required|in:plain,quick,debug', //plain:普通  quick:快速登录
            'password' => 'bail|required|min:6|max:18',
        ];

        /*
         * 这个地方看不懂的话注意下
         * @desc: 快速登录的注册走的是登录的验证
         *  因为 登录的注册和普通的注册放到一起了，为了兼容下
         */
        if ($this->type == 'plain') { //普通注册
            $rule['code'] = 'bail|required|smscode:1,1';
        } elseif ($this->type == 'quick') { //快速登录的注册
            $rule['code'] = 'bail|required|smscode:3,1';
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
            'type.in' => '类型为必选项',
            'password.required' => '密码不许为空',
            'password.min' => '密码长度不能小于6位',
            'password.max' => '密码长度不能大于18位',
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
        throw new StoreResourceFailedException('操作失败', $validator->errors());
    }
}
