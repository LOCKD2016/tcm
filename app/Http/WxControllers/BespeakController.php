<?php

namespace App\Http\WxControllers;

use App\Models\AppUser;
use App\Models\Clinique;
use App\Repository\SMSCodeRepository;
use App\Repository\TemplateRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use App\Events\SaveUser;
use Illuminate\Http\Request;
use App\Services\SoapServices;
use App\Repository\DoctorRepository;
use App\Repository\BespeakRepository;
use App\Repository\InquiryRepository;
use App\Repository\CliniqueRepository;
use App\Repository\JPushRepository;
use App\Transformers\BespeakTransformer;
use App\Http\Requests\BespeakWebRequest;
use App\Http\Requests\BespeakClinicRequest;
use Illuminate\Support\Facades\Auth;


/**
 * 预约
 * Class BespeakController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class BespeakController extends Controller
{
    /**
     * @Auth: kingofzihua
     * @var
     */
    protected $model;

    /**
     * @Auth: kingofzihua
     * BespeakController constructor.
     * @param BespeakRepository $bespeak
     */
    public function __construct(BespeakRepository $bespeak)
    {
        $this->model = $bespeak;
    }

    /**
     * 列表
     * @Auth: kingofzihua
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->model->get_auth_user_lists();

        return $this->response()->paginator($lists, new BespeakTransformer());
    }

    /**
     * 预约详情
     * @param $bespeak_id
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function detail($bespeak_id)
    {
        $bespeak = $this->model->get_data_by_id($bespeak_id);

        if (empty($bespeak)) {
            return $this->error(404, '该预约不存在');
        }

        return $this->response()->item($bespeak, new BespeakTransformer());
    }

    /**
     * 网诊预约
     * @Auth: kingofzihua
     * @param Request $request [
     *      'redundant_first', //是否是初诊//1是 0否
     *      'redundant_in', //是否是大国医的 患者
     *      'disease',
     *      'desc',
     *      'doctor_id',
     * ]
     * @return mixed
     */
    public function webBespeak(BespeakWebRequest $request, InquiryRepository $inquiryRepository)
    {
        $count = $this->model->count_now_web_clinic_num_by_user_and_doctor(Auth::user(), $request->doctor_id);

        if ($count) {//当前用户 对当前医生只能预约一次
            return $this->error(403, '您有未结束的预约');
        }
        $disease = $request['disease'];
        if(!$request['redundant_first'])
            $disease = json_encode($request['disease']);
        //创建预约
        $bespeak = $this->model->create(
            array_merge(
                $request->only(['redundant_first', 'redundant_in', 'doctor_id']),
                [
                    'user_id' => Auth::id(),
                    'start_time' => Carbon::now(),
                    'type' => $request->local_type==3?'3':'0',//网诊
                    'status' => '5',//5待接诊
                    'disease' => $disease
                ]
            )
        );

        //创建标准问诊单
        $inquiry = $inquiryRepository->create(
            array_merge(
                $request->only(['desc']),
                [
//                    'type' => $request->redundant_first && $request->redundant_in,//是否是初诊//1是 0否
                    'type' => $request->redundant_first,//是否是初诊//1是 0否
                    'bespeak_id' => $bespeak->id,
                    'user_id' => Auth::id(),
                    'disease' => $disease
                ]
            )
        );

        (new JPushRepository)->remind_doctor_accept_clinic(intval($request['doctor_id']));// 通知医生接诊极光推送
        (new SMSCodeRepository())->send_message_customer_for_bespeak_is_pay($bespeak); //短信通知客服患者预约了
        return $bespeak && $inquiry ? $this->success(['bespeak_id' => $bespeak->id], '预约成功') : $this->error(500, '系统错误');
    }


    /**
     * 门诊预约
     * @param BespeakClinicRequest $request [
     *      'doctor_id', //医生编号
     *      'date', //日期
     *      'time', //时间
     *      'clinique_id', //诊所ID
     * ]
     * @return mixed
     */
    public function clinicBespeak(BespeakClinicRequest $request, DoctorRepository $doctorRepository, CliniqueRepository $cliniqueRepository, SoapServices $soapServices)
    {
        //获取医生详情
        $doctor = $doctorRepository->get_data_by_id($request->doctor_id);

        if (empty($doctor)) {
            return $this->error(404, '医生不存在！');
        }

        //获取医生的code
        $doctor_code = $doctorRepository->get_doctor_code_by_clinque_id($doctor, $request->clinique_id);

        if (empty($doctor_code)) {//咩有code
            return $this->error(403, '该医生在当前所选诊所并未开通坐诊服务！');
        }

        //获取用户的信息
        list($userCode, $cliniqueCode) = Auth::user()->getCodeDateByCliniqueId($request->clinique_id);

        $clinique = $cliniqueRepository->get_data_by_id($request->clinique_id);

        //用户没有在诊所注册
        if (!$userCode) {

            //判断用户信息是否完善
            if (!Auth::user()->complete()) {
                return $this->error(502, '请先去完善信息！');
            }

            //已完善 去注册
            $clinique = $cliniqueRepository->get_data_by_id($request->clinique_id);
            event(new SaveUser(Auth::user(), $clinique));

            //重新获取用户的信息
            list($userCode, $cliniqueCode) = \Auth::user()->getCodeDateByCliniqueId($request->clinique_id);

            if (!$userCode) { //应该已经注册上了 如果没有就是 auth 有缓存 或者是api 报错了 程序错误 找
                return $this->error(502, '用户没有在该诊所注册！');
            }
        }


        /********************一个小时只能预约 时间间隔人数 的限制*******************/

        //处理时间
        $date1 = date('Y-m-d', strtotime($request->date));
        $daystartDate = date('YmdHis', strtotime($date1));
        $dayendDate = date('YmdHis', strtotime($date1) + 3600 * 24);
        //请求接口查询数据
        $list = $soapServices->getScheduleDate($doctor_code, $doctor->name, $daystartDate, $dayendDate, $clinique->code);//return $list;

//        $schedule_time = [];
        $time = $request->time;
        $bespeak_hour_num = 60/$doctor->length;

        foreach ($list as $key => $value){
            for ($i=0; $i<count($value['ResourceList']); $i++){
                if ($time == date('H:i', strtotime($value['ResourceList'][$i]['STR_APPOINT_STARTTIME']))){
                    return $this->error(403,'泰和国医提醒您：设置的预约时间段内已有人员预约，请重新设置预约时间段!');
//                    $schedule_time[$i]['APPOINT_ENDTIME'] = $value['ResourceList'][$i]['STR_APPOINT_ENDTIME'];
//                    $schedule_time[$i]['APPOINT_STARTTIME'] = $value['ResourceList'][$i]['STR_APPOINT_STARTTIME'];
                }
            }

        }

//        return $this->success(['bespeak_id' => 12345], '准备支付');

        /****************************************************************/




        //处理时间
        $date = strtotime($request->date . ' ' . $request->time);
        $startDate = date('YmdHis', $date);
        $endDate = date('YmdHis', $doctor->length * 60 + $date);

        //通过接口进行预约
/*        $arr = [];//测试
        $arr['userCode'] = $userCode;
        $arr['realname'] = \Auth::user()->realname;
        $arr['doctor_code'] = $doctor_code;
        $arr['name'] = $doctor->name;
        $arr['startDate'] = $startDate;
        $arr['endDate'] = $endDate;
        $arr['cliniqueCode'] = $cliniqueCode;
        return $arr;*/
        $saveBespeak = $soapServices->saveBespeak($userCode, \Auth::user()->realname, $doctor_code, $doctor->name,  $startDate, $endDate, $cliniqueCode);

        //获取诊所信息
        $getCli = Clinique::where('code', 'GS_01')->first();

        if (!$saveBespeak['state']) { //预约失败
            return $this->error($saveBespeak['code'], $getCli['name'].'提醒您：'.$saveBespeak['message']);
//            return $this->error($saveBespeak['code'], '泰和大国医提醒您：'.$saveBespeak['message']);
        }

        //接口预约成功 创建预约
        $bespeak = $this->model->create(
            [
                'type' => '1',//门诊
                'status' => '10',//10待支付
                'user_id' => \Auth::id(),
                'doctor_id' => $request->doctor_id,
                'clinique_id' => $request->clinique_id,
                'bespeak_code' => $saveBespeak['data']['Bespeak_CODE'],
                'start_time' => Carbon::createFromTimestamp($date), //预约开始时间
                'end_time' => Carbon::createFromTimestamp($doctor->length * 60 + $date), //预约结束时间
            ]
        );
        return $bespeak ? $this->success(['bespeak_id' => $bespeak->id], '预约成功') : $this->error(500, '系统错误');
    }

    /**
     * 测试 接诊
     * 这个是为了打通 微信端接口弄到，到后面整体流程能走了就删掉
     * @param $bespeak_id
     * @return mixed
     */
    public function testAccept($bespeak_id)
    {
        $bespeak = $this->model->get_data_by_id($bespeak_id);

        if (!$bespeak) {
            return $this->error(404, '诊疗不存在');
        }

        $edit = $bespeak->loadEditData(['status' => '10'])->save();

        return $edit ? $this->success([], '接诊成功') : $this->error(500, '系统错误');
    }

    /**
     * 取消预约
     * @desc 门诊:
     *          1判断能否取消
     * @desc 网诊:
     *
     * …… md 忘了写啥了 草，待会再说吧
     * @param $bespeak_id
     * @return mixed
     */
    public function close($bespeak_id)
    {
        $soapServices = new SoapServices();
        $doctorRepository = new DoctorRepository();

        $bespeak = $this->model->get_data_by_id($bespeak_id);
        if (!$bespeak) {
            return $this->error(404, '诊疗不存在');
        }

        $appUser = (new UserRepository())->get_detail_by_id($bespeak->user_id);

        // 获取取消预约所需信息
        $doctor = $doctorRepository->get_data_by_id($bespeak->doctor_id);

        //获取医生的code
        $doctor_code = $doctorRepository->get_doctor_code_by_clinque_id($doctor, $bespeak->clinique_id);

        //重新获取用户的信息
        list($userCode, $cliniqueCode) = (new AppUser())->getCodeDateByCliniqueId($bespeak->clinique_id);



        //判断下 预约的类型
        if (isset($bespeak->type) && $bespeak->type == 1) { //门诊

            $cancel_msg = $soapServices->cancelBespeak($bespeak->bespeak_code, $userCode, $appUser->realname, $doctor_code, $doctor->name,  $bespeak->start_time, $bespeak->end_time, $cliniqueCode);
            if(isset($cancel_msg['state']) && $cancel_msg['state']==1){
                $edit = $bespeak->loadEditData(['status' => '35'])->save();
                (new TemplateRepository())->cancel_bespeak_message($bespeak);//取消预约模板消息通知用户
            }else{
                return $this->error(500, $cancel_msg['message']);
            }

        } else { //网诊
            $edit = $bespeak->loadEditData(['status' => '35'])->save();
        }

        return $edit ? $this->success([], '取消预约成功') : $this->error(500, '系统错误');
    }

    public function canWeb($doctor_id)
    {
        $count = $this->model->count_now_web_clinic_num_by_user_and_doctor(\Auth::user(), $doctor_id);

        if ($count) {//当前用户 对当前医生只能预约一次
            return $this->error(403, '您有未结束的预约');
        }

        return $this->success('您可以预约');
    }
}
