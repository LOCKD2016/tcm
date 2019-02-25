<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/9/12
 * Time: 下午7:15
 */

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider as SysEloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
class EloquentUserProvider extends SysEloquentUserProvider
{

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];
        $salt = $user->getAuthSalt();
        return $this->hasher->check($plain.$salt, $user->getAuthPassword());
    }
}