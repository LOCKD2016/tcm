<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/9
 * Time: 下午7:22
 */
namespace WebIM;
use App\Models\Subscribe;
use Swoole\Filter;
use WebIM\Models\AppUser;
use WebIM\Models\Clinic;
use WebIM\Models\Doctor;
use WebIM\Models\Message;
use WebIM\Models\MessageList;
use WebIM\Models\Recipe;
use WebIM\Models\Sub;

class IMControl
{
    /**
     * @var Store\File;
     */
    protected $storage;
    /**
     * @var Server
     */
    public $server;

    /**
     * 上一次发送消息的时间
     * @var array
     */
    public $lastSentTime = array();
    public $users;

    protected $config;
    /**
     * IMControl constructor.
     * @param Server $server
     */
    public function __construct(Server $server,$config=[])
    {
        $this->server = $server;
        $this->config = $config;
        /**
         * 使用文件或redis存储聊天信息
         */
        $this->storage = new Storage();
    }



    /**
     * 获取在线列表
     */
    function getOnline($client_id, $msg)
    {
        $resMsg = array(
            'cmd' => 'getOnline',
        );
        $users = $this->storage->getOnlineUsers('user');
        $info = $this->storage->getUsers(array_slice($users, 0, 100));
        $resMsg['users'] = $users;
        $resMsg['list'] = $info;
        $this->server->sendJson($client_id, $resMsg);
    }

    function heartBeat($client_id,$msg){
        $resMsg = ['cmd' => 'heartBeat'];
        $this->server->sendJson($client_id, $resMsg);
    }

    function showBtn($client_id,$msg){
        $resMsg = ['cmd' => 'showBtn','clinicId'=>$msg['clinicId']];
        $this->server->sendJson($client_id, $resMsg);
    }
    function onBtn($client_id,$msg){
        $user = $this->storage->getUserByClientID($client_id);
        $func = $msg['cb'];
        if (method_exists($this, $func))
        {
            $type = $user['type']=='user'?'doctor':'user';
            $to_client_id = $this->storage->getClientId($msg['userId'],$type);
            $this->$func($client_id,$to_client_id,$msg);
        }else {

        }
    }


    function getChatUser($client_id,$msg){
        $resMsg = ['cmd' => 'getChatUser'];
        $messageList= new MessageList();
        $data=$messageList->get($msg['listId'],'id')->get();
        $user=$this->getUser($data['user_id']);
        $doctor=$this->getDoctor($data['doctor_id']);
        $resMsg['user']=$user;
        $resMsg['doctor']=$doctor;
        $resMsg['clinicId']=$data['clinic_id'];
        $this->server->sendJson($client_id, $resMsg);
    }

    protected function getDoctor($uid){
        $doctor=new Doctor();
        $info=$doctor->getInfo($uid);
        $res=[];
        if($info && strpos($info['head_img_L'],'http') === false){
            $info['head_img_L'] = 'https://app.taiheguoyi.com'.$info['head_img_L'];
        }
        $info && $res=['uid'=>$info['id'],'name'=>$info['name'],'img'=>str_replace('http://','https://',$info['head_img_L'])];
        return $res;

    }
    protected function getUser($uid){
        $user=new AppUser();
        $info=$user->getInfo($uid);
        $res=[];
        if($info && strpos($info['headimgurl'],'http') === false){
            $info['head_img_L'] = 'https://app.taiheguoyi.com'.$info['head_img_L'];
        }
        $info && $res=['uid'=>$info['id'],'name'=>$info['nickname'],'img'=>str_replace('http://','https://',$info['headimgurl'])];
        return $res;

    }




    /**
     * 登录
     * @param $client_id
     * @param $msg
     */
    function login($client_id, $user)
    {
        //回复给登录用户
        $resMsg = array(
            'cmd' => 'connect',
            'fd' => $client_id,
            'user_id'=>$user['id'],
            'type'=>$user['type'],
            'msg'=>'连接成功'
        );

        $this->users[$client_id] = $resMsg;
        $this->storage->login($client_id, $user);
        $this->server->sendJson($client_id, $resMsg);
        if(!empty($user['last_client_id'])&&!empty($user['deviceId'])){
            $msg=[
                'cmd'=>'exit',
                'msg'=>'被挤掉线',
                'deviceId'=>$user['deviceId'],
            ];
            $this->server->sendJson($user['last_client_id'], $msg);
        }

    }

    /**
     * 获取历史聊天记录
     */
    function getHistory($client_id, $msg)
    {

        $task['fd'] = $client_id;
        $task['cmd'] = 'getHistory';
        //$task['offset'] = '0,100';
        $task['data'] = $msg;
        //在task worker中会直接发送给客户端
        $this->server->getSwooleServer()->task(serialize($task), Server::WORKER_HISTORY_ID);
    }
    /**
     *同步客户端信息
     */

    function getSyncMsg($client_id, $msg){

        $task['fd'] = $client_id;
        $task['cmd'] = 'getSyncMsg';
        $task['data'] = $msg;
        //在task worker中会直接发送给客户端
        $this->server->getSwooleServer()->task(serialize($task), Server::WORKER_HISTORY_ID);//
    }



    /**
     * 发送信息请求
     */
    function message($client_id, $msg)
    {
        $resMsg = $msg;
        $resMsg['cmd'] = 'fromMsg';
        //发送消息最大长度
//        if (strlen($msg['content']) > Server::MESSAGE_MAX_LEN)
//        {
//            $this->server->sendErrorMessage($client_id, 102, 'message max length is '.Server::MESSAGE_MAX_LEN);
//            return;
//        }

        $now = time();
        //上一次发送的时间超过了允许的值，每N秒可以发送一次
        //var_dump($msg);
        if (isset($this->lastSentTime[$client_id])&&$this->lastSentTime[$client_id] > $now - $this->config['webim']['send_interval_limit'])
        {

            $this->server->sendErrorMessage($client_id, 104, 'over frequency limit');
            return;
        }
        //记录本次消息发送的时间
        $this->lastSentTime[$client_id] = $now;

        $user = $this->storage->getUserByClientID($client_id);
        $type = $user['type']=='user'?'doctor':'user';
        $to_client_id = $this->storage->getClientId($msg['userId'],$type);
        $status = 0;
        $resMsg['time']=$now;
        if($user['type']=='user'){
            $userid = $user['id'];
            $doctor_id = $msg['userId'];
        }else{
            $userid = $msg['userId'];
            $doctor_id = $user['id'];
        }
        $date = date('Y-m-d H:i:s');
        $data = [
            'list_id'=>$msg['listId'],
            'msg_type'=> empty($msg['msg_type']) ? 'text' : $msg['msg_type'],
            'content'=>json_encode($msg['content'],JSON_UNESCAPED_UNICODE),
            'type'=>empty($msg['type'])?3:$msg['type'],//发送类型 1 user 2 doctor 3 system
            'send_ip'=>$user['login_ip'],
            'status'=>$status,
            'created_at'=>$date,
            'updated_at'=>$date
        ];
        $this->server->log('addHistory #:'.json_encode($data));
        $messageId=$this->storage->addHistory($data);
        if($to_client_id){
            $resMsg['messageId']=$messageId;
            $resMsg['user_id'] = $userid;
            $resMsg['doctor_id'] = $doctor_id;
            $this->server->sendJson($to_client_id, $resMsg);

        }
        $resMsg['cmd']='fromMsgSelf';//
        $this->server->sendJson($client_id,$resMsg);
        $this->server->log('fromMsgSelf #:'.json_encode($resMsg));
        /*
        //表示群发
        if ($msg['channal'] == 0)
        {
            $this->server->broadcastJson($client_id, $resMsg);
            $this->server->getSwooleServer()->task(serialize(array(
                'cmd' => 'addHistory',
                'msg' => $data,
                'fd'  => $client_id,
            )), Server::WORKER_HISTORY_ID);
        }
        //表示私聊
        elseif ($msg['channal'] == 1)
        {
            $this->server->sendJson($msg['to'], $resMsg);
            $this->storage->addHistory($data);
        }*/
    }


}