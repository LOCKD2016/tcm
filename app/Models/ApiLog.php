<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * api 日志
 * Class ApiLog
 * @package App\Models
 */
class ApiLog extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_logs';

    /**
     * @var array
     */
    protected $guarded = [];

}