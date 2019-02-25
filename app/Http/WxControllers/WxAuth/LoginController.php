<?php

namespace App\Http\WxControllers\WxAuth;

use App\Http\Controllers\Controller;
use App\Models\UserWeixin;
use App\Util\Tools;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AppUser;
class LoginController extends Controller
{

	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/wechat/';


	protected function guard()
	{
		return Auth::guard('wx_user');
	}


	public function username()
	{
		return 'mobile';
	}

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $data = $request->all();
        $user_id = AppUser::where('mobile', $data['mobile'])->value('id');
        if(!$user_id){
            return \Response::json([
                'status'=>0,
                'msg'=>'请注册'
            ]);
        }
        $openid = UserWeixin::where('user_id', $user_id)->value('openid');
        if($openid != $request->cookie('openid')){
            return \Response::json([
                'status'=>0,
                'msg'=>'该手机号已绑定其他微信'
            ]);
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = $this->guard()->user();
        $user->im_token = Tools::getChatToken();
        $save = $user->save();
        return $this->authenticated($request, $user)
            ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return \Response::json([
            'status'=>0,
            'msg'=>'账号或者密码错误'
        ]);
    }

	/**
	 * The user has been authenticated.
	 * 写入登录日志
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		return \Response::json([
			'status'=>1,
			'url'=>$this->redirectTo,
            'user'=>session('user'),
            'wxUser'=>$user
		]);
	}
}
