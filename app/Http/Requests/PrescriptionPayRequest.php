<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

class PrescriptionPayRequest extends FormRequest
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
        $rules = [
            'tisane' => 'required|in:0,1',//代煎
            'express' => 'required|in:0,1',//快递
        ];

        if ($this->express) { //如果 选择了快递 ，则 地址编号为必须
            $rules['address_id'] = 'required|exists:address,id';
        }

        return $rules;
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'tisane.required' => '请选择是否代煎',
            'tisane.in' => '所选代煎类型不正确',
            'express.required' => '请选择是否快递',
            'express.in' => '所选快递类型不正确',
            'address_id.required' => '请选择地址',
            'address_id.exists' => '所选地址不存在',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('订单创建失败', $validator->errors());
    }
}
