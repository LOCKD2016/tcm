<?php

namespace App\Http\ApiControllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repository\ClinicRepository;
use App\Repository\MessageRepository;
use App\Repository\PrescriptionRepository;
use App\Transformers\MessageListTransFormer;

/**
 * 消息对话
 * Class MessagesController
 * @package App\Http\ApiControllers
 */
class MessagesController extends Controller
{
    /**
     * @var ExamRepository
     */
    public $model;

    /**
     * MessagesController constructor.
     * @param MessageRepository $messageRepository
     */
    public function __construct(MessageRepository $messageRepository)
    {
        $this->model = $messageRepository;
    }

    /**
     * 聊天记录列表
     * @return mixed
     */
    public function lists(Request $request)
    {
        $lists = $this->model->get_list_by_doctor($request->name,\Auth::id());

        return $this->response()->paginator($lists, new MessageListTransFormer());
    }

    /**
     * 消息已读
     * @param $list_id
     * @return mixed
     */
    public function read($list_id)
    {
        $read = $this->model->doctor_message_list_read($list_id);

        if ($read) {
            return $this->success(['list_id' => $list_id], '操作成功');
        }

        return $this->error(502, '操作成功');
    }

    /**
     * 通过编号获取聊天列表id的详情
     * @param $list_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($list_id)
    {
        $message_list = $this->model->get_message_lists_by_id($list_id);

        return $message_list ? $this->response()->item($message_list, new MessageListTransFormer()) : $this->error(404, '该列表不存在');
    }

    /**
     * 聊天页面的状态栏
     * @param ClinicRepository $clinicRepository
     * @param PrescriptionRepository $prescriptionRepository
     * @param $list_id
     * @return mixed
     */
    public function statusBar(ClinicRepository $clinicRepository, PrescriptionRepository $prescriptionRepository, $list_id)
    {
        //获取聊天列表
        $message_list = $this->model->get_message_lists_by_id($list_id);

        if (empty($message_list)) {
            return $this->error(404, '记录不存在');
        }

        //获取诊疗信息
        $clinic = $clinicRepository->get_data_by_id($message_list->clinic_id);

        if (empty($clinic)) {
            return $this->error(404, '未查询到诊疗信息');
        }

        //获取药方信息 prescription
        $prescription = $prescriptionRepository->get_data_by_clinic_id($message_list->clinic_id);

        //$clinic->status 诊疗状态 0:诊疗未开始[比如门诊预约未到时间] 5:诊疗中 9:追问中 10:诊疗结束
        return $this->success([
            'clinic' => $clinic->status != 10 ? true : false, //结束问诊按钮是否显示 只要诊疗状态不是已结束都是显示的(包括追问中)
            'prescribe' => empty($prescription) && ($clinic->status != 10), //开方按钮是否显示  true 没有开方可以开方|false 已经开了 不能开方
            'recipeId' => $prescription ? $prescription->id : 0, //药方编号
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

        if (empty($clinic)) return $this->error(500, '当前诊疗不能结束');//有问题 正常情况不应该出现

        //发送系统消息
        $this->model->send_system_message($message_list->id, '医生已结束问诊');

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

        return $update ? $this->success([], '诊疗已结束') : $this->error(500, '操作失败 请稍后重试');
    }
}
