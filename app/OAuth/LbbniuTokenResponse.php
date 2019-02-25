<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/10/28
 * Time: 下午3:10
 */

namespace App\OAuth;

use App\Models\Doctor;
use Illuminate\Support\Facades\DB;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Server\ResponseTypes\AbstractResponseType;

class LbbniuTokenResponse extends AbstractResponseType
{
    /**
     * {@inheritdoc}
     */
    public function generateHttpResponse(ResponseInterface $response)
    {
        $expireDateTime = $this->accessToken->getExpiryDateTime()->getTimestamp();

        $jwtAccessToken = $this->accessToken->convertToJWT($this->privateKey);

        $responseParams = [
            //'token_type'   => 'Lbbniu',
            'expires_in'   => $expireDateTime - (new \DateTime())->getTimestamp(),
            'access_token' => (string) $jwtAccessToken,
        ];

        //保证单点登录 ， 删除其他的token
        DB::table('oauth_access_tokens')
            ->where('user_id',$this->accessToken->getUserIdentifier())
            ->where('client_id',$this->accessToken->getClient()->getIdentifier())
            ->where('id','!=',$this->accessToken->getIdentifier())->delete();

        if ($this->refreshToken instanceof RefreshTokenEntityInterface) {
            $refreshToken = $this->encrypt(
                json_encode(
                    [
                        'client_id'        => $this->accessToken->getClient()->getIdentifier(),
                        'refresh_token_id' => $this->refreshToken->getIdentifier(),
                        'access_token_id'  => $this->accessToken->getIdentifier(),
                        'scopes'           => $this->accessToken->getScopes(),
                        'user_id'          => $this->accessToken->getUserIdentifier(),
                        'expire_time'      => $this->refreshToken->getExpiryDateTime()->getTimestamp(),
                    ]
                )
            );

            $responseParams['refresh_token'] = $refreshToken;
        }

        $responseParams = array_merge($this->getExtraParams($this->accessToken), $responseParams);

        $response = $response
            ->withStatus(200)
            ->withHeader('pragma', 'no-cache')
            ->withHeader('cache-control', 'no-store')
            ->withHeader('content-type', 'application/json; charset=UTF-8');

        $data = [
            'status'=>1,
            'msg'=>'ok',
            'errcode'=>0,
            'data'=>$responseParams
        ];

        $response->getBody()->write(json_encode($data));

        return $response;
    }

    /**
     * Add custom fields to your Bearer Token response here, then override
     * AuthorizationServer::getResponseType() to pull in your version of
     * this class rather than the default.
     *
     * @param AccessTokenEntityInterface $accessToken
     *
     * @return array
     */
    protected function getExtraParams(AccessTokenEntityInterface $accessToken)
    {
        $model = Doctor::find($accessToken->getUserIdentifier());

        $data = [
            "id" => $model->id,
            "mobile" => $model->mobile,
            "nickname" => $model->name?:'',
            "photoSUrl" => $model->photoSUrl?:'', //小头像
            "photoLUrl" => $model->photoLUrl?:'',//大头像
            "expert" => $model->expert?:'', //擅长 json串形式
            "intro" => $model->intro, //医生介绍
            "im_token"=>$model->im_token,//
            "qrcode"=>$model->qrcode,
            'blacklist'=>$model->blacklist,
            "created_at"=>$model->created_at,
        ];
        return $data;
    }

    /**
     * 备份之前的方法
     * @auther kingofzihua
     * @date 2017/06/07
     */
    public function getExtraParamsBak(AccessTokenEntityInterface $accessToken)
    {
        $model = \App\Models\AppUser::find($accessToken->getUserIdentifier());

        $data = [
            "did" => $model->id,
            "mobile" => $model->mobile,
            "nickname" => $model->nickname,
            "headimgurl" => $model->headimgurl,
            "email" => $model->email,
            "birthday" => $model->birthday,
            "height" => $model->height,
            "stage" => is_null($model->stage)?0:$model->stage,
            "marital" => is_null($model->marital)?0:$model->marital,
            "province" => $model->province,
            "city" => $model->city,
            "area" => $model->area,
            "notice_status" => $model->notice_status,
            "collection_num" => $model->collection_num,
            "first_login" => \App\Util\Tools::isMobile($model->mobile)?0:1,
        ];


        // 转换 null 字段为空字符串
        foreach (array_keys($data) as $key) {
            if (! isset($data[$key])) {
                $data[$key] = '';
                continue;
            }
            if (is_null($data[$key])) {
                $data[$key] = '';
            }
        }
        return $data;
    }
}