<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/10/28
 * Time: 下午3:14
 */

namespace App\OAuth;

use League\OAuth2\Server\AuthorizationServer as AServer;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
class AuthorizationServer extends AServer
{
    /**
     * Get the token type that grants will return in the HTTP response.
     *
     * @return ResponseTypeInterface
     */
    protected function getResponseType(){
        if ($this->responseType instanceof ResponseTypeInterface === false) {
            $this->responseType = new LbbniuTokenResponse();
        }

        $this->responseType->setPrivateKey($this->privateKey);

        return $this->responseType;
    }


    /**
     * Return an access token response.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @throws OAuthServerException
     *
     * @return ResponseInterface
     */
    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseInterface $response)
    {

        foreach ($this->enabledGrantTypes as $grantType) {
            if ($grantType->canRespondToAccessTokenRequest($request)) {
                $tokenResponse = $grantType->respondToAccessTokenRequest(
                    $request,
                    $this->getResponseType(),
                    $this->grantTypeAccessTokenTTL[$grantType->getIdentifier()]
                );

                if ($tokenResponse instanceof ResponseTypeInterface) {
                    return $tokenResponse->generateHttpResponse($response);
                }else{
                    return $this->gh_return($response,$tokenResponse);
                }

            }
        }

        throw OAuthServerException::unsupportedGrantType();
    }

    /**
     * 账号密码错误或者被拉黑返回信息
     * @param ResponseInterface $response
     * @param $tokenResponse
     * @return ResponseInterface|static
     */
    public function gh_return(ResponseInterface $response,$tokenResponse){
        $response = $response
            ->withStatus(200)
            ->withHeader('pragma', 'no-cache')
            ->withHeader('cache-control', 'no-store')
            ->withHeader('content-type', 'application/json; charset=UTF-8');

        $data = [
            'status'=>0,
            'msg'=>$tokenResponse['msg'],
            'errcode'=>$tokenResponse['code']
        ];

        $response->getBody()->write(json_encode($data));

        return $response;
    }
}