<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
use Illuminate\Validation\Rule;


/**
 * Class DoctorUpdateRequest
 * @package App\Http\Requests
 */
class DoctorUpdateRequest extends FormRequest
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
            'name' => 'min:2',     //医师姓名
            'code' => 'min:3',     //医师资格证编码
            'desc' => 'min:3',//门诊信息
            'expert' => 'json', //擅长 疾病 json串形式
            'sections' => 'json', //科室 json串形式
            'intro' => 'min:3', //医生介绍
            'address' => 'min:3',//家庭住址
            'profession_auth' => 'json', //职业证书
            'qualification_auth' => 'json', //资格证书
            'major_qualification_auth' => 'json', //专业技术资格证书
            'sex' => 'in:0,1', //性别,0男 1 女
            'birthday' => 'date', //出生日期 2013-03-21
            'nation' => 'min:2', //国籍
            'native' => 'min:2', //籍贯
            'idType' => 'in:0,1,2,3,4,9',//证件类型; 0:身份证;1:军官证;2:护照;3:台胞证;4:其他;9:无证件
            'idNo' => 'min:3', //证件号码
            'title' => 'min:2', //头衔
            'photoSUrl' => 'min:3', //大头像地址
            'photoLUrl' => 'min:3', //小头像地址
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'name.min' => '医师姓名不符合',     //医师姓名
            'code.min' => '医师资格证编码不合法',     //医师资格证编码
            'clinic_intro.min' => '门诊信息长度不能小于3个字符',//门诊信息
            'expert.json' => '擅长:所输入的类型不正确', //擅长 json串形式
            'intro.min' => '医生介绍 不能小于3个字符', //医生介绍
            'address.min' => '家庭住址不能小于三个字符',//家庭住址
            'profession_auth.json' => '职业证书类型不合法', //职业证书
            'qualification_auth.json' => '资格证书类型不合法', //资格证书
            'major_qualification_auth.json' => '专业技术资格证书类型不合法', //专业技术资格证书
            'gender.in' => '所属性别不合法', //性别,0男 1 女
            'birthday.date' => '出生日期不合法', //出生日期 2013-03-21
            'nation.min' => '请输入正确的国籍', //国籍
            'native.min' => '请输入正确的籍贯', //籍贯
            'idType.in' => '证件类型 不合法',//证件类型; 0:身份证;1:军官证;2:护照;3:台胞证;4:其他;9:无证件
            'idNo.min' => '证件号码过短', //证件号码
            'title.min' => '请输入正确的头衔', //头衔
            'briefIntro.min' => '医生简介不能小于3个字符', //医生简介
            'photoLUrl.min' => '请输入正确的大头像地址', //大头像地址
            'photoSUrl.min' => '请输入正确的头像地址', //小头像地址
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
