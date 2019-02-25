<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Address
 * @package App\Models
 */
class Address extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'address';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * 根据用户编号查询
     * @param $query
     * @param $id
     * @return string
     */
    public function scopeQueryId($query, $id)
    {
        return $id ? $query->where('id', $id) : '';
    }

    /**
     * 根据用户编号查询
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryUser($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : '';
    }

    /**
     * 查询默认
     * @param $query
     * @return mixed
     */
    public function scopeQueryDefault($query)
    {
        return $query->where('is_default', '1');
    }
}