<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 17/6/8
 * Time: 下午10:47
 */

namespace WebIM\Models;
/**
 * 医生表操作
 * Class Doctor
 * @package WebIM
 */
class Doctor extends Base
{
    public $table = 'doctors';

    public function getUser($token){
        return $this->get($token,'im_token');
    }
    function getInfo($uid){
        return $this->get($uid,'id');
    }


}