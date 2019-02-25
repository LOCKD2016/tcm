<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 个性化问诊单 题目
 * Class ExamOption
 * @package App\Models
 */
class ExamOption extends Model
{

    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'exam_options';

    /**
     * @var array
     */
    protected $fillable = ['exam_id', 'title', 'type', 'option', 'must', 'sort'];

    /**
     * @var array
     */
    protected $casts = [
        'option' => "json",
    ];

    /**
     * 储存答案用的
     * @desc 答案直接放到题目的下面，所以必须有个地方存答案
     * @auth kingofzihua
     * @var string
     */
    protected $answers = '';

    /**
     * @var array
     */
    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at',
    ];

}