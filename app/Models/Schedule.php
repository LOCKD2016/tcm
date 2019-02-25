<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Scheduling
 * @Auth: kingofzihua
 * @package App\Models
 */
class Schedule extends Model
{
    /**
     * @var string
     */
    protected $table = "schedules";

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var bool
     */
    public $timestamps = true;


    /**
     * 关联诊所
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clinque()
    {
        return $this->hasOne(Clinique::class, 'id', 'clinique_id');
    }

    /**
     * 查询医生
     * @param $query
     * @param $doctor_id
     * @return string
     */
    public function scopeQueryDoctor($query, $doctor_id)
    {
        return $doctor_id ? $query->where('doctor_id', $doctor_id) : '';
    }

    /**
     * 查询诊所
     * @param $query
     * @param $clinique_id
     * @return string
     */
    public function scopeQueryClinique($query, $clinique_id)
    {
        return $clinique_id ? $query->where('clinique_id', $clinique_id) : '';
    }

    /**
     * 查询日期
     * @param $query
     * @param $date
     * @return string
     */
    public function scopeQueryDate($query, $date)
    {
        return $date ? $query->where('date', $date) : '';
    }
}