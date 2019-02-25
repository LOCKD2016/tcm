<?php
/**
 * Created by PhpStorm.
 * User: alex807666873
 * Date: 2017/5/5
 * Time: 18:43
 */
namespace App\Http\Controllers;

use App\Auth\LBWechat;
use App\Http\ApiControllers\BespeakController;
use App\Http\WxControllers\OrdersController;
use App\Models\AppUser;
use App\Models\Bespeak;
use App\Models\CliniqueRecipe;
use App\Models\Orders;
use App\Models\Schedule;
use App\Repository\BespeakRepository;
use App\Http\Controllers\Api\BespeaksController;
use App\Models\Doctor;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\Permission;
use App\Repository\CliniqueRepository;
use App\Repository\ConfigRepository;
use App\Repository\ExamRepository;
use App\Repository\MessageRepository;
use App\Repository\SMSCodeRepository;
use App\Repository\TemplateRepository;
use App\Services\JPushServices;
use App\Transformers\Api\Export\sendTransformer;
use App\Transformers\OrderTransformer;
use App\Util\Exp;
use App\Util\Tools;
use Illuminate\Support\Facades\Auth;
use App\Services\SoapServices;
use Illuminate\Http\Request;
use App\Repository\DoctorRepository;
use App\Repository\UserRepository;
use App\Repository\InquiryRepository;
use App\Services\TemplateServices;
use QrCode;
use DB;
use App\Http\PlatformControllers\DoctorController;

class ViewController extends Controller
{
    /**
     * 验证当前用户是否合法
     * @param $code
     * @return bool
     */
    public function verification($code)
    {
        if (isset($_COOKIE[$code])) {
            $doctor = Doctor::where('rand_code', $code)->first();
            if ($doctor) {
                return $doctor->id;
            }
            return false;
        }

    }

    /**
     * 获取web扫码提交的 问诊单数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function save_exam(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required|in:1,2,3',
            'option' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => $validator->errors()->first()]);
        }
        if ($doctor_id = $this->verification($request->code)) {
            $exam = Exam::where(['doctor_id' => $doctor_id, 'type' => $request->type])->first();

            $examRepository = new ExamRepository;

            $is_delete  =  false;
            //判断是否被答过题  没有就直接更改  否则软删除原数据
            if (ExamAnswer::where('exam_id', $exam->id)->first()) {

                //删除旧的问诊单 返回新的问诊单
                $exam = $this->softDelete_exam($exam);

                $is_delete = true;
            }

            $option = $examRepository->update_exam_option($exam, $request->option,$is_delete);

            if ($option) {
                return response()->json(['status' => 1, 'msg' => '修改成功', 'data' => $exam]);
            }
        }
        return response()->json(['status' => 0, 'msg' => '医生验证失败']);
    }

    /**
     * @param $exam
     * @return static
     */
    public function  softDelete_exam($exam){
        $data['title'] = $exam->title;
        $data['type'] = $exam->type;
        $data['doctor_id'] = $exam->doctor_id;
        $exam->delete();
        return Exam::create($data);
    }

    /**
     * 返回问诊单列表页
     * @param $code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retInquiry($code)
    {
        if ($this->verification($code)) {
            return view('inquiry.list');
        }
        abort(404, '验证失败,请扫码重试!');
    }

    /**
     * 返回问诊单详情
     * @param $code
     * @param $type
     * @return $this
     */
    public function inquiryDetail($code, $type)
    {
        \View::addExtension('html', 'php');

        return view('inquiry.detail');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInquiryDetail(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => $validator->errors()->first()]);
        }
        if ($doctor_id = $this->verification($request->code)) {
            $exam = Exam::where(['doctor_id' => $doctor_id, 'type' => $request->type])->first();

            if (empty($exam)) {
                //为医生添加一个空的问诊单1
                if ($request->type == 1) {
                    $title = '成人男';
                } elseif ($request->type == 2) {
                    $title = '成人女';
                } else {
                    $title = '儿童';
                }
                $exam = Exam::create(['doctor_id' => $doctor_id, 'title' => $title, 'type' => $request->type]);
            }
            $exam['options'] = $exam->options ;
            return response()->json(['status' => 1, 'msg' => 'ok', 'data' => $exam]);
        }

        return response()->json(['status' => 0, 'msg' => '医生验证失败']);

    }

    /**
     * 输出二维码
     * @return $this
     */
    public function inquiry()
    {
        $code = md5(time());
        $qrcode = QrCode::encoding('UTF-8')->size(500)->generate($code);
        return view('qrcode')->with(compact('qrcode', 'code'));
    }

    /**
     * 轮询获取当前扫码的医生
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function polling(Request $request)
    {
        if ($request->has('code')) {
            $doctor = Doctor::where('rand_code', $request->code)->first();
            if ($doctor) {
                return response()->json(['id' => $doctor->id, 'status' => 1]);
            }
        }

        return response()->json(['status' => 0]);

    }

    public function index()
    {
        if (\App\Util\Tools::isWeChatBrowser()) {
            return redirect('/wechat/index');
        }
        if (Auth::guest()) {
            return view('auth.login');
        }
        return redirect('/admin/index');
    }

    public function admin()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name', 'id')->toArray();
        if (!count($roles) && $user->user_id != 1) {
            abort(403, 'Unauthenticated.');
        }

        if ($user->user_id == 1 || in_array('admin', $roles)) {
            $nav = $this->customMenu();
        } else {
            $permissions = Permission::leftJoin('permission_role as r', 'r.permission_id', 'permissions.id')
                ->whereIn('r.role_id', array_keys($roles))
                ->distinct()->pluck('permissions.id')->toArray();
            //dd($permissions);
            $nav = $this->customMenu(1, $permissions);
        }
        //dd($nav);
        return view('admin.index', [
            'user' => $user,
            'nav' => $nav
        ]);
    }

    /**
     * 筛选菜单
     * @param int $handle
     * @param array $option
     * @return array
     */
    public function customMenu($handle = 0, array $option = [])
    {
        $menu = [
            0 => [
                'name' => '人员管理',
                'url' => '',
                'child' => [
                    ['id' => 1, 'name' => '患者列表', 'url' => '/app_users/1'],
                    ['id' => 5, 'name' => '医生管理', 'url' => '/doctor/1'],
                    ['id' => 11, 'name' => '客服管理', 'url' => '/service/1'],
                    ['id' => 38, 'name' => '划价收费', 'url' => '/charge_price/1'],
                ]
            ],
            1 => [
                'name' => '财务管理',
                'url' => '',
                'child' => [
                    //['id' => 85, 'name' => '商城订单', 'url' => '/shop_deal/1'],
                    ['id' => 14, 'name' => '预约订单', 'url' => '/drug_manage/1'],
                    ['id' => 14, 'name' => '药费订单', 'url' => '/drug_medicinal/1'],
//                    ['id' => 189, 'name' => '充值订单', 'url' => '/drug_pay/1']
                ]
            ],
            2 => [
                'name' => '数据管理',
                'url' => '',
                'child' => [
                    ['id' => 23, 'name' => '经营统计', 'url' => '/count_manage/1'],
                    ['id' => 28, 'name' => '患者统计', 'url' => '/count_family'],
                    ['id' => 29, 'name' => '医师统计', 'url' => '/count_doc/1'],
                    ['id' => 21, 'name' => '医师收入统计', 'url' => '/count_income/1'],
                    ['id' => 20, 'name' => '疗效统计', 'url' => '/count_curative_detail/1'],
                    //['id' => 202, 'name' => '商城统计', 'url' => '/count_mall'],
                    //['id' => 204, 'name' => '方案统计', 'url' => '/count_lnquiry'],
                ]
            ],
            3 => [
                'name' => '内容管理',
                'url' => '',
                'child' => [
                    //['id' => 86, 'name' => '商城发货', 'url' => '/send_list/1'],
                    ['id' => 15, 'name' => '药品发货', 'url' => '/send_recipe/1'],
                    //['id' => 130, 'name' => '问诊单', 'url' => '/lnquiry_list/1'],
                    //['id' => 124, 'name' => '题库', 'url' => '/question_list/1'],
                    //['id' => 138, 'name' => '方案处理', 'url' => '/proposed_law/1'],
                    ['id' => 40, 'name' => '诊疗管理', 'url' => '/chat_admin/1'],
                    ['id' => 43, 'name' => '评价管理', 'url' => '/comment_admin/1'],
                ]
            ],
//            4 => [
//                'name' => '优惠码管理',
//                'url' => '',
//                'child' => [
//                    ['id' => 94, 'name' => '优惠码列表', 'url' => '/promocode_list/1'],
//                    ['id' => 92, 'name' => '优惠码添加', 'url' => '/promocode_add'],
//                    ['id' => 97, 'name' => '活动管理', 'url' => '/promocode_mobile/1'],
//                ]
//            ],
            5 => [
                'name' => '系统设置',
                'url' => '',
                'child' => [
                    ['id' => 53, 'name' => '管理员列表', 'url' => '/adm_user/1'],
                    ['id' => 64, 'name' => '权限管理', 'url' => '/adm_pri'],
                    //['id' => 226, 'name' => '数据同步', 'url' => '/adm_sync'],
                    ['id' => 74, 'name' => '登录日志', 'url' => '/adm_log/1'],
                    //['id' => 0, 'name' => '接口日志', 'url' => '/adm_field/1'],
                    //['id' => 241, 'name' => '常见疾病', 'url' => '/adm_disease'],
                    ['id' => 28, 'name' => '诊所管理', 'url' => '/clinique'],
//                    ['id' => 247, 'name' => '疾病管理', 'url' => '/disease_admin/1'],
                    ['id' => 30, 'name' => '科室管理', 'url' => '/section_admin/1'],
                    ['id' => 45, 'name' => '协议管理', 'url' => '/agreement'],
                    ['id' => 77, 'name' => '药品管理', 'url' => '/medicinal_type/1'],
                    ['id' => 48, 'name' => '系统问诊单', 'url' => '/exam'],
                    ['id' => 24, 'name' => '轮播图', 'url' => '/slider'],
                    ['id' => 0, 'name' => '客服手机', 'url' => '/telephone'],
                    ['id' => 22, 'name' => '聊天管理', 'url' => '/message_list'],
                ]
            ],
        ];

        foreach ($menu as $k => $v) {
            if ($handle) {
                foreach ($v['child'] as $kk => $vv) {
                    if ($vv['id'] != 0 && !in_array($vv['id'], $option)) {
                        unset($menu[$k]['child'][$kk]);
                    }
                }
            }
            if (count($menu[$k]['child'])) {
                $menu[$k]['url'] = reset($menu[$k]['child'])['url'];
            }
            // else
            //unset($menu[$k]);

            ///dd($menu,$option);
        }

        return $menu;
    }

    public function sign(Request $request)
    {
        if (Auth::guard('wx_user')->id()) {
            if(session('preurl')){
                $pre_url = session('preurl');
                $request->session()->forget('preurl');
                return response()->redirectTo($pre_url);
            }else{
                return response()->redirectTo('/wechat/index');
            }
        }
        return view('wechat.index');
    }

    public function wechat()
    {
        return view('wechat.index');
    }

    public function server()
    {
        if (env('WECHAT_AUTH')) {
            $options = array(
                'appid' => config("wechat.app_id"), //填写高级调用功能的app id
                'appsecret' => config("wechat.secret") //填写高级调用功能的密钥
            );
            $weObj = new LBWechat($options);
            //$service = new Yar_Server($weObj);
            //$service->handle();
        }
    }

    public function dotest()
    {
        return $this->daoru();
        //return AppUser::find(1)->getCodeDateByCliniqueId(1);
//        $client = new JPush(
//            config('services.ajpush.appKey'),
//            config('services.ajpush.secret')
//        );
//        $client->push()
//            ->setPlatform('all')
//            ->addAllAudience()
//            ->setNotificationAlert('Hello, JPush(Just for a test!)')
//            ->send();
    }

    public function Template()
    {
        $bespeak_id = 84;
        $bespeak = (new BespeakRepository())->get_detail($bespeak_id); // 预约

        $doctor = (new DoctorRepository())->get_doctor_by_id($bespeak->doctor_id);//医生

        $appUser = (new UserRepository())->get_detail_by_id($bespeak->user_id);//用户

        $inquiry = (new InquiryRepository())->get_detail_by_bespeak_id($bespeak_id);//标准问诊单

        $openid = (new UserRepository())->get_user_wechat_openid($bespeak->user_id);


        if (!$openid) return $this->error(500, '系统错误，获取患者微信信息失败');

        $data = [
            'first' => ['value' => '尊敬的用户您好，' . $doctor->name . '医师已接诊！', 'color' => '#173177'],
            'keyword1' => ['value' => '', 'color' => '#173177'],
            'keyword2' => ['value' => $doctor->name, 'color' => '#173177'],
            'keyword3' => ['value' => '在线咨询', 'color' => '#173177'],
            'keyword4' => ['value' => $appUser->realname, 'color' => '#173177'],
            'keyword5' => ['value' => $inquiry->disease, 'color' => '#173177'],
            'remark' => ['value' => '医生最多等待您三分钟，超时需重新挂号。', 'color' => '#173177']
        ];

        (new TemplateServices())->pushMsgTemplate($openid, config('app.url'), $data, 'OmX3Dg8bxTalJ1bAWyMny4WcfarUY4fp6xF93KczJxc');
        return [];
    }

    public function daoru()
    {

        return (new TemplateRepository())->send_goods_to_user_remind_message(Orders::find(37));

        return (new SMSCodeRepository())->send_message_customer_for_bespeak_is_pay(Bespeak::find(77)); //短信通知客服患者预约了
        dd((new Exp('sf','048070104682'))->query());die;

        $recipe_code_arr = CliniqueRecipe::where('user_id',1)->orderBy('RCP_DATE', 'desc')->groupBy('RCPCLASS_CODE_NO')->limit(2)->pluck('RCPCLASS_CODE_NO')->toArray();
        $list = CliniqueRecipe::where('user_id',1)->where('RCPCLASS_CODE_NO', $recipe_code_arr[0])->orderBy('RCP_DATE', 'desc')->get();

        (new MessageRepository())->send_clinique_recipe_message(24,$list);
        dd($list);

        $list = (new SoapServices())->getRecipe(['customer_code'=>'04c54490-b1ec-4b0b-97fa-19b25db85626','bespeak_code'=>'']);

        dd($list);
//        $list = DB::table('ashdkjahdahskldhlksahdadkadhaksdasdkda')->get()->toArray();
//        foreach ($list as $v){
//            $res = (new DoctorController(new DoctorRepository()))->save($v,(new CliniqueRepository()),(new ConfigRepository()));
//            if(!$res) continue;
//        }

//        $list = DB::table('schdule_add')->get();
//        foreach ($list as $k=>$v){
//            //查询医生id
//            $doctor = DB::table('doctor_clinique')->where('code', $v->customer_code)->first();
//            if(!$doctor) continue;
//
//            if(count($doctor)){
//                $is_exist = Schedule::where('code',$v->code)->first();
//                if(!count($is_exist)){
//                    $schdule = new Schedule();
//                    $data = [
//                        'clinique_id' => 8,
//                        'doctor_id' => $doctor->doctor_id,
//                        'start_time' => $v->start_time,
//                        'end_time' => $v->end_time,
//                        'code' => $v->code,
//                        'date' => date('Y-m-d', strtotime($v->start_time)),
//                    ];
//                    $schdule->create($data);
//                }
//
//            }
//
//        }
    }

}