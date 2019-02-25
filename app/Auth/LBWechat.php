<?php
namespace App\Auth;

use Illuminate\Support\Facades\Cache;
use App\Auth\Wechat;

class LBWechat extends Wechat{

    /**
     * log overwrite
     * @see Wechat::log()
     */
    protected function log($log){
        if ($this->debug) {
            is_array($log)  ? \Log::debug('LBWechat',$log) :  \Log::debug("LBWechat : $log");
        }
        return false;
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename,$value,$expired){

        Cache::put($cachename, $value, intval($expired/60));
        return true;//S($cachename,$value,$expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename){
        $ret = Cache::get($cachename);
        return $ret;//S($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename){
        $ret = Cache::pull($cachename);
        return $ret;
    }

    public $component_appid = "";
    public $component_appsecret = "";
    public $component_verify_ticket = "";
    public $component_access_token = "";
    public $pre_auth_code = "";

    public function setComponent($component_appid,$component_appsecret){
        $this->component_appid = $component_appid;
        $this->component_appsecret = $component_appsecret;
    }

    public function setComponentVerifyTicket($component_verify_ticket,$component_appid=''){
        if(!$component_appid)
            $component_appid = $this->component_appid;
        $this->component_verify_ticket = $component_verify_ticket;

        $cachename = "component_verify_ticket_".$component_appid;
        $this->setCache($cachename,$component_verify_ticket,1000);
    }

    /**
     * 1、推送component_verify_ticket协议
     * @param string $component_appid
     * @return mixed|string
     */
    public function getComponentVerifyTicket($component_appid=''){
        if($this->component_verify_ticket)
            return $this->component_verify_ticket;
        if(!$component_appid)
            $component_appid = $this->component_appid;
        $authname = "component_verify_ticket_".$component_appid;
        if ($rs = $this->getCache($authname))  {
            $this->component_verify_ticket = $rs;
            return $rs;
        }
        return false;
    }

    /**
     * 2、获取第三方平台component_access_token
     * @param string $component_appid
     * @param string $component_appsecret
     * @param string $token
     * @return bool|mixed|string
     */
    public function getComponentAccessToken($component_appid='',$component_appsecret='',$token=''){
        if (!$component_appid || !$component_appsecret) {
            $component_appid = $this->component_appid;
            $component_appsecret = $this->component_appsecret;
        }
        if ($token) { //手动指定token，优先使用
            $this->component_access_token=$token;
            return $this->component_access_token;
        }

        $authname = 'component_access_token'.$component_appid;
        if ( $rs = $this->getCache($authname))  {
            $this->component_access_token = $rs;
            return $rs;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/component/api_component_token";
        $data = [
            'component_appid'=>$component_appid,
            'component_appsecret'=>$component_appsecret,
            'component_verify_ticket'=>$this->getComponentVerifyTicket($component_appid),
        ];
        $result = $this->http_post($url,self::json_encode($data));
        if ($result)
        {

            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->component_access_token = $json['component_access_token'];
            $expire = $json['expires_in'] ? intval($json['expires_in'])-200 : 3600;
            $this->setCache($authname,$this->component_access_token,$expire);
            return $this->component_access_token;
        }
        return false;
    }

    /**136713d2e1b07cc7dc3342d79c3d642b
     * 3、获取预授权码pre_auth_code
     * @param string $component_appid
     * @param string $component_appsecret
     * @param string $token
     * @return bool|mixed|string
     */
    public function getPreAuthCode($component_appid='',$component_appsecret='',$token=''){
        if (!$component_appid || !$component_appsecret) {
            $component_appid = $this->component_appid;
            $component_appsecret = $this->component_appsecret;
        }

        if ($token) { //手动指定token，优先使用
            $this->pre_auth_code=$token;
            return $this->pre_auth_code;
        }

        $authname = 'pre_auth_code'.$component_appid;
        if (false && $rs = $this->getCache($authname))  {
            $this->pre_auth_code = $rs;
            return $rs;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token=".$this->getComponentAccessToken($component_appid,$component_appsecret);
        if(!$component_appid){
            $component_appid = $this->component_appid;
        }
        $data = ['component_appid'=>$component_appid];

        $result = $this->http_post($url,self::json_encode($data));
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->pre_auth_code = $json['pre_auth_code'];
            $expire = $json['expires_in'] ? intval($json['expires_in'])-200 : 1000;
            $this->setCache($authname,$this->pre_auth_code,$expire);
            return $this->pre_auth_code;
        }
        return false;
    }

    /**
     * 4、使用授权码换取公众号的接口调用凭据和授权信息
     */
    public function getComponentAuthInfo($auth_code){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=".$this->getComponentAccessToken();
        $data = [
            'component_appid'=>$this->component_appid,
            'authorization_code'=>$auth_code
        ];
        $result = $this->http_post($url,self::json_encode($data));
        if($result){
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json['authorization_info'];
        }
        return false;
    }

    /**
     * 5、获取（刷新）授权公众号的接口调用凭据（令牌）
     * @param $authorizer_appid
     * @param $authorizer_refresh_token
     * @return bool|mixed
     */
    public function componentRefreshToken($authorizer_appid,$authorizer_refresh_token){
        $url = "https://api.weixin.qq.com/cgi-bin/component/api_authorizer_token?component_access_token=".$this->getComponentAccessToken();;
        $data = [
            'component_appid'=>$this->component_appid,
            'authorizer_appid'=>$authorizer_appid,
            'authorizer_refresh_token'=>$authorizer_refresh_token
        ];
        $result = $this->http_post($url,self::json_encode($data));
        if($result){
            $json = json_decode($result,true);
            if (!$json || isset($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return json_decode($result);
        }
        return false;
    }
    //6、获取授权方的公众号帐号基本信息
    //7、获取授权方的选项设置信息
    //8、设置授权方的选项信息
    //9、推送授权相关通知


    public function getComponentOauthRedirect($redirect_uri){
        $pre_auth_code = $this->getPreAuthCode();
        return "https://mp.weixin.qq.com/cgi-bin/componentloginpage?component_appid={$this->component_appid}&pre_auth_code={$pre_auth_code}&redirect_uri=".urlencode($redirect_uri);
    }

    public function clearCache(){
        $authname = 'component_access_token'.$this->component_appid;
        $this->removeCache($authname);
        $authname = 'pre_auth_code'.$this->component_appid;
        $this->removeCache($authname);
    }




    /**
     * oauth 授权跳转接口
     * @param string $callback 回调URI
     * @return string
     */
    public function getOpenRedirect($callback,$state='',$scope='snsapi_userinfo'){
        return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'&component_appid='.$this->component_appid.'#wechat_redirect';

        //return 'http://auth.vliang.com/oauth.html?appid='.$this->appid.'&redirect_uri='.urlencode($callback).'&response_type=code&scope='.$scope.'&state='.$state.'&component_appid='.$this->component_appid.'#wechat_redirect';

    }

    /**
     * 通过code获取Access Token
     * @return array {access_token,expires_in,refresh_token,openid,scope}
     */
    public function getOpenAccessToken($code=""){
        $code = isset($_GET['code'])?$_GET['code']:$code;

        if (!$code) return false;

        $result = $this->http_get('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$this->appid
            .'&component_appid='.$this->component_appid.'&component_access_token='.$this->getComponentAccessToken().'&code='.$code.'&grant_type=authorization_code');
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->access_token = $json['access_token'];

            return $json;
        }
        return false;
    }

    /**
     * 刷新access token并续期
     * @param string $refresh_token
     * @return boolean|mixed
     */
    public function getOpenRefreshToken($refresh_token){
        $result = $this->http_get('https://api.weixin.qq.com/sns/oauth2/component/refresh_token?appid='.$this->appid
            .'&grant_type=refresh_token&component_appid='.$this->component_appid
            .'&component_access_token='.$this->getComponentAccessToken().'&refresh_token='.$refresh_token);
        if ($result)
        {
            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            $this->access_token = $json['authorizer_access_token'];
            return $json;
        }
        return false;
    }

    /**
     * 获取授权后的用户资料
     * @param string $access_token
     * @param string $openid
     * @return array {openid,nickname,sex,province,city,country,headimgurl,privilege,[unionid]}
     * 注意：unionid字段 只有在用户将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
     */
    public function getOpenUserinfo($access_token,$openid){
        $result = $this->http_get(self::API_BASE_URL_PREFIX.self::OAUTH_USERINFO_URL.'access_token='.$access_token.'&openid='.$openid);
        if ($result)
        {

            $json = json_decode($result,true);
            if (!$json || !empty($json['errcode'])) {
                $this->errCode = $json['errcode'];
                $this->errMsg = $json['errmsg'];
                return false;
            }
            return $json;
        }
        return false;
    }



    public function pushMsgTemplate($openid,$tplId,$url,$data){
        $datas['touser'] = $openid;
        $datas['template_id'] = $tplId;
        $datas['url'] = $url;
        $datas['data'] = $data;
//        if(env('WECHAT_AUTH')){
//            $wechat = new \Yar_Client('http://auth.vliang.com/lbbniu/weixin');
//            return $wechat->sendTemplateMessage($datas);
//        }
        return $this->sendTemplateMessage($datas);
    }


}