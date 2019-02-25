<?php

namespace App\Repository;

use App\Models\Group;

/**
 * Class GroupRepository
 * @package App\Repository
 */
class GroupRepository extends Repository
{
    /**
     * 通过
     * @param $all
     * @param $now
     * @return array
     */
    public function get_non_existent_data($all, $now)
    {
        return collect($all)->diff($now)->all();
    }

    /**
     * 当前编号的数组 是不是代表着 编号
     * @param $ids
     * @return mixed
     */
    public function get_group_data_in_id($ids)
    {
        $ids = $this->model->whereIn('id', $ids)->pluck('id');

        return count($ids) ? $ids->toArray() : [];
    }

    /**
     * 通过name 数组获取 编号
     * @param $name
     * @return array
     */
    public function get_group_data_in_name($name)
    {
        $ids = $this->model->whereIn('name', $name)->pluck('id');

        return count($ids) ? $ids->toArray() : [];
    }

    /**
     * 通过名字获取
     * @param $name
     * @return mixed
     */
    public function get_data_by_name($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * @param $group_id
     * @return mixed
     */
    public function group_user_list_by_id($group_id)
    {
        return $this->get_data_by_id($group_id)->users()->paginate($this->page);
    }

    /**
     * @return Group
     */
    public function model()
    {
        return new Group();
    }
}