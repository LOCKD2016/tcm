<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Auth\LBWechat;
use App\Util\Tools;

class RemindLnquiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:lnquiry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '提醒订单完成未填写问诊单的用户填写问诊单';

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
        $result = DB::table('orders')
            ->leftJoin('app_users as user','user.id','orders.user_id')
            ->leftJoin('user_weixins as wx','wx.user_id','orders.user_id')
            ->where('orders.order_status',1)
            ->where('orders.pay_status',2)
            ->whereNotExists(function($query){
                $query->select(DB::raw(1))
                    ->from('user_lnquiry as ul')
                    ->whereRaw('orders.id = ul.order_id');
            })
            ->orderBy('wx.openid')->select('orders.pay_time','user.mobile','wx.openid')
            ->groupBy('wx.openid')
            ->chunk(100,function ($list){
                foreach($list as $k=>$v){
                    $date = date('Y年m月d日', strtotime($v->pay_time));

                }
            });
    }

    // 微信模板
    private function pushTemplate($openid,$url,$data){
        $wechat = new LBWechat([
            'appid'=>config("wechat.app_id"), //填写高级调用功能的app id
            'appsecret'=>config("wechat.secret") //填写高级调用功能的密钥
        ]);
        $ret = $wechat->pushMsgTemplate($openid,'l8QrOgXwiclTbjwMFGNhmcS2Xaih-eDf6-FfuKeTC-c',$url,$data);
        if(!$ret){
            Log::info('pushMsgTemplate',['code'=>$wechat->errCode,'msg'=>$wechat->errMsg]);
        }
    }
}
