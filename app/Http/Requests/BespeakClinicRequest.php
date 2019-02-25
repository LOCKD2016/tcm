<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

class BespeakClinicRequest extends FormRequest
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
            'doctor_id' => 'required|int',//医生编号
            'date' => 'required|date|after:now',
            'time' => 'required|date_format:H:i',
            'clinique_id' => 'required|int',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'doctor_id.required' => '请选择所预约的医生',
            'date.required' => '请选择所预约的日期',
            'date.after' => '所预约的日期必须大于今天',
            'date.date' => '所预约的日期不合法',
            'time.required' => '请选择所预约的时间',
            'time.date_format' => '所预约的时间不合法',
            'clinique_id.required' => '请选择所预约的诊所',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('预约失败', $validator->errors());
    }
}
