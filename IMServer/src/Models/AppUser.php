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
class AppUser extends Base
{
    public $table = 'app_users';

    public function getUser($token){
        return $this->get($token,'im_token');
    }

    function getInfo($uid){
        return $this->get($uid,'id');
    }




}