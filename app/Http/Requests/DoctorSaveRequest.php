<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Validation\Rule;

/**
 * 添加医生的接口
 * Class DoctorSaveRequest
 * @Auth: kingofzihua
 * @package App\Http\Requests
 */
class DoctorSaveRequest extends FormRequest
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
            'name' => 'bail|required',
            'mobile' => 'bail|required|unique:doctors',//手机号
            'sex' => ['required', Rule::in([0, 1]),],//性别
            'idType' => ['required', Rule::in([0, 1, 2, 3, 4, 9])],
            'idNo' => 'bail|required',//证件号码
            'birthday' => 'bail|required|date',//出生日期
            'customer_code' => 'bail|required',//客户代码 其他系统需要

        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '医生姓名不能为空',
            'mobile.required' => '医生手机号不能为空',
            'mobile.unique' => '医生手机号重复',
            'sex.required' => '医生性别不能为空',
            'sex.in' => '医生性别不合法',
            'idType.required' => '医生证件类型不能为空',
            'idNo.required' => '医生证件号码不能为空',
            'birthday.required' => '出生日期不能为空',
            'birthday.date' => '出生日期类型不正确',
            'customer_code.required' => '客户代码不能为空',
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
