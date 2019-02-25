<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 16/10/28
 * Time: 上午2:51
 */

namespace App\Providers;

use Laravel\Passport\PassportServiceProvider as ServiceProvider;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\ClientRepository;
//use League\OAuth2\Server\Grant\PasswordGrant;
use App\OAuth\PasswordGrant;
use Laravel\Passport\Bridge;
use Illuminate\Auth\RequestGuard;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;
use App\OAuth\UserRepository;
use App\OAuth\ResourceServer;
use App\OAuth\TokenGuard;
use App\OAuth\AuthorizationServer;
class PassportServiceProvider extends ServiceProvider
{
    /**
     * Create and configure a Password grant instance.
     *
     * @return PasswordGrant
     */
    protected function makePasswordGrant()
    {
        $grant = new PasswordGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(Bridge\RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
    /**
     * Make an instance of the token guard.
     *
     * @param  array  $config
     * @return RequestGuard
     */
    protected function makeGuard(array $config)
    {
        return new RequestGuard(function ($request) use ($config) {
            return (new TokenGuard(
                $this->app->make(ResourceServer::class),
                Auth::createUserProvider($config['provider']),
                new TokenRepository,
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter')
            ))->user($request);
        }, $this->app['request']);
    }

    /**
     * Register the resource server.
     *
     * @return void
     */
    protected function registerResourceServer()
    {
        $this->app->singleton(ResourceServer::class, function () {
            return new ResourceServer(
                $this->app->make(Bridge\AccessTokenRepository::class),
                'file://'.Passport::keyPath('oauth-public.key')
            );
        });
    }

    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        $server =  new AuthorizationServer(
            $this->app->make(Bridge\ClientRepository::class),
            $this->app->make(Bridge\AccessTokenRepository::class),
            $this->app->make(Bridge\ScopeRepository::class),
            'file://'.Passport::keyPath('oauth-private.key'),
            'file://'.Passport::keyPath('oauth-public.key')
        );
        $server->setEncryptionKey(app('encrypter')->getKey());
        return $server;
    }

}