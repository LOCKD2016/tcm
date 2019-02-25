<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 试卷 == 个性化问诊单
 * Class Exam
 * @package App\Models
 */
class Exam extends Model
{

    use SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'exam';

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'doctor_id', 'type'
    ];

    /**
     * 通过医生编号查询
     * @Auth: kingofzihua
     * @param $query
     * @return mixed
     */
    public function scopeQueryDoctorId($query, $doctor_id)
    {
        return $doctor_id !== '' ? $query->where('doctor_id', $doctor_id) : '';
    }

    /**
     * 通过类型查询
     * @param $query
     * @param $type
     * @return string
     */
    public function scopeQueryType($query, $type = '')
    {
        return $type !== '' ? $query->where('type', $type) : '';
    }

    /**
     * 题目
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ExamOption::class, 'exam_id', 'id');
    }

    /**
     * 个性化问诊单的答案
     * @desc 只有有用户答题了才有答案 所以得确定用户的编号
     *      如果是微信端 直接获取登录的用户编号 如果是医生端 需要传user_id
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        $clinic_id = request('clinic_id');

        return $this->hasMany(ExamAnswer::class, 'exam_id', 'id')->where('clinic_id', $clinic_id);
    }

}