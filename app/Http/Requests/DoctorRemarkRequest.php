<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 医生重置密码
 * Class DoctorRegisterRequest
 * @package App\Http\Requests
 */
class DoctorRemarkRequest extends FormRequest
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
            'content' => 'required|min:3',
        ];
    }

    /**
     * 错误消息
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => '医嘱标题不能为空',
            'content.required' => '医嘱内容不能为空',
            'title.min' => '医嘱标题不能小于3个字符',
            'content.min' => '医嘱内容不能小于3个字符',

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
