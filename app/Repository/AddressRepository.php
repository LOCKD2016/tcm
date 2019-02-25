<?php

namespace App\Repository;

use App\Models\Areas;
use App\Models\Address;

/**
 * Class AddressRepository
 * @package App\Repository
 */
class AddressRepository extends Repository
{
    /**
     * 查询用户所有的地址
     * @param $user_id
     * @return \Illuminate\Support\Collection
     */
    public function get_user_data_list($user_id)
    {
        return $this->model->queryUser($user_id)->get();
    }

    /**
     * 设置用户默认的地址
     * @param $user_id
     * @param $address_id
     * @return mixed
     */
    public function set_user_default_address($user_id, $address_id)
    {
        //取消所有的 默认
        $this->model->queryUser($user_id)->update(['is_default' => 0]);

        //设为默认
        return $this->model->queryUser($user_id)->queryId($address_id)->update(['is_default' => 1]);
    }

    /**
     * 获取用户的默认地址
     * @param $user_id
     * @return mixed
     */
    public function get_user_default_data($user_id)
    {
        return $this->model->queryUser($user_id)->queryDefault()->first();
    }

    /**
     * 通过城市获取价格
     * @param $province
     * @return mixed
     */
    public function get_amount_by_province($province)
    {
        return Areas::where('name', 'like', '%' . $province . '%')->first();
    }

    /**
     * @return Address
     */
    public function model()
    {
        return new Address();
    }

}