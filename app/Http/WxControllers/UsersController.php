<?php

namespace App\Http\WxControllers;

use App\Models\AppUser;
use App\Models\Error_note;
use App\Models\UserWeixin;
use DB;
use App\Util\Tools;
use App\Auth\LBWechat;
use Illuminate\Http\Request;
use App\Repository\UserRepository;
use App\Repository\ClinicRepository;
use App\Transformers\UserTransformer;
use App\Transformers\DoctorTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Contracts\Hashing\Hasher;
use App\Http\Requests\UserRegisterOrLoginRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Class UsersController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class UsersController extends Controller
{
    /**
     * @Auth: kingofzihua
     * @var
     */
    protected $model;

    /**
     * 默认密码
     * @var string
     */
    protected $basePwd = '111111';

    /**
     * @Auth: kingofzihua
     * UsersController constructor.
     * @param $user
     */
    public function __construct(UserRepository $user)
    {
        $this->model = $user;
    }

    /**
     * 用户详情
     * @desc 有就根据编号查询 没有就查询登录用户的
     * @Auth: kingofzihua
     * @param string $user_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($user_id = '')
    {
        if (empty($user_id)) {
            $user = $this->model->get_auth_data();
        } else {
            $user = $this->model->get_data_by_id($user_id);
        }
        return $this->response()->item($user, new UserTransformer());
    }

    public function check(){
        if(Auth::guard('wx_user')->check()){
            $u = Auth::guard('wx_user')->user();
            $user = [
                'id'=>$u->id,
                'nickname'=>$u->nickname,
                'headimgurl'=>$u->headimgurl,
            ];
            return $this->success($user);
        }else{
            return $this->error('');
        }
    }

    /**
     * 判断用户的登录方式
     * 首先获取用户 查看用户是否存在
     * @desc
     * @param UserRegisterOrLoginRequest $request
     * @param Hasher $hasher
     * @return mixed
     */
    public function login(UserRegisterOrLoginRequest $request, Hasher $hasher)
    {
        $user = $this->model->get_data_by_mobile($request->mobile);

        if ($user) { //已经有啦
            if ($request->type == 'login_plain') { //普通登录

                $check = $hasher->check($request->password . $user->salt, $user->getAuthPassword());

                if (!$check) { //密码验证失败
                    return $this->error(403, "密码错误");
                }
            }

        } else {//没有；
            if ($request->type == 'login_quick') { //如果是快速登录,则要注册信息的

                return $this->register($request);
            } elseif ($request->type == 'login_plain') { //普通登录 不存在 要注册

                return $this->error(404, "用户不存在 请注册");
            }
        }
        if(!Tools::isTCMUserApp()){
            //获取用户的openid 查看是否已经存在？如果不存在就给他赋值上 如果存在了就判断下是否是当前的微信
            $weixinUser = $this->model->get_user_wechat_byuid($user->id);
            $wxUser = session('user');
            if (!$weixinUser) { //不存在，可能已经注册了，但是失败了，或者是说 同步过来的数据，只有信息，没有用微信登陆过
                if(!$wxUser || !isset($wxUser['openid']) || !isset($wxUser['unionid'])){
                    //return $this->error(404, "用户不存在 请注册");
                    return $this->error(404, "请在手机微信端打开登录");
                }
                $wxUser['avatar'] = $wxUser['headimgurl'];
                try {
                    $this->model->wechat_create(array_merge(['user_id' => $user->id,], $wxUser));
                } catch (QueryException $exception) {
                    return $this->error($exception->getCode(), '请在手机微信端打开登录');
                }
            }
            if ($wxUser&&$weixinUser->openid !== $wxUser['openid']) { //手机号不是一个
                return $this->error(403, "当前用户已绑定其他微信，如果不是本人操作，请联系客服人员！");
            }
        }
        //登录
        Auth::guard('wx_user')->loginUsingId($user->id);

        $im_token = Tools::getChatToken(); //更新下聊天的token
        $user->loadEditData(['im_token' => $im_token])->save();

        return $this->success(array_merge(['im_token' => $im_token], $request->all()), '操作成功');
    }

    /**
     * app微信登录
     * @param Request $request
     * @return mixed
     */
    public function wxLogin(Request $request)
    {
        $options = array(
            'appid'=>config("wechat_app.app_id"), //填写高级调用功能的app id
            'appsecret'=>config("wechat_app.secret") //填写高级调用功能的密钥
        );
        $weObj = new LBWechat($options);
        $token = $weObj->getOauthAccessToken();
        if(!$token){
            return $this->error(403, "登录失败");
        }
        $user = $weObj->getOauthUserinfo($token['access_token'],$token['openid']);
        $wxuser = UserWeixin::where('unionid',$user['unionid'])->first();
        session(['user'=>$user]);
        if(!$wxuser){
            return $this->error(404, "请绑定手机号");
        }
        Auth::guard('wx_user')->loginUsingId($wxuser->user_id);
        $user = AppUser::find($wxuser->user_id);
        //登录
        Auth::guard('wx_user')->loginUsingId($user->id);
        $im_token = Tools::getChatToken(); //更新下聊天的token
        $user->loadEditData(['im_token' => $im_token])->save();
        return $this->success(array_merge(['im_token' => $im_token], $request->all()), '操作成功');
    }

    public function logout(){
        session()->flush();
        return $this->success();
    }

    /**
     * 注册逻辑
     * @desc  获取用户数据，如果没有 则认为是
     * @param UserRegisterOrLoginRequest $request
     * @return mixed
     */
    public function register(UserRegisterOrLoginRequest $request)
    {
        DB::beginTransaction(); //开启事务

        //查询用户 没有就创建
        $user = $this->model->get_data_by_mobile($request->mobile) ?: $this->model->create([
            'mobile' => $request->mobile, 'password' => $request->password ?: $this->basePwd
        ]);
        if(Tools::isTCMUserApp()){
            //完善用户的信息 1.user_weixin 表中要存一下
            $wxUser = session('user');
            if(!$wxUser){
                $wxUser = ['headimgurl'=>'https://app.taiheguoyi.com/img/doctor_default.png', 'nickname'=>'泰和APP用户'.$user->id, 'sex'=>'0', 'city'=>'未知', 'province'=>'未知', 'country'=>'未知'];
            }else{//有微信登录信息，才完善
                $wxUser['avatar'] = $wxUser['headimgurl'];
                //如果没有openid就会报错
                try {
                    $this->model->wechat_app_create(array_merge(['user_id' => $user->id,], $wxUser));
                } catch (QueryException $exception) {
                    return $this->error($exception->getCode(), '请在手机微信端打开注册');
                }
            }
            //完善用户的信息 2.app_user 表
            $im_token = Tools::getChatToken();
            if(is_null($user->nickname)){
                $save = $user->loadEditData(array_merge(['im_token' => $im_token], array_only($wxUser,
                    ['headimgurl', 'nickname', 'sex', 'city', 'province', 'country']
                )))->save();
            }else{
                $save = $user->loadEditData(['im_token' => $im_token])->save();
            }
            if ($save) {
                DB::commit();
                //注册成功 =>登录
                Auth::guard('wx_user')->loginUsingId($user->id);
                return $this->success(array_merge(['im_token' => $im_token], $request->all()), '操作成功');
            } else {
                DB::rollBack();
                return $this->error(502, "操作失败，请稍后重试");
            }
        }else{//微信
            //获取微信的openid
            $openid = $this->model->get_user_wechat_openid($user->id);

            if ($openid) { //已经有openid 了 直接让他去登录，不用注册
                return $this->error(101, "该手机号已经注册过了");
            }
            //完善用户的信息 1.user_weixin 表中要存一下
            $wxUser = session('user');
            if(!$wxUser){
                return $this->error(101, "未获取到微信授权信息，注册失败");
            }
            $wxUser['avatar'] = $wxUser['headimgurl'];
            //如果没有openid就会报错
            try {
                $this->model->wechat_create(array_merge(['user_id' => $user->id,], $wxUser));
            } catch (QueryException $exception) {
                return $this->error($exception->getCode(), '请在手机微信端打开注册');
            }

            //完善用户的信息 2.app_user 表
            $im_token = Tools::getChatToken();

            $save = $user->loadEditData(array_merge(['im_token' => $im_token], array_only($wxUser,
                ['headimgurl', 'nickname', 'sex', 'city', 'province', 'country']
            )))->save();

            if ($save) {
                DB::commit();
                //注册成功 =>登录
                Auth::guard('wx_user')->loginUsingId($user->id);
                return $this->success(array_merge(['im_token' => $im_token], $request->all()), '操作成功');
            } else {
                DB::rollBack();
                return $this->error(502, "操作失败，请稍后重试");
            }
        }
    }

    /**
     * 修改信息
     * @Auth: kingofzihua
     * @param Request $request
     * @return  mixed
     */
    public function edit(Request $request)
    {
        $user = $this->model->get_auth_data()->update($request->all());

        return $user ? $this->success([], '修改成功') : $this->error('502', '修改失败');
    }

    /**
     * 判断是否完善用户信息
     * @return mixed
     */
    public function complete()
    {
        return \Auth::user()->complete() ? $this->success([], '用户信息已完善') : $this->error(404, '请先完善信息');
    }

    /**
     * 医生的列表
     * @return \Dingo\Api\Http\Response
     */
    public function doctor()
    {
        $doctor_list = $this->model->get_auth_doctor_lists();

        return $this->response()->paginator($doctor_list, new DoctorTransformer());
    }

    /**
     * 获取最后一次诊疗的医生
     * @param ClinicRepository $clinicRepository
     * @return mixed
     */
    public function getLastClinicDoctor(ClinicRepository $clinicRepository)
    {
        $clinic = $clinicRepository->get_last_clinic_data_by_user(\Auth::id());

        return $clinic ? $this->success(['doctor_id' => $clinic->doctor_id], '获取成功') : $this->error(404, '未找到诊疗信息');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function wxupload(Request $request)
    {
//        if (env('WECHAT_AUTH')) {
//            $wechat = new \Yar_Client('http://auth.vliang.com/lbbniu/weixin');
//        } else {
//            $wechat = new LBWechat([
//                'appid' => config("wechat.app_id"), //填写高级调用功能的app id
//                'appsecret' => config("wechat.secret") //填写高级调用功能的密钥
//            ]);
//        }
        $wechat = new LBWechat([
            'appid' => config("wechat.app_id"), //填写高级调用功能的app id
            'appsecret' => config("wechat.secret") //填写高级调用功能的密钥
        ]);
        $count = 0;
        upload:
        $content = $wechat->getMedia($request->get('serverId'));
        if($wechat->errCode == 40001){
            $wechat->resetAuth();
            $wechat->resetJsTicket();
            Error_note::create(['code'=>'40001', 'type'=>1, 'content'=>'刷新access_token', 'created_at'=>date('Y-m-d H:i:s')]);
            $content = $wechat->getMedia($request->get('serverId'));
        }
        if(empty($content)){
            if($count<1){
                $count++;
                goto upload;
            }
        }
        if ($content) {
            $dst = date('ymdhis') . mt_rand(1000, 9999) . '-dst.png';
            $path = public_path('image/' . $dst);
            $file_msg = file_put_contents($path, $content);
            $img_url = Tools::move_img_from_wx_to_qiniu($dst,$path);
            if($img_url)
                return $this->success($img_url);
        }
        return $this->error(100,$wechat->errCode."---".$wechat->errMsg);
    }



}
