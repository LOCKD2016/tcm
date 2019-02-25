<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table = 'order_goods';
    protected  $guarded  = [];

    /**
     * 商品
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function goods(){

        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }
}
