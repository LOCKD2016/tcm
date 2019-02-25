<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Dingo\Api\Exception\StoreResourceFailedException;

/**
 * 评论诊疗
 * Class CommentSaveRequest
 * @package App\Http\Requests
 */
class CommentSaveRequest extends FormRequest
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
            'condition' => 'required|in:1,2,3,4,5', //病情  1:痊愈: 明显好转 3：好转 4：没变化 等等
            'manner' => 'required|in:1,2,3,4,5',//态度 对应的几颗星
            //'effect' => 'required|in:1,2,3,4,5',//疗效 对应的几颗星
            ///'content' => 'required',//内容
        ];
    }

    /**
     * @Auth: kingofzihua
     * @return array
     */
    public function messages()
    {
        return [
            'condition.required' => '病情状况不能为空',
            'condition.in' => '所选病情状况不正确',
            'manner.required' => '态度不能为空',
            'manner.in' => '所选态度不正确',
            //'effect.required' => '疗效不能为空',
            //'effect.in' => '所选疗效不正确',
            //'content.required' => '评价内容不许为空',
        ];
    }

    /**
     * @Auth: kingofzihua
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new StoreResourceFailedException('评论失败', $validator->errors());
    }
}
