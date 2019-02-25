<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 评论
 * Class CommonCard
 * @package App\Models
 */
class Comment extends Model
{
    /**
     * @var string
     */
    protected $table = 'comment';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * 医生
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctors()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }

    /**
     * 用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id');
    }

    /**
     * 疾病名
     * @param $query
     * @param $disease
     */
    public function scopeCommentDisease($query, $disease)
    {
        return $disease ? $query->where('disease', $disease) : '';
    }

    /**
     * 疗效
     * @param $query
     * @param $condition
     */
    public function scopeCommentCondition($query, $condition)
    {
        return $condition === '' ? '' : $query->where('condition', $condition);
    }

    /**
     * 起始时间
     * @param $query
     * @param $start
     */
    public function scopeCommentStart($query, $start)
    {
        return $start === '' ? '' : $query->where('created_at', '>', $start);
    }

    /**
     * 结束时间
     * @param $query
     * @param $start
     */
    public function scopeCommentEnd($query, $end)
    {
        return $end === '' ? '' : $query->where('created_at', '<', $end);
    }

    /**
     * 医生
     * @param $query
     * @param $doctor
     */
    public function scopeCommentDoctor($query, $doctor)
    {
        return $doctor ? $query->WhereHas('doctors', function ($que) use ($doctor) {

            $que->where('name', 'like', '%' . $doctor . '%');

        }) : '';
    }

    /**
     * 患者
     * @param $query
     * @param $user
     */
    public function scopeCommentUser($query, $user)
    {
        return $user ? $query->WhereHas('user', function ($que) use ($user) {

            $que->where('realname', 'like', '%' . $user . '%')->orWhere('nickname', 'like', '%' . $user . '%');

        }) : '';
    }

    /**
     * @return false|string
     */
    public function getTimeAttribute()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }
}
