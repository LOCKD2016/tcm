<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

class BespeakWebRequest extends FormRequest
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
            'redundant_first' => 'required', //是否是初诊 1:初诊 2:复诊 页面中需要记录 虽然没什么卵用
            'redundant_in' => 'required',//是否是泰和国医的用户 虽然也没啥卵用 但是页面需要
            'doctor_id' => 'required',//医生编号
            //'disease' => 'required', // 疾病名
            'desc' => 'required', // 疾病描述
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
            'redundant_first.required' => '请选择是否是泰和国医患者',
            'redundant_in.required' => '请选择诊断类型',
            //'disease.required' => '疾病名称不能为空',
            'desc.required' => '疾病描述不能为空',
            'doctor_id.required' => '请选择所预约的医生',
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
