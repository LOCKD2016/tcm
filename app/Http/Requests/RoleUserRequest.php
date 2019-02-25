<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;
class RoleUserRequest extends FormRequest
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
        $id = $this->route('id');
        $pid = $this->route('pid');

        //添加用户验证
        if(empty($id)){
            $rules['user_name'] = ['required', 'unique:user,user_name'];
            $rules['user_email'] = ['unique:user,user_email'];
            $rules['user_phone'] = ['digits:11'];
            $rules['user_realname'] = ['required'];
            $rules['user_password'] = ['required', 'min:6','alpha_num','confirmed'];
            $rules['user_password_confirmation'] = ['required', 'min:6','alpha_num'];
        }else{
            //修改用户信息验证规则
            $rules['user_name'] = ['required', 'unique:user,user_name,'.$id.',user_id'];
            $rules['user_email'] = ['unique:user,user_email,unique:user,user_id,'.$id];
            $rules['user_phone'] = ['digits:11'];
            $rules['user_realname'] = ['required'];
        }
        //判断修改密码的验证规则
        if(!empty($pid)){
            $rules = array();
            $rules['user_newpassword'] = ['required', 'min:6','alpha_num','confirmed'];
            $rules['user_newpassword_confirmation'] = ['required', 'min:6','alpha_num'];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            "user_name.required"=>"用户的名称不能为空",
            "user_realname.required"=>"真实姓名不能为空",
            "user_name.max"=>"用户名称不能超过50位",
            "user_name.unique"=>"用户名称不能重复",
            "user_email.email"=>"请填写正确邮箱格式",
            "user_email.unique"=>"该邮箱已经注册",
            "user_phone.digits"=>"请填写正确的手机号码格式",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException("修改失败", $validator->errors());
    }
}
