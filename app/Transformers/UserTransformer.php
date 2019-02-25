<?php

namespace App\Transformers;

use App\Models\Doctor;

/**
 * Class UserTransformers
 * @Auth: kingofzihua
 * @package App\Transformers
 */
class UserTransformer extends BaseTransformer
{

    /**
     * 可选包含项
     * @var array
     */
    protected $availableIncludes = [
        'groups'
    ];

    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @Auth: kingofzihua
     * @param $model
     * @return array
     */
    public function transformData($model)
    {
        return [
            'id' => $model->id,
            'mobile' => $model->mobile,//手机
            'nickname' => $model->nickname,//昵称
            'realname' => $model->realname,//真实姓名
            'sex' => $model->sex,//性别 0未知 1男 2女
            'headimgurl' => $model->headimgurl,//头像地址
            'birthday' => $model->birthday, //生日
            'age' => $model->age, //
            'height' => $model->height,//身高
            'weight' => $model->weight,//体重
            'pincode' => $model->pincode,//身份证号
            'country' => $model->country,//国家
            'province' => $model->province,//省份
            'city' => $model->city,//城市
            'area' => $model->area,//区县
            'im_token' => $model->im_token,//聊天token
            'customer_code' => $model->customer_code, //客户代码
            'status' => $model->status,//账号状态 0 正常 1 待定 保留字段
            'idNo' => $model->idNo, //身份证号
            'pivot' => $model->pivot,
        ];
    }

    /**
     * 获取用户分组
     * @param $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeGroups($model)
    {
        //当前是医生端
        if (\Auth::user() instanceof Doctor) {
            //获取用户在当前医生的分组
            $groups = \Auth::user()->selectGroupByUserId($model->id);

            //如果有group_id 则认为是 判断当前用户是否在 指定的分组内
            $group_id = request('group_id');
            if (request('group_id')) {
                //如果在分组内 就返回 不然全部剔除
                $groups = $groups->filter(function ($value, $key) use ($group_id) {
                    return $value->id == $group_id;
                });
            }

        } else {
            $groups = $model->groups;
        }

        return $this->collection($groups, new GroupTransformer);
    }

}