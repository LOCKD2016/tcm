<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 个性化问诊单 答案
 * Class ExamAnswer
 * @Auth: kingofzihua
 * @package App\Models
 */
class ExamAnswer extends Model
{
    /**
     * @var string
     */
    protected $table = 'exam_answer';

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $guarded = [];

    /**
     * 试题
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(ExamOption::class,'question_id','id');
    }

    /**
     * 获取时间 将创建时间转化下
     * @return false|string
     */
    public function getTimeAttribute()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }

    /**
     * 查询诊疗编号
     * @param $query
     * @param $clinic_id
     * @return string
     */
    public function scopeQueryClinicId($query, $clinic_id)
    {
        return $clinic_id ? $query->where('clinic_id', $clinic_id) : '';
    }
}