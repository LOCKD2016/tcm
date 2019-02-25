<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 普通问诊单
 * Class Inquiry
 * @Auth: kingofzihua
 * @package App\Models
 */
class Inquiry extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'inquiry';

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $fillable = [
        'disease', 'desc', 'type', 'bespeak_id', 'user_id'
    ];

    /**
     * 用户
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class);
    }

    /**
     * 获取时间 将创建时间转化下
     * @return false|string
     */
    public function getTimeAttribute()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }

    public function getDetailByBespeak($bespeak_id)
    {
        return self::where('bespeak_id', $bespeak_id)->first();
    }
}
