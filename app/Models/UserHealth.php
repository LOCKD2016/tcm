<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserHealth
 * @Auth: kingofzihua
 * @package App\Models
 */
class UserHealth extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'user_health';

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $fillable = [
        'content', 'date', 'user_id', 'type'
    ];

    /**
     * @var array
     */
    protected $casts = [
//        'content' => 'json',
    ];

    /**
     * 查询用户编号
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryUserId($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : '';
    }

    /**
     * 类型
     * @Auth: kingofzihua
     * @param $query
     * @param $type 默认等于1
     * @return string
     */
    public function scopeQueryType($query, $type = 1)
    {
        return $query->where('type', $type);
    }

    /**
     * 按照日期查询日期
     * @param $query
     * @param $date
     * @return string
     */
    public function scopeQueryDate($query, $date)
    {
        return $date ? $query->whereDate('date', $date) : '';
    }

    /**
     * 开始时间
     * @param $query
     * @param $startDate
     * @return string
     */
    public function scopeQueryStartDate($query, $startDate)
    {
        return $startDate ? $query->whereDate('date', '>', $startDate) : '';
    }

    /**
     * 结束时间
     * @param $query
     * @param $endDate
     * @return string
     */
    public function scopeQueryEndDate($query, $endDate)
    {
        return $endDate ? $query->whereDate('date', '<=', $endDate) : '';
    }
}