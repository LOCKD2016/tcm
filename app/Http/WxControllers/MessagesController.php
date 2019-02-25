<?php

namespace App\Http\WxControllers;

use App\Models\Clinic;
use App\Models\Clinique;
use App\Models\User;
use App\Models\UserWeixin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repository\ClinicRepository;
use App\Repository\BespeakRepository;
use App\Repository\MessageRepository;
use App\Transformers\MessageListTransFormer;

/**
 * 聊天消息
 * Class MessagesController
 * @package App\Http\WxControllers
 */
class MessagesController extends Controller
{

    /**
     * MessagesController constructor.
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->model = $messageRepository;
    }

    /**
     * 消息列表页
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->model->get_list_by_user(\Auth::id());

        return $this->response()->paginator($lists, new MessageListTransFormer());
    }

    /**
     * 消息已读
     * @param $list_id
     * @return mixed
     */
    public function read($list_id)
    {
        $read = $this->model->user_message_list_read($list_id);

        if ($read) {
            return $this->success(['list_id' => $list_id], '操作成功');
        }

        return $this->error(502, '操作成功');
    }

    /**
     * 获取列表页数据
     * @param Request $request
     * @param BespeakRepository $bespeakRepository
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function getMessageListData(BespeakRepository $bespeakRepository, $type, $id)
    {
        switch ($type) {
            case 'bespeak' : //预约
                $bespeak = $bespeakRepository->get_data_by_id($id);

                if ($bespeak) {
                    $doctor_id = $bespeak->doctor_id;
                }
                break;
            default:
                $doctor_id = '';
        }


        if (!isset($doctor_id) || empty($doctor_id)) {
            return $this->error(404, '列表不存在');
        }

        $message_list = $this->model->get_lists_by_user_and_doctor(\Auth::id(), $doctor_id);

        return $this->response()->item($message_list, new MessageListTransFormer());
    }

    /**
     * 聊天页面的状态栏
     * @param ClinicRepository $clinicRepository
     * @param $list_id
     * @return mixed
     */
    public function statusBar(Request $request,ClinicRepository $clinicRepository, BespeakRepository $bespeakRepository,$list_id)
    {
        $input = $request->all();

        //获取聊天列表
        $message_list = $this->model->get_message_lists_by_id($list_id);

        $user_id = \Auth::id();
        $user = UserWeixin::where('user_id',$user_id)->first();
        if ($user['is_ask'] == 0){
            Clinic::where('id', $message_list->clinic_id)->update(['ask_status' => 0]);
        }

        if (empty($message_list)) {
            return $this->error(404, '记录不存在');
        }

        //获取诊疗信息     //根据message_list表的clinic_id找到对应的clinic记录
        $clinic = $clinicRepository->get_data_by_id($message_list->clinic_id);

        if (empty($clinic)) {
            return $this->error(405, '未查询到诊疗信息');
        }


        /********************************************************************/
        // 微信聊天页面判断输入框是否关闭
        $message_status = $message_list->status;
        //获取追问状态
        $ask = $clinic->ask_status;
        //获取结束追问状态
        $end = $clinic->end_status;
        //$clinic->status 诊疗状态 0:诊疗未开始[比如门诊预约未到时间] 5:诊疗中 9:追问中 10:诊疗结束 12:结束追问
        //结束问诊-5 追问-复诊-10 结束追问-复诊-9 复诊-12
//        $ask = $visit = $clinic->status == 10; //如果诊疗结束了 默认能复诊和追问
        if ($clinic->status == 5){
            $status = true;
            $visit = false;
//            $ask = $ask;
//            $end = $end;
        }else if ($clinic->status == 9 || $clinic->status == 10|| $clinic->status == 12){
            $status = false;
            $visit = true;
        }

        if ($input['is_ask'] == 1){
            $params['ask_status'] = 0;
            $params['status'] = 9;
            $params['end_status'] = 2;
            $re = Clinic::where('id', $message_list->clinic_id)->update($params);
            $ask = $re['ask_status'];
            $end = $re['end_status'];
            $status = false;
            $user_id = \Auth::id();
            UserWeixin::where('user_id',$user_id)->update(['is_ask' => 0]);
        }

        //结束时间不为空 说明已经结束过问诊了 能复诊和追问的
//        if ($ask && !empty($clinic->end_time)) {
//
//            //如果走到这句话就说明现在是能复诊和追问的
//            if (Carbon::parse($clinic->end_time)->diffInDays() >= 48) { //超过48天了
//                $ask = $visit = false;//不能复诊 不能追问
//            } elseif (Carbon::parse($clinic->end_time)->diffInDays() >= 15) {
//                $ask = false;//能复诊 不能追问
//            }
//
//            if(Carbon::parse($clinic->end_time)->diffInMinutes()/60 >= 24){
//                $ask = false;//结束问诊超过24小时 不能追问
//            }
//
////            if($clinic->end_time)
////                $ask = false;
//
//        }


//        //根据字段ask_status判断用户是否有追问的机会，默认为1-有，追问一次以后变为0-没有
//        ($clinic->ask_status == 0) ? $ask=0 : $ask=1;
//        //给个状态来判断是否显示结束追问，注：另一个状态是结束问诊
//        ($clinic->end_status == 1) ? $end=1 : $end=2;
        //结束问诊按钮是否显示 只要诊疗状态不是已结束都是显示的(包括追问中)
//        $status = $clinic->status != 10 ? true : false;
//        if ($clinic->status == 9 || $clinic->status == 10){
//            $status = false;
//        }else{
//            $status = true;
//        }
        //点击追问的操作
//        if ($input['is_ask'] == 1){
//            $cli = Clinic::find($message_list->clinic_id);
//            $cli->ask_status = 0;
//            $cli->status = 9;
//            $cli->end_status = 2;
//            $cli->save();
//            $ask = 0;
//            $status = false;
//            $end = 2;
//        }
        $bespeak = $bespeakRepository->get_detail($input['bespeakId']);

        $isVideo = false;
        if($bespeak && $bespeak->type ==3){
            $isVideo = true;
        }

        //诊疗结束 显示追问和复诊
        return $this->success([
            'clinic' => $status, //结束问诊按钮是否显示 只要诊疗状态不是已结束都是显示的(包括追问中)
            'ask' => $ask, //追问按钮是否显示 1:显示 0:不显示
            'visit' => $visit, //复诊按钮是否显示 1:显示 0:不显示
            'keyboard' => $message_status, //聊天框是否显示 1:可以聊天 0:不能聊天
            'end' => $end,//结束追问 1：默认状态-不显示 2：显示
            'isVideo' => $isVideo //视频
        ], '获取成功');
    }

    /**
     * 关闭诊疗
     * @param ClinicRepository $clinicRepository
     * @param $list_id
     * @return mixed
     */
    public function closeClinic(ClinicRepository $clinicRepository, $list_id)
    {
        //获取聊天列表
        $message_list = $this->model->get_message_lists_by_id($list_id);

        if (empty($message_list)) return $this->error(404, '当前聊天列表不存在');//结束的诊疗不对 不是最后一次诊疗

        //获取诊疗信息
        $clinic = $clinicRepository->get_data_by_id($message_list->clinic_id);

        if (empty($clinic)) return $this->error(500, '当前诊疗不能结束'); //有问题 正常情况不应该出现

        //发送系统消息
        $this->model->send_system_message($message_list->id, '用户已结束问诊');

        //修改聊天列表数据
        $message_list->loadEditData(['status' => '0'])->save(); //结束问诊了 不能聊天

        //没有结束过
        if (empty($clinic->end_time)) {
            //修改诊疗数据
            $update = $clinic->loadEditData([
                'status' => '10',
                'comment' => '1', //可以评论
                'end_time' => Carbon::now()
            ])->save();

            $bespeak = $clinic->bespeak;

            $bespeak->loadEditData(['status' => '25',])->save();

        } else {//已经结束过 追问 或者其他
            $update = $clinic->loadEditData(['status' => '10'])->save();
        }

        $cli = Clinic::find($message_list->clinic_id);
        $cli->status = 10;
        $cli->ask_status = 1;
        $cli->save();

        return $update ? $this->success([], '诊疗已结束') : $this->error(500, '操作失败 请稍后重试');
    }

    /**
     * 用户追问
     * @param ClinicRepository $clinicRepository
     * @param $list_id
     * @return mixed
     */
    public function ask(ClinicRepository $clinicRepository, $list_id)
    {
        //获取聊天列表
        $message_list = $this->model->get_message_lists_by_id($list_id);

        if (empty($message_list)) return $this->error(404, '当前聊天列表不存在');//结束的诊疗不对 不是最后一次诊疗

        //获取诊疗信息
        $clinic = $clinicRepository->get_data_by_id($message_list->clinic_id);

        if (empty($clinic)) return $this->error(500, '未查询到诊疗信息'); //有问题 正常情况不应该出现

        //发送系统消息
        $this->model->send_system_message($message_list->id, '用户发起追问');

        //修改聊天列表数据
        $message_list->loadEditData(['status' => '1'])->save(); //追问中 能聊天

        //修改诊疗数据
        $update = $clinic->loadEditData(['status' => '9', 'ask_time' => date('Y-m-d H:i:s')])->save(); //状态追问中 记录追问时间

        return $update ? $this->success([], '操作成功') : $this->error(500, '操作失败 请稍后重试');
    }
    /**
     * 结束追问访问的方法
     */
    public function endAsk(ClinicRepository $clinicRepository, Request $request)
    {
        $input = $request->all();
        $list_id = $input['id'];
        $message_list = $this->model->get_message_lists_by_id($list_id);

        if (empty($message_list)) {
            return $this->error(404, '记录不存在');
        }

        //获取诊疗信息
        $clinic = $clinicRepository->get_data_by_id($message_list->clinic_id);

        if (empty($clinic)) {
            return $this->error(405, '未查询到诊疗信息');
        }

//        $cli = Clinic::find($message_list->clinic_id);
//        $cli->status = 12;
//        $cli->end_status = 1;
//        $cli->ask_status = 0;
//        $cli->save();
        $params['status'] = 12;
        $params['end_status'] = 1;
        $params['ask_status'] = 0;
        $cli = Clinic::where('id', $message_list->clinic_id)->update($params);


        $message_list->status = 0;
        $message_list->save();


        return $this->success($cli,'结束追问成功');


    }

}
