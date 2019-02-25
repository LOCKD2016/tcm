<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 医生的分组表
 * Class Group
 * @package App\Models
 */
class Group extends Model
{
    /**
     * @var string
     */
    protected $table = 'group';

    /**
     * @var array
     */
    protected $fillable = [
        'name', 'doctor_id', 'num',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * 查询用户
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryUser($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : '';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(AppUser::class, 'group_user', 'group_id', 'user_id');
    }

}