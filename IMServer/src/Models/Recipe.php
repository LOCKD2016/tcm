<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/8
 * Time: 下午6:26
 */

namespace WebIM\Models;
/**
 * 开方表
 * Class Clinic
 * @package WebIM
 */
class Recipe extends Base
{
    public $table = "recipe";
    function getRecipe($clinic_id){
      return $this->get($clinic_id,'clinic_id');
    }
}