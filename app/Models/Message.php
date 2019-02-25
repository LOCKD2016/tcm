<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @package App\Models
 */
class Message extends Model
{
    /**
     * @var string
     */
    protected $table = "messages";

    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var array
     */
    protected $casts = [
        'content' => 'json', //聊天内容
    ];

    /**
     * 获取表名
     * @return string
     */
    protected function getTableName()
    {
        return $this->table;
    }
}
