<?php

namespace App\Repository;

use App\Models\AppUser;
use App\Models\UserWeixin;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Class UserRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class UserRepository extends Repository
{

    /**
     * 创建新的用户
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data['salt'] = Str::random(8);
        $data['password'] = bcrypt($data['password'] . $data['salt']);
        return parent::create($data);
    }

    /**
     * user_weixin 中数据存一下
     * @param $data
     * @return static
     */
    public function wechat_create($data)
    {
        //过滤掉不需要的数据
        $keys = ['unionid','user_id', 'openid', 'nickname', 'avatar', 'province', 'city', 'country', 'sex'];

        $results = [];

        foreach ($keys as $key) {
            Arr::set($results, $key, data_get($data, $key));
        }

        return UserWeixin::create($results);
    }

    /**
     * user_weixin 中数据存一下
     * @param $data
     * @return static
     */
    public function wechat_app_create($data)
    {
        //过滤掉不需要的数据
        $keys = ['unionid','user_id', 'nickname', 'avatar', 'province', 'city', 'country', 'sex'];

        $results = [];

        foreach ($keys as $key) {
            Arr::set($results, $key, data_get($data, $key));
        }

        return UserWeixin::create($results);
    }

    /**
     * 通过手机号获取用户
     * @param $mobile
     * @return mixed
     */
    public function get_data_by_mobile($mobile)
    {
        return $this->model->queryMobile($mobile)->first();
    }

    /**
     * 通过用户的编号获取微信openid
     * @param $user_id
     * @return mixed
     */
    public function get_user_wechat_openid($user_id)
    {
        return UserWeixin::where('user_id', $user_id)->value('openid');
    }

    /**
     * 通过用户的编号获取微信信息
     * @param $user_id
     * @return mixed
     */
    public function get_user_wechat_byuid($user_id)
    {
        return UserWeixin::where('user_id', $user_id)->first();
    }

    /**
     * 获取登录用户的医生列表
     * @return mixed
     */
    public function get_auth_doctor_lists()
    {
        return $this->get_auth_data()->follow()->paginate($this->page);
    }

    /**
     * @Auth: kingofzihua
     * @return \App\Models\User|null
     */
    public function get_auth_data()
    {
        return \Auth::user();
    }

    /**
     * @Auth: kingofzihua
     * @return AppUser
     */
    function model()
    {
        // TODO: Implement model() method.
        return new AppUser();
    }

    /***********************************************************************泰和国医接口********************************************************************************/

    /**
     * 多条件获取患者信息
     * @param $search
     */
    public function get_platform_user_list($search)
    {
        return $this->model
            ->name($search['name'] ?? '')//用户姓名
            ->mobile($search['mobile'] ?? '')//用户手机号
            ->idNo($search['idNo'] ?? '')//用户身份证
            ->customerCode($search['customer_code'] ?? '')//泰和国医用户标识
            ->paginate($this->page);
    }

    /**
     * 根据customer_code查询患者信息
     * @param $id
     * @return mixed
     */
    public function get_user_by_customer_code($id)
    {
        return $this->model->where('customer_code', $id)->first();
    }

    /**
     * 根据customer_code删除患者信息
     * @param $id
     * @return mixed
     */
    public function soft_delete_data($id)
    {
        return $this->model->where('customer_code', $id)->delete();
    }

    /**
     * 修改患者信息
     * @param $customer_code
     * @param $data
     * @return mixed
     */
    public function update_user_data($customer_code, $data)
    {
        return $this->model->where('customer_code', $customer_code)->update($data);
    }

    /**
     * 获取详情
     * @desc
     * @author Eric Chow
     * @DateTime 2018/2/1 12:16
     * @param $id
     */
    public function get_detail_by_id($id)
    {
        return $this->model()->find($id);
    }

}