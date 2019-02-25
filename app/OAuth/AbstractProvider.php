<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 16/11/4
 * Time: 上午1:54
 */

namespace App\OAuth;

use Laravel\Socialite\Two\InvalidStateException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider as BaseProvider;
use SocialiteProviders\Manager\OAuth2\User;
abstract class AbstractProvider extends BaseProvider
{
    /**
     * @return \SocialiteProviders\Manager\OAuth2\User
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }
        $token = $this->request->get("token");
        if($this->request->get("loginType")=='weixin'){
            $response = $this->getAccessTokenResponse($token);
        }else{
            $response = [
                'access_token'=>$token
            ];
        }


        $user = $this->mapUserToObject($this->getUserByToken(
            $token = $this->parseAccessToken($response)
        ));

        $this->credentialsResponseBody = $response;

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->credentialsResponseBody);
        }

        return $user->setToken($token)
            ->setRefreshToken($this->parseRefreshToken($response))
            ->setExpiresIn($this->parseExpiresIn($response));
    }
}