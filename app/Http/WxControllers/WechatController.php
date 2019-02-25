<?php

namespace App\Http\WxControllers;
use App\Auth\LBWechat;
use Illuminate\Http\Request;
class WechatController extends Controller
{
    public function wxconfig(Request $request){
        $options = array(
            'appid'=>config("wechat.app_id"), //填写高级调用功能的app id
            'appsecret'=>config("wechat.secret") //填写高级调用功能的密钥
        );
        $url = $request->header('referer');
        $weObj = new LBWechat($options);
        $jsApiConfig = $weObj->getJsSign($url);
        return $this->success($jsApiConfig);
    }
}
