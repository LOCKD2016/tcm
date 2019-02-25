<?php

namespace App\Http\WxControllers;

use App\Transformers\ClinicTransformer;
use Carbon\Carbon;
use App\Repository\ClinicRepository;
use App\Repository\MessageRepository;

/**
 * Class ClinicController
 * @package App\Http\WxControllers
 */
class ClinicController extends Controller
{
    /**
     * @var Clinic
     */
    protected $model;

    /**
     * ClinicController constructor.
     * @param ClinicRepository $clinicRepository
     */
    public function __construct(ClinicRepository $clinicRepository)
    {
        $this->model = $clinicRepository;
    }

    /**
     * 关闭诊疗
     * @param MessageRepository $messageRepository
     * @param $clinic_id
     * @return mixed
     */
    public function close(MessageRepository $messageRepository, $clinic_id)
    {
        $clinic = $this->model->get_data_by_id($clinic_id);

        if (empty($clinic)) return $this->error(404, '诊疗不存在');

        $update = $clinic->loadEditData(['status' => '10', 'end_time' => Carbon::now()])->save();

        if ($update) {
            $msg_list = $messageRepository->get_message_lists_by_clinic_id($clinic->id);

            if (empty($msg_list)) return $this->success([], '诊疗已结束,但未关闭聊天');//结束的诊疗不对 不是最后一次诊疗

            //发送系统消息
            $messageRepository->send_system_message($msg_list->id, '用户已结束问诊');

            //修改聊天列表数据
            $msg_list->loadEditData(['status' => '0']); //结束问诊了 不能聊天

            return $this->success([], '诊疗已结束');
        }

        return $this->error(500, '操作失败,请稍后重试');
    }

    /**
     * 文件描述 获取未评论的诊疗列表
     * Created on 2018/1/8 13:11
     * Create by zhoupeng
     */
    public function getUncommentedLists()
    {
        $list = $this->model->get_list_by_user_without_comments();
        return $this->response()->paginator($list,new ClinicTransformer());
    }

    /**
     * desc 获取诊疗详情
     * Created on 2018/1/8 18:24
     * Create by zhoupeng
     * @param $clinic_id
     */
    public function detail($clinic_id)
    {
        $detail = $this->model->get_data_by_id($clinic_id);
        return $this->response()->item($detail, new ClinicTransformer());
    }
}
