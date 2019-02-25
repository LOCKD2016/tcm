<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Goods
 * @Auth: kingofzihua
 * @package App\Models
 */
class Goods extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'goods';

    /**
     * 定义商品类型常量
     */
    const GOOD_TYPE = [ //商品类型 1:普通商品 11:门诊预约 12:网诊预约 13:抓药 14:煎药 15:调剂费 16:自备 17:快递
        'clinicBespeak' => '11',
        'webBespeak' => '12',
        'prescription' => '13',
        'tisane' => '14',
        'dispensing' => '15',
        'self_price' => '16',
        'express' => '17',
    ];

}
