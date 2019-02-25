<?php
/**
 * Created by PhpStorm.
 * User: vming
 * Date: 16/10/28
 * Time: 上午2:57
 */

namespace App\OAuth;

use App\Util\Tools;
use RuntimeException;
use Illuminate\Contracts\Hashing\Hasher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Laravel\Passport\Bridge\User;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\Exception\OAuthServerException;
class UserRepository implements UserRepositoryInterface
{
    /**
     * The hasher implementation.
     *
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;

    /**
     * Create a new repository instance.
     *
     * @param  \Illuminate\Contracts\Hashing\Hasher  $hasher
     * @return void
     */
    public function __construct(Hasher $hasher)
    {
        $this->hasher = $hasher;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        if (is_null($model = config('auth.providers.appusers.model'))) {
            throw new RuntimeException('Unable to determine user model from configuration.');
        }
        $service = Request::get("loginType");
        if($service){
            $user = Socialite::driver($service)->stateless()->user();
            $platform = app('App\Models\User'.ucfirst($service));
            $user = $platform->login($user);
            if(!$user){
                return ;
            }
        }else{
            if (method_exists($model, 'findForPassport')) {
                $user = (new $model)->findForPassport($username);
            } else {
                $user = (new $model)->where('mobile', $username)
                    ->first();
            }
            //添加判断是否拉黑 （禁网诊）
            if (! $user || ! $this->hasher->check($password.$user->salt, $user->password)) {
                return array('code'=>100,'msg'=>'账号或密码错误');
            }

            if($user->blacklist ==2){
                return array('code'=>100,'msg'=>'很抱歉，您的在线咨询账号已被停用，如有疑问请联系010-64300955');
            }
        }

        //throw OAuthServerException::invalidCredentials();
        $im_token=Tools::getChatToken() ;
        $user->im_token=$im_token;
        $user->save();
        return new User($user->getAuthIdentifier());
    }
}