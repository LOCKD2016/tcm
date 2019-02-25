<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @package App\Models
 */
class Medicine extends Model
{
    /**
     * @var string
     */
    protected $table = "medicine";

    /**
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * 根据药品名称查询药品
     * @param $query
     * @param $name
     */
    public function scopeMedicineName($query, $name)
    {
        return $name ? $query->where('name', 'like', '%'.$name.'%') : '';
    }

    /**
     * 药品修改
     * @param $data
     * @return mixed
     */
    public function update_medicine_data($data)
    {
        return Medicine::where('id', $data['id'])->update($data);
    }

}
