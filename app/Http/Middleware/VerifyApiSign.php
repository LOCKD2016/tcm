<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class VerifyApiSign
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //验证 X-Token-With
        if(!$this->verifyToken($request)){
            abort(400,"token 异常");
        }
        //验证 X-LbbNiu-Sign
        if(!$this->verifySign($request)){
            abort(400,"sign 异常");
        }
        return $next($request);
    }

    public function verifyToken(Request $request){
        //var appKey = signature.sha1Sync({data:appId + "VMING" + key + "VMING" + now}) + "." + now+'.'+appType;
        $appId =  config("api.appId");
        $appKey = config("api.appKey");
        $tokenWith = $request->header("X-Token-With");
        $signArr = explode('.',$tokenWith);
        if(count($signArr)!==3){
            return false;
        }
        $sign = $signArr[0];
        $newStr = "{$appId}VMING{$appKey}VMING{$signArr[1]}";
        if(strtoupper(sha1($newStr)) !== $sign){
            return false;
        }
        return true;
    }

    public function verifySign(Request $request){
        $join = config("api.join");
        $prefix = config("api.prefix");
        $url = str_replace("/{$prefix}/",'',$request->getPathInfo());
        $method = strtolower($request->method());
        $systemType = $request->header("X-System-Type");
        $systemVersion = $request->header("X-System-Version");
        $deviceId = $request->header("X-Device-Id");
        $deviceModel = $request->header("X-Device-Model");
        $appVersion = $request->header("X-App-Version");
        $sign = $request->header("X-LbbNiu-Sign");
        // 接口简短形式 + 请求类型 + 系统类型 + 系统版本号 + 设备id + 设备型号 + app版本 + data hmac + &appkey
        $strObj = "{$url}{$join}{$method}{$join}{$systemType}{$join}{$systemVersion}{$join}{$deviceId}{$join}{$deviceModel}{$join}{$appVersion}";
        $appKey = config("api.appKey");//与客户端保持一直
        $data = $request->all();
        $keys = array_keys($request->allFiles());
        ksort($data);
        $valueArr = [];
        foreach($data as $key => $val){
            if(!empty($val) && !in_array($key,$keys)){
                if(is_array($val)){
                    $val = implode(',',$val);
                }
                $valueArr[] = "{$key}={$val}";
            }
        }
        $dStr = implode($join,$valueArr);
        $strObj = "{$strObj}{$join}{$dStr}{$join}{$appKey}";
        //$newSign = strtoupper(sha1($strObj));
        $newSign = strtoupper(md5($strObj));
        //echo $sign.'----'.$strObj.'-----';
        //exit($newSign);
        if($newSign === $sign){
            return true;
        }
        return false;
    }
}
