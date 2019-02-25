<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DoctorLeave
 * @package App\Models
 */
class DoctorLeave extends Model
{
    /**
     * @var string
     */
    protected $table = 'doctor_leaves';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * 查询医生
     * @param $query
     * @param $doctor
     * @return string
     */
    public function scopeQueryDoctor($query, $doctor)
    {
        return $doctor ? $query->where('doctor_id', $doctor) : '';
    }

}