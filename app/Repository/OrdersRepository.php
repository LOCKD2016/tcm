<?php

namespace App\Repository;

use App\Util\Tools;
use App\Models\Orders;
use App\Models\OrderLog;

/**
 * Class OrdersRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class OrdersRepository extends Repository
{

    /**
     * 通过用户获取数据列表
     * @param $user_id
     * @return mixed
     */
    public function get_data_list_by_user($user_id)
    {
        return $this->model->queryUserId($user_id)->orderBy('id','desc')->paginate($this->page);
    }

    /**
     * 通过订单编号获取 订单
     * @param $order_sn
     * @return mixed
     */
    public function get_data_by_order_sn($order_sn)
    {
        return $this->model->queryOrderSn($order_sn)->first();
    }

    /**
     * 添加订单操作日志
     * @param $order_id
     * @param $type
     * @param $desc
     * @param string $admin_id
     * @param string $extend
     * @return static
     */
    public function add_order_log($order_id, $type, $desc, $admin_id = '', $extend = '')
    {
        return OrderLog::create([
            'order_id' => $order_id,
            'type' => $type,
            'desc' => $desc,
            'admin_id' => $admin_id,
            'extend' => $extend,
        ]);
    }

    /**
     * 订单生成唯一的编号
     * @param $order
     * @return bool|string
     */
    public function get_unique_order_sn($order)
    {
        do {
            //生成唯一的订单号
            $order_new_sn = Tools::getOrderSn($order->order_sn);

            //查询下数据库 防止重复
            $order_new = $this->get_data_by_order_sn($order_new_sn);
        } while ($order_new);

        $order->order_sn = $order_new_sn;

        //修改原订单的编号
        return $order->loadEditData(['order_sn' => $order_new_sn])->save() ? $order_new_sn : false;
    }

    /**
     * @Auth: kingofzihua
     * @return Orders
     */
    public function model()
    {
        return new Orders();
    }
}