<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 17/6/8
 * Time: 下午10:47
 */

namespace WebIM\Models;
/**
 * 微信用户操作
 * Class AppUser
 * @package WebIM
 */
class MessageList extends Base
{
    public $table = 'message_lists';

    public function update($id,$data){
       return $this->set($id,$data);
    }





}