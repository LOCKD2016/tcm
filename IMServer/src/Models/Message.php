<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/8
 * Time: 下午6:26
 */

namespace WebIM\Models;
/**
 * 消息表操作
 * Class Message
 * @package WebIM
 */
class Message extends Base
{
    public $table = "messages";

    public function add($data)
    {
        return $this->put($data);
    }

//更新单条消息状态

    public  function updateUnread($uid,$type,$listId){
        if($type=='user'){
            $field='user_id';
            $mType=['2','3'];
        }else{
            $field='doctor_id';
            $mType=['1','3'];
        }
        $wheres=[[$field,'=',$uid],['status','=','0']];//
        $num=$this->count(['where'=>$wheres,'in'=>['type',$mType]]);//获取用户所有未读消息存入缓存中
    }



    function getHistory($list_id, $id = null, $num = 10)
    {
        $wheres = [
            ['list_id', '=', $list_id],
        ];
        if (!is_null($id)) {
            $wheres[] = ['id', '<', $id];
        }
        return $this->gets(array(
                'where' => $wheres,
                'limit' => $num)
        );
    }

    function getSyncMsg($list_id, $id = null,$num = 10)
    {
        $wheres = [
            ['list_id', '=', $list_id],
        ];
        if (!is_null($id)) {
            $wheres[] = ['id', '>', $id];
        }
        return $this->gets(array(
                'where' => $wheres,
                'limit' => $num,
                'order' =>'id asc'
            )
        );
    }


}