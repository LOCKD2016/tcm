<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Orders
 * @Auth: kingofzihua
 * @package App\Models
 */
class Orders extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'orders';

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * 订单编号
     * @param $query
     * @param $order_sn
     * @return string
     */
    public function scopeQueryOrderSn($query, $order_sn)
    {
        return $order_sn ? $query->where('order_sn', $order_sn) : '';
    }

    /**
     * 用户
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id', 'id');
    }

    /**
     * 商品
     * @Auth: kingofzihua
     * @return $this
     */
    public function goods()
    {
        return $this->belongsToMany(Goods::class, 'order_goods', 'order_id', 'goods_id')->withPivot(['number', 'amount', 'attr', 'extend']);
    }

    /**
     * 准备修改的数据
     * @Auth: kingofzihua
     * @param array $data
     * @return $this
     */
    public function loadEditData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * 订单中添加 商品
     * @Auth: kingofzihua
     * @param $goods_id
     * @param $attributes
     */
    public function addGoods($goods_id, $attributes)
    {
        return $this->goods()->attach($goods_id, $attributes);
    }

    /**
     * 预约
     * @Auth: Nnn
     * @return $this
     */
    public function bespeak()
    {
        return $this->belongsTo(Bespeak::class, 'id', 'order_id')->with('doctor');
    }

    /**
     * 药方
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'id', 'order_id');
    }

    /**
     * 查询用户编号
     * @param $query
     * @param $user_id
     * @return string
     */
    public function scopeQueryUserId($query, $user_id)
    {
        return $user_id ? $query->where('user_id', $user_id) : '';
    }

    /**
     * 预约/药方订单
     * @param $query
     * @return string
     */
    public function scopeOrderType($query, $type)
    {
        if ($type == 'bespeak') {
            return $query->where('order_type', '<', 3);
        } else {
            return $query->where('order_type', 3);
        }
    }

    /**
     * 订单编号
     * @param $query
     * @param $order_sn
     * @return string
     */
    public function scopeOrderSn($query, $order_sn)
    {
        return $order_sn ? $query->where('order_sn', 'like', '%' . $order_sn . '%') : '';
    }

    /**
     * 订单类型
     * @param $query
     * @param $clinic_type
     * @return string
     */
    public function scopeOrderStatus($query, $clinic_type)
    {
        return $clinic_type === '' ? '' : $query->where('order_type', $clinic_type);
    }

    /**
     * 付款方式
     * @param $query
     * @param $pay_type
     * @return string
     */
    public function scopeOrderPayType($query, $pay_type)
    {
        return $pay_type === '' ? '' : $query->where('pay_type', $pay_type);
    }

    /**
     * 付款时间
     * @param $query
     * @param $time
     * @return string
     */
    public function scopeOrderPayTime($query, $time)
    {
        return $time === '' ? '' : $query->whereDate('pay_time', $time);
    }

    /**
     * 用户姓名
     * @Auth: Nnn
     * @param $query
     * @param $user_name
     * @return string
     */
    public function scopeOrderUser($query, $user_name)
    {
        return $user_name ? $query->WhereHas('user', function ($que) use ($user_name) {

            $que->where('realname', 'like', '%' . $user_name . '%')->orWhere('nickname', 'like', '%' . $user_name . '%');

        }) : '';
    }

    /**
     * 筛选药方为快递
     * @Auth: Nnn
     * @param $query
     * @param $user_name
     * @return string
     */
    public function scopePrescriptionExpress($query, $express)
    {
        return $express ? $query->WhereHas('prescription', function ($que) use ($express) {

            $que->where('express', $express);

        }) : '';
    }

    /**
     * 用户姓名
     * @Auth: Nnn
     * @param $query
     * @param $user_name
     * @return string
     */
    public function scopeOrderConsignee($query, $user_name)
    {
        return $user_name ? $query->where('user_name', 'like', '%' . $user_name . '%') : '';
    }

    /**
     * 支付状态
     * @Auth: Nnn
     * @param $query
     * @param $pay_status
     * @return string
     */
    public function scopeOrderPayStatus($query, $pay_status)
    {
        return $pay_status === '' ? '' : $query->where('pay_status', $pay_status);
    }

    /**
     * 修改
     * @param $id
     * @param $data
     */
    public function updateData($id, $data)
    {
        return Orders::where('id', $id)->update($data);
    }

}
