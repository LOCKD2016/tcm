<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 诊疗
 * Class Clinic
 * @package App\Models
 */
class Clinic extends Model
{
    /**
     * @var string
     */
    protected $table = 'clinics';

    /**
     * @var array
     */
    protected $fillable = [
        'bespeak_id', 'user_id', 'doctor_id', 'type', 'first', 'end_time', 'comment', 'status', 'ask_time'
    ];

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
     * 根据医生编号查询
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryDoctor($query, $doctor_id)
    {
        return $doctor_id ? $query->where('doctor_id', $doctor_id) : '';
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

    /**
     * 标准问诊单
     * @auth kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inquiry()
    {
        return $this->hasOne(Inquiry::class, 'clinic_id', 'id');
    }

    /**
     * 预约信息
     * @auth kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function bespeak()
    {
        return $this->hasOne(Bespeak::class);
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
     * 诊疗评价
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'clinic_id', 'id');
    }

    /**
     * 药方信息
     * @auth Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    /**
     * 个性化问诊单
     * @auth kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exams()
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * 个性化问诊单
     * @auth kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageList()
    {
        return $this->hasOne(MessageList::class)->with('message');
    }

    /**
     * 诊疗类型
     * @param $query
     * @param $type
     */
    public function scopeClinicType($query, $type)
    {
        return $type === '' ? '' : $query->where('type', $type);
    }

    /**
     * 诊断方式
     * @param $query
     * @param $first
     */
    public function scopeClinicFirst($query, $first)
    {
        return $first === '' ? '' : $query->where('first', $first);
    }

    /**
     * 状态
     * @param $query
     * @param $status
     */
    public function scopeClinicStatus($query, $status)
    {
        return $status === '' ? '' : $query->where('status', $status);
    }

    /**
     * 状态
     * @param $query
     * @param $status
     */
    public function scopeClinicCreatedTime($query, $created_at)
    {
        return empty($created_at) ? '' : $query->whereDate('created_at', $created_at);
    }

    /**
     * 医生
     * @param $query
     * @param $doctor
     */
    public function scopeClinicDoctor($query, $doctor)
    {
        return $doctor ? $query->WhereHas('doctor', function ($que) use ($doctor) {

            $que->where('name', 'like', '%' . $doctor . '%');

        }) : '';
    }

    /**
     * 患者
     * @param $query
     * @param $user
     */
    public function scopeClinicUser($query, $user)
    {
        return $user ? $query->WhereHas('user', function ($que) use ($user) {

            $que->where('realname', 'like', '%' . $user . '%')->orWhere('nickname', 'like', '%' . $user . '%');

        }) : '';
    }

    /**
     * 获取聊诊
     * @param $id
     * @return mixed
     */
    public function getFind($id)
    {
        return Clinic::find($id);
    }

}
