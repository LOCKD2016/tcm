<?php

namespace App\Models;

/**
 * 用户微信
 * Class UserWeixin
 * @package App\Models
 */
class UserWeixin extends Platform
{
    /**
     * @var string
     */
    protected $table = 'user_weixins';

    /**
     * @var array
     */
    protected $guarded = [];
}
