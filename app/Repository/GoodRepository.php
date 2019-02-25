<?php

namespace App\Repository;

use App\Models\Goods;

/**
 * Class GoodRepository
 * @package App\Repository
 */
class GoodRepository extends Repository
{

    /**
     * 通过商品类型获取商品的信息
     * @param $type
     * @return mixed
     */
    public function get_good_data_by_type($type)
    {
        return $this->model->where('type', Goods::GOOD_TYPE[$type])->where('status', '1')->first();
    }

    /**
     * @return Goods
     */
    public function model()
    {
        return new Goods();
    }

}