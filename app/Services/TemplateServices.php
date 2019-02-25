<?php
namespace App\Services;

use App\Models\Error_note;
use EasyWeChat\Foundation\Application;
use EasyWeChat\Core\Exceptions\HttpException;

class TemplateServices{

    protected $app;

    protected $notice;

    public function __construct()
    {
        $options = config('wechat');
        $this->app = new Application($options);
        $this->notice = $this->app->notice;
    }

    public function send($templateId, $url, $sendData, $userId)
    {
        try {
            $res = $this->notice->uses($templateId)->withUrl($url)->andData($sendData)->andReceiver($userId)->send();

            new HttpException($res);

            return $res;
        } catch (HttpException $exception) {
            return $exception->getMessage();
        }
    }

    public function pushMsgTemplate($openid, $url, $data, $temId)
    {
        $msg = $this->send($temId, $url, $data, $openid);
        if (!$msg) {
            Error_note::create(['code'=> $msg->errCode, 'content'=>$msg->errMsg]);
        }
    }












































}













































?>