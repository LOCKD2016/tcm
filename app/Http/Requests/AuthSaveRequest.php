<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
class AuthSaveRequest extends FormRequest
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
     *  @guhao
     * @return array
     */
    public function rules()
    {
        return [
            'pid'=>'required|integer',
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required'
        ];
    }

    public function messages()
    {
        return [
            "pid.required"=>"请选择权限等级",
            "pid.integer"=>"请选择权限等级",
            "name.required"=>"权限名称不能为空",
            "display_name.required"=>"展示称不能为空",
            "description.required"=>"描述不能为空",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException("添加失败", $validator->errors());
    }
}
