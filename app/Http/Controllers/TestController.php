<?php

namespace App\Http\Controllers;

use App\Auth\LBWechat;
use App\Events\SaveUser;
use App\Models\AppUser;
use App\Models\Clinique;
use App\Models\UserWeixin;
use App\Util\Tools;
use EasyWeChat\Foundation\Application;

/**
 * 测试控制器
 * Class TestController
 * @package App\Http\WxControllers
 */
class TestController extends ApiBaseController
{
    public function yang(){
        //判断请求方式 api  还是   web路由
        /*$options = array(
            'appid'=>config("wechat.app_id"), //填写高级调用功能的app id
            'appsecret'=>config("wechat.secret") //填写高级调用功能的密钥
        );
        $weObj = new LBWechat($options);

        $users = UserWeixin::whereNull('unionid')->select('id','openid')->get();
        $info = [];
        foreach ($users as $user){
            $wxObj = $weObj->getUserInfo($user->openid);
            if(isset($wxObj['unionid'])){
                UserWeixin::where('openid',$wxObj['openid'])->update(['unionid'=>$wxObj['unionid']]);
            }
            $info[] = $wxObj;
        }
        return $info;*/
        $this->tcmApp = new Application(config('wechat_app'));
        $result =  $this->tcmApp->payment->refund('20180706105013079963', '20180706105013079964', 1511);
        return $result;
    }
}