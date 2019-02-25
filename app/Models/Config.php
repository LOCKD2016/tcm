<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 配置
 * Class Config
 * @Auth: kingofzihua
 * @package App\Models
 */
class Config extends Model
{
    /**
     * @var string
     */
    protected $table = 'configs';


    /**
     * 根据key 查询信息
     * @param $query
     * @param $key
     * @return string
     */
    public function scopeQueryKey($query, $key)
    {
        return $key ? $query->where('key', $key) : '';
    }

    /**
     * @param $id
     * @return string
     */
    protected function getTitle($id = null)
    {
        //放到缓存里面 不然 sql 实在太多了
        $data = \Cache::get('doctor_title', function () {
            $doctor_title = self::where(['key' => 'doctor_title'])->first();
            \Cache::add('doctor_title', $doctor_title, 1);
            return $doctor_title;
        });

        if ($data) {
            $arr = \GuzzleHttp\json_decode($data->value, true);

            foreach ($arr as $item) {
                if ($item['id'] == $id) {
                    return ($item['name']);
                }
            }
        }

        return $data ? $arr : '';
    }
}
