<?php

namespace App\Console\Commands;

use App\Http\WxControllers\BespeakController;
use Illuminate\Console\Command;
use App\Models\OrderLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repository\BespeakRepository;

class OrderClose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关闭过期没有支付的订单';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //------------------------------------------------------------------门诊订单(15分钟)---------------------------------------------------------------------
        $door_close_time = date('Y-m-d H:i:s', time() - 900);
        $this->closeSubscribe($door_close_time, 1);
        logger('关闭门诊订单----' . $door_close_time);
        //------------------------------------------------------------------网诊订单(30分钟)---------------------------------------------------------------------
        $net_close_time = date('Y-m-d H:i:s', time() - 1800);
        $this->closeSubscribe($net_close_time, 2);
        logger('关闭网诊订单----' . $net_close_time);
        //------------------------------------------------------------------药费订单,传方配药订单(72小时)---------------------------------------------------------------------
        $yf_close_time = date('Y-m-d H:i:s', time() - 259200);
        DB::table('orders')->where('status', '<', 5)
            ->where('order_type', 3)
            ->where('created_at', '<=', $yf_close_time)->orderBy('id', 'desc')
            ->chunk(100, function ($list) {
                foreach ($list as $k => $v) {
                    DB::table('orders')->where('id', $v->id)->update(['status' => 7, 'updated_at' => date('Y-m-d H:i:s')]);
                    DB::table('prescription')->where('order_id', $v->id)->update(['is_price' => 8, 'updated_at' => date('Y-m-d H:i:s')]);
                    OrderLog::create(['order_id' => $v->id, 'type' => 4, 'desc' => '订单过期', 'extend' => '药方订单超过30分钟未支付，超过30分钟，系统关闭订单', 'admin_id' => 1, 'created_at' => date('Y-m-d H:i:s')]);
                }
            });
        logger('关闭处方订单----' . $yf_close_time);

    }

    /**
     * 关闭预约
     * @Author zhoupeng
     * @Date&Time 2017-08-15 18:20
     * @param $close_time
     * @param $type 1:网诊 2:门诊预约 3:药费(药材费用+代煎) 6:排队预约
     */
    public function closeSubscribe($close_time, $type)
    {
        DB::table('orders')->where('orders.status', '<', 5)
            ->leftJoin('bespeaks as s', 'orders.id', '=', 's.order_id')
            ->where('orders.order_type', $type)
            ->where('orders.created_at', '<=', $close_time)
            ->orderBy('orders.id', 'desc')
            ->select('orders.id', 'orders.order_type', 's.id as sid')
            ->chunk(100, function ($list) {
                foreach ($list as $k => $v) {
                    $order_update = DB::table('orders')->where('id', $v->id)->update(['status' => 7, 'updated_at' => date('Y-m-d H:i:s')]);
                    if (!$order_update) logger('close---order---error--id' . $v->id);
                    $sub_update = DB::table('bespeaks')->where('id', $v->sid)->update(['status' => 38, 'updated_at' => date('Y-m-d H:i:s')]);
                    if (!$sub_update) logger('close---subscribe---error--id' . $v->sid);
                    OrderLog::create(['order_id' => $v->id, 'type' => 4, 'desc' => '订单过期', 'extend' => '预约订单超时未支付，系统关闭订单', 'admin_id' => 1, 'created_at' => date('Y-m-d H:i:s')]);
                    if ($v->order_type == 1) {
                        $cancel_msg = (new BespeakController(new BespeakRepository()))->close($v->sid);
                        if (isset($cancel_msg['errcode'])) logger('close--clinic--subscribe---fail--msg' . $cancel_msg['errcode'] . $cancel_msg['msg'] . 'bespeak_id--' . $v->sid);
                    }
                }
            });
    }

}
