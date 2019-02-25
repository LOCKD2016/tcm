<?php

namespace App\Http\WxControllers;

use App\Events\SaveUser;
use App\Models\Clinique;

/**
 * 测试控制器
 * Class TestController
 * @package App\Http\WxControllers
 */
class TestController extends Controller
{
    /**
     *
     */
    public function events()
    {
        $user = \Auth::user();
        $clinique = Clinique::first();
        event(new SaveUser($user, $clinique));
    }

}