<?php
namespace WebIM;
use WebIM\Models\Message;
use WebIM\Models\MessageList;

class Storage
{
    /**
     * @var \redis
     */
    protected $redis;
    const PREFIX = 'webim';
    protected $message;
    function __construct()
    {
        $this->redis = \Swoole::getInstance()->redis;
        $this->redis->delete(self::PREFIX.':online');
        $this->message = new Message();
        $this->messageList = new MessageList();
    }

    function login($client_id, $user)
    {
        //client_id 对应用户信息
        $this->redis->set(self::PREFIX . ':client:' . $client_id, json_encode($user));

        $this->redis->sAdd(self::PREFIX .':'.$user['type'].':online', $client_id);

        //存储用户对应的socket链接ID client_id
        $this->redis->set(self::PREFIX.':'.$user['type'].':'.$user['id'],$client_id);
    }

    function logout($client_id, $user)
    {
        $this->redis->del(self::PREFIX.':client:'.$client_id);
        $this->redis->sRemove(self::PREFIX.':'.$user['type'].':online', $client_id);
        //del client_id
        $this->redis->del(self::PREFIX.':'.$user['type'].':'.$user['id']);
    }

    /**
     * 用户在线用户列表 doctor user
     * @param $type
     * @return array
     */
    function getOnlineUsers($type)
    {
        return $this->redis->sMembers(self::PREFIX .':'.$type. ':online');
    }

    /**
     * 批量获取用户信息
     * @param $users
     * @return array
     */
    function getUsers($users)
    {
        $keys = array();
        $ret = array();

        foreach ($users as $v)
        {
            $keys[] = self::PREFIX . ':client:' . $v;
        }

        $info = $this->redis->mget($keys);
        foreach ($info as $v)
        {
            $ret[] = json_decode($v, true);
        }

        return $ret;
    }



    /**
     * 批量设置当前链接用户所有组的的
     * @param $users
     * @return array
     */
    function setUsersNum($listIds,$type)
    {
        $keys = array();
        $ret = array();

        foreach ($listIds as $k=>$v)
        {
            $keys[] = self::PREFIX . ':listIdUnreadNew:'.$k.':'.$type.':' . $v;//todo:批量设置用户未读消息
        }

        $info = $this->redis->mget($keys);
        foreach ($info as $v)
        {
            $ret[] = json_decode($v, true);
        }

        return $ret;
    }


    /**
     * 获取client_id对应用户信息
     * @param $client_id
     * @return bool|mixed
     */
    function getUserByClientID($clientid)
    {
        $ret = $this->redis->get(self::PREFIX . ':client:' . $clientid);
        $info = json_decode($ret, true);

        return $info;
    }

    public function getUserByID($userid,$type){
        return $this->getUserByClientID($this->getClientId($userid,$type));
    }

    /**
     * 根据用户id 和 类型 获取 client_id
     * @param $userid
     * @param $type
     * @return int|mixed
     */
    function getClientId($userid,$type){
        return $this->redis->get(self::PREFIX.':'.$type.':'.$userid);
    }

    //获取redis 中缓存未读消息
    function  getUnreadByListId($type,$userId,$ListId){
        return $this->redis->get(self::PREFIX . ':listId:'.$ListId.':'.$type.':'.$userId);
    }
    //设置redis 未读消息自增1
    function  unreadIncrementByList($type,$userId,$ListId,$num){
        return $this->redis->set(self::PREFIX . ':listId:'.$ListId.':'.$type.':'.$userId,$num+1);
    }
    /**
     * 判断client_id 的信息是否存在
     * @param $userid
     * @return bool
     */
    function addHistory($data)
    {

        $data['content']=$this->strFilter($data['content']);
        $last_message=$this->setLastMessage($data['msg_type'],$data['content']);

       // $user_new_num=$this->getUnread('user',$data['user_id']);
      //  $this->unreadIncrement('user',$data['user_id'],$user_new_num);

        //$doctor_new_num=$this->getUnread('doctor',$data['doctor_id']);
       // $this->unreadIncrement('doctor',$data['doctor_id'],$doctor_new_num);


        //获取医生和用户缓存未读消息数加1
       // var_dump('doctor_num:'.$doctor_new_num,'user_num:'.$user_new_num);
        //$this->messageList ->set($data['list_id'],['user_new_num'=>$user_new_num+1,'doctor_new_num'=>$doctor_new_num+1,'last_message'=>$last_message,'updated_at' => date('Y-m-d H:i:s', time())]);
        $this->messageList ->set($data['list_id'],['last_message'=>$last_message,'updated_at' => date('Y-m-d H:i:s', time())]);
        return $this->message->add($data);
    }

    function strFilter($str){
        $str= str_replace("'", "''",$str);
        return $str;
    }
   function setLastMessage($msg_type,$content){
        $content= json_decode($content, true);
        switch ($msg_type){
            case "text":
                $text=$content['text'];
                break;
            case "image":
                $text='【图片】';
                break;
            case "audio":
                $text='【声音】';
                break;
            case "card":
                $text='【卡片】';
                break;
        }
        return $text;
   }
    /**
     * 获取历史消息
     * @param $user_id
     * @param $doctor_id
     * @param int $offset
     * @param int $num
     * @return array
     */
    function getHistory($listId, $firstid=null, $num = 10)
    {
//        $user = $this->getUserByID($user_id,'user');
//        $doctor = $this->getUserByID($doctor_id,'doctor');
//
//        $user['nickname']=empty($user['nickname'])?'':$user['nickname'];
//        $user['headimgurl']=empty($user['headimgurl'])?'':$user['headimgurl'];
//        $doctor['name']=empty($doctor['name'])?'':$doctor['name'];
//        $doctor['headimgurl']=empty($doctor['photoSUrl'])?'':$doctor['photoSUrl'];


        $data = array();
        $list = $this->message->getHistory($listId, $firstid, $num);
        foreach ($list as $li)
        {
            $result['id'] = $li['id'];
            $result['list_id'] = $li['list_id'];
            $result['type'] = $li['type'];
            $result['msg_type'] = $li['msg_type'];
            $result['content'] = json_decode($li['content'], true);
            $result['status'] = $li['status'];
            $result['created_ed'] = strtotime($li['created_at']);
            $data[] = $result;
        }
        return $firstid ? $data: array_reverse($data);
    }

    /**
     * 同步页面消息
     * @param $list_id 聊天列表标识
     * @param int $offset
     * @param int $num
     * @return array
     */
    function getSyncMsg($list_id,$firstid, $num = 10)
    {

        $data = array();
        $list = $this->message->getSyncMsg($list_id,$firstid,$num);
        foreach ($list as $li)
        {
            $result['id'] = $li['id'];
            $result['list_id'] = $li['list_id'];
            $result['type'] = $li['type'];
            $result['msg_type'] = $li['msg_type'];
            $result['content'] = json_decode($li['content'], true);
            $result['status'] = $li['status'];
            $result['created_ed'] = strtotime($li['created_at']);
            $data[] = $result;
        }
        return  array_reverse($data);
    }

}