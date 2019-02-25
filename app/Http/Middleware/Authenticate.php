<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Authenticates;
use App\Exceptions\AuthenticationException;

class Authenticate extends Authenticates
{

    protected function authenticate(array $guards)
    {
        if (config('app.king')) { //判断是本地开发环境的话 就给他默认登录 @auth:kingofzihua
            app('auth')->guard('api')->setUser(\App\Models\Doctor::first());
        }

        if (empty($guards)) {
            return $this->auth->authenticate();
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new AuthenticationException('Unauthenticated.', $guards);
    }
}
