<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 退款记录表
 * Class Refund
 * @Auth: Nnn
 * @package App\Models
 */
class Refund extends Model
{
    /**
     * @var string
     */
    protected $table = 'refund';

    protected $guarded = [];

    public function create_refund_data($data)
    {
        return Refund::create($data);
    }

}
