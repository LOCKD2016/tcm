<?php

namespace App\Repository;

use App\Models\Config;

/**
 * Class ConfigRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class ConfigRepository extends Repository
{

    /**
     * 通过key 获取数据
     * @param $key
     * @return mixed
     */
    public function get_data_by_key($key)
    {
        return $this->model->QueryKey($key)->first();
    }

    /**
     * 获取医生职称
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_doctor_title()
    {
        return Config::getTitle();
    }

    /**
     * @Auth: kingofzihua
     * @return Config
     */
    public function model()
    {
        return new Config();
    }

    /**
     * 查询医生的头衔，没有的话增加
     * @param $searchTitle
     * @return array|mixed
     */
    public function get_title_id_by_name_or_save($searchTitle)
    {
        $title = Config::where('key', 'doctor_title')->first();

        $titleArray = \GuzzleHttp\json_decode($title->value, true);

        $name = collect($titleArray)->pluck('name')->toArray();

        if (!in_array($searchTitle, $name)) {
            $createData = [
                'id' => count($titleArray) + 1,
                'name' => $searchTitle,
            ];

            array_push($titleArray, $createData);

            $title->value = \GuzzleHttp\json_encode($titleArray);

            if ($title->save($title->toArray())) {
                return $createData;
            }
            return false;
        }

        return collect($titleArray)->where('name', $searchTitle)->first();
    }
}