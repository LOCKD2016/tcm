<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 医生休息
 * Class DoctorRegisterRequest
 * @package App\Http\Requests
 */
class DoctorRestRequest extends FormRequest
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
            'start_time' => 'required|date',
            'end_time' => 'required|date',
            'day' => 'required|int',
        ];
    }

    /**
     * 错误消息
     * @return array
     */
    public function messages()
    {
        return [
            'start_time.required' => '开始时间不许为空',
            'start_time.date' => '结束时间不许为空',
            'end_time.required' => '开始时间格式不正确',
            'end_time.date' => '结束时间格式不正确',
            'day.required' => '时间间隔不许为空',
            'day.int' => '时间间隔格式不正确',
        ];
    }


    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('申请失败', $validator->errors());
    }
}
