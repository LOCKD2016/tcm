<?php

namespace App\Auth;
use Illuminate\Auth\AuthManager as SysAuthManager;

class AuthManager extends SysAuthManager
{

    /**
     * Create an instance of the Eloquent user provider.
     *
     * @param  array  $config
     * @return \Illuminate\Auth\EloquentUserProvider
     */
    protected function createEloquentProvider($config)
    {
        return new EloquentUserProvider($this->app['hash'], $config['model']);
    }
}
