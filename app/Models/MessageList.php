<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MessageList
 * @package App\Models
 */
class MessageList extends Model
{
    /**
     * @var string
     */
    protected $table = "message_lists";

    /**
     * @var array
     */
    protected $fillable = [
        'doctor_id', 'user_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        // 'last_message' => 'json',
    ];

    /**
     * 根据编号查询
     * @param $query
     * @param $id
     * @return string
     */
    public function scopeQueryId($query, $id)
    {
        return $id ? $query->where('id', $id) : '';
    }

    /**
     * 根据医生查询
     * @param $query
     * @param $doctor_id
     * @return string
     */
    public function scopeQueryDoctor($query, $doctor_id)
    {
        return $doctor_id ? $query->where('doctor_id', $doctor_id) : '';
    }

    /**
     * 根据医生查询
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryUser($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : '';
    }

    /**
     * 通过诊疗编号获取数据
     * @param $query
     * @param $clinic_id
     * @return string
     */
    public function scopeQueryClinicId($query, $clinic_id)
    {
        return $clinic_id ? $query->where('clinic_id', $clinic_id) : '';
    }

    /**
     * 医生
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
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
     * 消息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function message()
    {
        return $this->hasMany(Message::class, 'list_id', 'id');
    }

    /**
     * 准备修改的数据
     * @Auth: kingofzihua
     * @param array $data
     * @return $this
     */
    public function loadEditData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

}