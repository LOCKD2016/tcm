<?php
namespace App\Repository;

use App\Models\Medicine;

/**
 * Class SectionRepository
 * @Auth: Nnn
 * @package App\Repository
 */
class MedicineRepository extends Repository
{

    /**
     * @Auth: Nnn
     * @return Medicine
     */
    public function model()
    {
        // TODO: Implement model() method.
        return new Medicine();
    }

    /**
     * 通过项目编号获取项目数据
     * @param $code
     * @return mixed
     */
    public function get_medicine_by_code($code)
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * 根据名称搜索
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/1 17:14
     * @param $name
     */
    public function get_medicine_by_name($name)
    {
        return $this->model->where('name', $name)->first();
    }

    /**
     * 编辑项目信息
     * @param $data
     * @return mixed
     */
    public function update_data($data)
    {
        return $this->model->where('code', $data['code'])->update($data);
    }

    /**
     * 删除项目信息
     * @param $code
     */
    public function delete_data($code)
    {
        return $this->model->where('code', $code)->delete();
    }


}