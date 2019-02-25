<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
class RolesRequest extends FormRequest
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
            'display_name' => 'required',
            'name' => 'required',
            'description' => 'required|max:100',
        ];
    }

    public function messages()
    {
        return [
            "display_name.required"=>"用户组标识不能为空",
            "name.required"=>"用户组名称不能为空",
            "dictionary.required"=>"所属分类不能为空",
            "dictionary.max"=>"所属分类不能超出50位",
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException("修改失败", $validator->errors());
    }

}
