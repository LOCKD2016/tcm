<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Smscode
 * @package App\Models
 */
class Smscode extends Model
{
    protected $guarded = [];

    /**
     * 添加短信记录
     * @param $data
     * @return bool
     */
    public function add($data)
    {
        unset($data['timestamp']);
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
        return $this->save();
    }
}
