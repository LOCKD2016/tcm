<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/10/28
 * Time: 下午6:26
 */

namespace App\Http\Middleware;


use App\Models\AppUser;
use Closure;
use Illuminate\Support\Facades\Auth;
class ResponseMiddleware
{
    public function handle($request, Closure $next)
    {


//        if($request->hasHeader('origin') || !Auth::id()){
        if($request->hasHeader('origin')&&$request->hasHeader('origin') != env('APP_URL') && !Auth::guard('wx_user')->id()){
            $user = AppUser::find(11);
            app('auth')->guard('wx_user')->setUser($user);
        }


        //本地环境 默认登录第一个用户 @auth kingofzihua//
        if (config('app.king')){
            $user = AppUser::first();
            $user && app('auth')->guard('wx_user')->setUser($user);
        }

        $request->setTrustedProxies(array('10.0.0.1/8','192.168.0.1/16'));

        $response = $next($request);
//        $response->header('Access-Control-Max-Age', '1728000');
//        $response->header('Access-Control-Allow-Origin', '*');
//        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Cookie, Accept, multipart/form-data, application/json');
//        $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
//        $response->header('Access-Control-Allow-Credentials', 'true');
        return $response;
//        return $response;
    }

}