<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 17/6/9
 * Time: 下午7:19
 */

namespace WebIM\Models;

use Swoole\Model;
class Base extends Model
{
    public function __construct()
    {
        parent::__construct(\Swoole::getInstance());
    }
}