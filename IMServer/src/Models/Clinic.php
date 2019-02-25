<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/8
 * Time: 下午6:26
 */

namespace WebIM\Models;
/**
 * 诊疗表
 * Class Clinic
 * @package WebIM
 */
class Clinic extends Base
{
    public $table = "clinic";

    public function setClinic($clinic_id,$data){
        return $this->set($clinic_id,$data);
    }
    public function getNewClinicId($user_id,$doctor_id){
        $wheres = [
            ['user_id','=',$user_id],
            ['doctor_id','=',$doctor_id],
            ['recipe_status','=',0],
        ];
        return $this->gets(['where'=>$wheres,'limit' => 1]);

    }
    function getClinic($clinic_id){
      return $this->get($clinic_id,'id');
    }
}