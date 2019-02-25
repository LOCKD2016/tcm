<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

class GroupSaveRequest extends FormRequest
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
            'name' => 'required|min:3|max:25',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '分组名称不许为空',
            'name.min' => '分组名称不能少于3位字符',
            'name.max' => '分组名称不能大于25位字符',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('分组创建失败', $validator->errors());
    }
}
