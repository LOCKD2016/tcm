<?php
/**
 * Created by PhpStorm.
 * User: alex807666873
 * Date: 2017-05-05
 * Time: 上午10:58
 */

namespace App\Http\Middleware;

use App\Models\AppUser;
use App\Models\UserWeixin;
use App\Util\Tools;
use Closure;
use App\Auth\LBWechat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class Wechat
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!(strpos(url()->previous(), 'index') || strpos(url()->previous(), 'sign'))) {
            session(['preurl' => url()->previous()]); //处理分享链接,授权后跳回原链接
        }
        if(!Request::isJson() && false === strpos(url()->current(),'/wechat/')){
            return response()->redirectTo('/wechat/index');
        }
        if(!session('user')&&Tools::isWeChatBrowser()){
            //判断请求方式 api  还是   web路由
            $options = array(
                'appid'=>config("wechat.app_id"), //填写高级调用功能的app id
                'appsecret'=>config("wechat.secret") //填写高级调用功能的密钥
            );
            $weObj = new LBWechat($options);
            //echo url()->current(); echo url()->full(); echo url()->previous();
            $selfUrl = url()->full();
            $url = $weObj->getOauthRedirect($selfUrl,'','snsapi_userinfo',env("WECHAT_AUTH"));
            $token = $weObj->getOauthAccessToken();
            if(!$token){
                return response()->redirectTo($url);
            }
            $user = $weObj->getOauthUserinfo($token['access_token'],$token['openid']);
            $wxuser = UserWeixin::where('unionid',$user['unionid'])->orWhere('openid',$user['openid'])->first();
            if($wxuser){
                if(!$wxuser->unionid  || !$wxuser->openid){
                    $wxuser->unionid = $user['unionid'];
                    $wxuser->openid = $user['openid'];
                    $wxuser->save();
                }
                Auth::guard('wx_user')->loginUsingId($wxuser->user_id);
                $appuser = AppUser::find($wxuser->user_id);
                $appuser->im_token = Tools::getChatToken();
                $appuser->save();
                $user['im_token'] = $appuser->im_token;
            }
            $response = $next($request);
            session(['user'=>$user]);
        }else{
            $response = $next($request);
            $user = session('user');
        }
        if(!$request->cookie('openid')){
            $response->cookie('openid',$user['openid'],43200);//30天
        }
        return $response;
    }
}