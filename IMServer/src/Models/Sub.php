<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/8
 * Time: 下午6:26
 */

namespace WebIM\Models;
/**
 * 预约表
 * Class Subscribe
 * @package WebIM
 */
class Sub extends Base
{
    public $table = "subscribe";

    function getSubscribe($sub_id){
      return $this->get($sub_id,'id');
    }

    public function setSub($clinic_id,$data){
        return $this->set($clinic_id,$data);
    }
}