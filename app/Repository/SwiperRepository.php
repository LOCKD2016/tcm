<?php
namespace App\Repository;

use App\Models\Swiper;

/**
 * Class SwiperRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class SwiperRepository extends Repository
{
    /**
     * 获取显示数据
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_show_data()
    {
        return $this->model->where(['status' => '1'])->get();
    }

    /**
     * @Auth: kingofzihua
     * @return Swiper
     */
    public function model()
    {
        return new Swiper();
    }

}