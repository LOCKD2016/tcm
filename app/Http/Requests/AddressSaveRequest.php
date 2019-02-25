<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 用户添加地址
 * Class AddressSaveRequest
 * @package App\Http\Requests
 */
class AddressSaveRequest extends FormRequest
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
            'user_name' => 'required|min:1|max:25', //姓名
            'mobile' => 'bail|required|regex:/^1[34578][0-9]{9}$/',
            'province' => 'required',//省份
            'city' => 'required',//城市
            'district' => 'min:2|max:10',//区县
            'address' => 'required',//详细地址
            'is_default' => 'required|in:0,1',//是否是默认
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'user_name.required' => '姓名不许为空',
            'user_name.min' => '姓名不能少于1位字符',
            'user_name.max' => '姓名不能大于25位字符',
            'mobile.required' => '手机号不能为空',
            'mobile.regex' => '手机号码格式不正确',
            'province.required' => '省份不能为空',
            'city.required' => '省份不能为空',
            'district.min' => '区县不能少于1位字符',
            'district.max' => '区县不能大于25位字符',
            'address.required' => '详细地址不能为空',
            'is_default.required' => '默认为必选项',
            'is_default.in' => '默认类型不正确',
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
