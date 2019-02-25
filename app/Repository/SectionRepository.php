<?php
namespace App\Repository;

use App\Models\Section;

/**
 * Class SectionRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class SectionRepository extends Repository
{
    /**
     * 获取可以显示的数据
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_show_data()
    {
        return $this->model->where(['status' => '1'])->orderBy('id', 'asc')->get();//->orderBy('sort', 'desc')
    }

    /**
     * @Auth: kingofzihua
     * @return Section
     */
    public function model()
    {
        // TODO: Implement model() method.
        return new Section();
    }

}