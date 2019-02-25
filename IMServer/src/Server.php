<?php
namespace WebIM;
use Swoole;
use WebIM\Models\AppUser;
use WebIM\Models\Doctor;
use WebIM\Models\Message;

class Server extends Swoole\Protocol\CometServer
{
    /**
     * @var IMControl
     */
    protected $im;
    const MESSAGE_MAX_LEN     = 1024; //单条消息不得超过1K
    const WORKER_HISTORY_ID   = 0;

    protected $storage;

    function __construct($config = array())
    {
        if (!empty($config['webim']['log_file']))
        {
            //检测日志目录是否存在
            $log_dir = dirname($config['webim']['log_file']);
            if (!is_dir($log_dir))
            {
                mkdir($log_dir, 0777, true);
            }
            $logger = new Swoole\Log\FileLog($config['webim']['log_file']);
        }
        else
        {
            $logger = new Swoole\Log\EchoLog(true);
        }
        $this->setLogger($logger);   //Logger


        $this->origin = $config['server']['origin'];
        $this->im = new IMControl($this,$config);
        $this->storage = new Storage();
        parent::__construct($config);
    }
    protected function get($key){
        if(!$this->currentRequest){
            return null;
        }
        return isset($this->currentRequest->get[$key])?$this->currentRequest->get[$key]:null;
    }

    /**
     * 连接成功 进行登录
     * @param $client_id
     */
    function onEnter($client_id)
    {

        $type = $this->get('type');
        $this->log->put('进入用户 client_id：'.$client_id);
        if(!in_array($type,['user','doctor'])){//拒绝链接
            $this->log('用户类型错误');
            return $this->close($client_id,self::CLOSE_POLICY_ERROR,'用户类型错误');
        }
        $token = $this->get('token');

        if($type == 'user'){ //app_users 中获取 获取用户信息
            $model = new AppUser();
        }else{//doctor 获取token 对应用户
            $model = new Doctor();
        }
        $user = $model->getUser($token);
        $data  = $user->get();
        if(empty($data)){
            $this->log('用户授权失败'.$token);
            return $this->close($client_id,self::CLOSE_POLICY_ERROR,'用户授权失败');
        }
        $data['deviceId']=$type=='doctor'? $this->get('deviceId'):'';
        $this->log->put('进入用户 token：'.$token.'--type:'.$type.'--deviceId:'.$data['deviceId']);
        //获取上次登录的client_id 并通知其下线；
        $last_client_id=$this->storage->getClientId($user['id'],$type);
        $this->log->put('上次进入进入的clientId ：'.$last_client_id);
        $this->onExit($last_client_id);//通知上次登录的clientId下线

        $data['type'] = $type;
        $data['login_ip'] = $this->currentRequest->getClientIP();
        $data['last_client_id']=$last_client_id;//设置缓存当前登录用户未读消息数
        $this->im->login($client_id,$data);
    }

    /**
     * 接收到消息时
     * @see WSProtocol::onMessage()
     */
    function onMessage($client_id, $ws)
    {
        $this->log("onMessage #$client_id: " . $ws['message']);
        $msg = json_decode($ws['message'], true);
        if (empty($msg['cmd']))
        {
            $this->sendErrorMessage($client_id, 101, "invalid command");
            return;
        }
        $func = $msg['cmd'];
        if (method_exists($this->im, $func))
        {
            $this->im->$func($client_id, $msg);
        }
        else
        {
            $this->sendErrorMessage($client_id, 102, "command $func no support.");
            return;
        }
    }

    /**
     * 下线时，通知所有人
     */
    function onExit($client_id)
    {
        $this->log->put('退出用户 client_id：'.$client_id);
        $userInfo = $this->storage->getUserByClientID($client_id);
        if ($userInfo)
        {
            $this->storage->logout($client_id,$userInfo);
            unset($this->im->users[$client_id]);
            /*$resMsg = array(
                'cmd' => 'offline',
                'fd' => $client_id,
                'from' => 0,
                'channal' => 0,
                'data' => $userInfo['name'] . "下线了",
            );*/
            //将下线消息发送给所有人
            //$this->broadcastJson($client_id, $resMsg);
        }
        $this->log("onOffline: " . $client_id);
    }

    function onTask($serv, $task_id, $from_id, $data)
    {
        $req = unserialize($data);
        if ($req)
        {
            switch($req['cmd'])
            {
                case 'getHistory':
                    $msg = $req['data'];
                    //自己是用户
                    if(empty($msg['firstId'])){
                        $firstId = null;//消息id
                    }else{
                        $firstId = $msg['firstId'];
                    }
                    $listId = $msg['listId'];
                    $history = array('cmd'=> 'getHistory', 'history' => $this->storage->getHistory($listId,$firstId));
                    if ($this->isCometClient($req['fd']))
                    {
                        return $req['fd'].json_encode($history);
                    }
                    //WebSocket客户端可以task中直接发送
                    else
                    {
                        $this->sendJson(intval($req['fd']), $history);
                    }
                    break;
                case 'addHistory':
                    if (!empty($req['msg']))
                    {
                        $this->storage->addHistory($req['msg']);
                    }
                    break;
                case "getSyncMsg":
                    $msg = $req['data'];
                    if(empty($msg['firstId'])){
                        $firstId = 0;//消息id
                    }else{
                        $firstId = $msg['firstId'];
                    }
                    if(empty($msg['listId'])){
                        $listId = 0;//消息id
                    }else{
                        $listId = $msg['listId'];
                    }
                    $syncMsg = ['cmd'=> 'getSyncMsg', 'syncMsg' => $this->storage->getSyncMsg($listId,$firstId)];
                    if ($this->isCometClient($req['fd']))
                    {
                        return $req['fd'].json_encode($syncMsg);
                    }
                    //WebSocket客户端可以task中直接发送
                    else
                    {
                        $this->sendJson(intval($req['fd']), $syncMsg);
                    }
                    break ;
                default:
                    break;
            }
        }
    }

    function onFinish($serv, $task_id, $data)
    {
        $this->send(substr($data, 0, 32), substr($data, 32));
    }

    /**
     * 发送错误信息
    * @param $client_id
    * @param $code
    * @param $msg
     */
    function sendErrorMessage($client_id, $code, $msg)
    {
        $this->sendJson($client_id, array('cmd' => 'error', 'code' => $code, 'msg' => $msg));
    }

    /**
     * 发送JSON数据
     * @param $client_id
     * @param $array
     */
    function sendJson($client_id, $array)
    {
        $array['fd']=$client_id;//
        $msg = json_encode($array);
        if ($this->send($client_id, $msg) === false)
        {
            $this->close($client_id);
        }
    }

    /**
     * 广播JSON数据
     * @param $client_id
     * @param $array
     */
    function broadcastJson($sesion_id, $array)
    {
        $msg = json_encode($array);
        $this->broadcast($sesion_id, $msg);
    }

    function broadcast($current_session_id, $msg)
    {
        foreach ($this->im->users as $client_id => $name)
        {
            if ($current_session_id != $client_id)
            {
                $this->send($client_id, $msg);
            }
        }
    }
}

