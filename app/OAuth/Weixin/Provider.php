<?php

namespace App\OAuth\Weixin;

use Laravel\Socialite\Two\ProviderInterface;
use App\OAuth\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'WEIXIN';

    /**
     * @var string
     */
    protected $openId;

    /**
     * {@inheritdoc}.
     */
    protected $scopes = ['snsapi_userinfo'];

    /**
     * set Open Id.
     *
     * @param string $openId
     */
    public function setOpenId($openId)
    {
        $this->openId = $openId;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://open.weixin.qq.com/connect/oauth2/authorize', $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);

        return $url.'?'.$query.'#wechat_redirect';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'appid' => $this->clientId, 'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state' => $state,
        ];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenUrl()
    {
        return 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://api.weixin.qq.com/sns/userinfo', [
            'query' => [
                'access_token' => $token,
                'openid' => $this->openId,
                'lang' => 'zh_CN',
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['unionid'],
            'nickname' => isset($user['nickname']) ? $user['nickname'] : null,
            'avatar' => isset($user['headimgurl']) ? $user['headimgurl'] : null,
            'province'=>$user['province'],
            'city'=>$user['city'],
            'sex'=>$user['sex'],
            'country'=>$user['country'],
            'name' => null,
            'email' => null,
        ]);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenFields($code)
    {
        return [
            'appid' => $this->clientId, 'secret' => $this->clientSecret,
            'code' => $code, 'grant_type' => 'authorization_code',
        ];
    }

    /**
     * {@inheritdoc}.
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        $this->credentialsResponseBody = json_decode($response->getBody(), true);
        $this->openId = $this->credentialsResponseBody['openid'];

        return $this->credentialsResponseBody;
    }

    /**
     * {@inheritdoc}.
     */
    public function scopes(array $scopes)
    {
        $this->scopes = array_unique($scopes);

        return $this;
    }
}
