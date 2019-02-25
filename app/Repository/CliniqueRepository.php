<?php
namespace App\Repository;

use App\Models\Clinique;

/**
 * Class CliniqueRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class CliniqueRepository extends Repository
{

    /**
     * 获取第一条数据
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_first_data()
    {
        return $this->model->orderBy('id', 'asc')->first();
    }

    /**
     * 根据code码查询门店信息
     * @Auth: kingofzihua
     * @param $code
     * @return mixed
     */
    public function get_data_by_code($code)
    {
        try {
            return $this->model->queryCode($code)->first();
        } catch (\ErrorException $exception) {
            return null;
        }
    }

    /**
     * 根据code码查询门店信息，如果没有就会创建
     * @Auth: kingofzihua
     * @param $code
     * @return mixed
     */
    public function get_data_by_code_with_create($code)
    {
        $clinique = $this->get_data_by_code($code);

        return $clinique ?: $this->create(['code' => $code,]);
    }

    /**
     * 根据id获取信息
     * @desc
     * @author Eric
     * @DateTime 2018/3/7 10:45
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|void
     */
    public function get_data_by_id($id)
    {
        return $this->model()->find($id);
    }

    /**
     * @Auth: kingofzihua
     * @return Clinique
     */
    public function model()
    {
        return new Clinique();
    }

}