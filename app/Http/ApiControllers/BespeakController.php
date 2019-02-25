<?php

namespace App\Http\ApiControllers;

use Carbon\Carbon;
use App\Repository\BespeakRepository;
use App\Repository\TemplateRepository;
use App\Transformers\BespeakTransformer;

/**
 * Class BespeakController
 * @package App\Http\ApiControllers
 */
class BespeakController extends Controller
{

    /**
     * @var BespeakRepository
     */
    protected $model;

    /**
     * BespeakController constructor.
     * @param Bespeak $bespeak
     * @param BespeakRepository $bespeakRepository
     */
    public function __construct(BespeakRepository $bespeakRepository)
    {
        $this->model = $bespeakRepository;
    }

    /**
     * 网络预约列表
     * @auth kingofzihua
     * @return \Dingo\Api\Http\Response
     */
    public function webList()
    {
        $lists = $this->model->get_web_lists_by_doctor_for_loading(\Auth::id());
        return $this->response()->paginator($lists, new BespeakTransformer());
    }

    /**
     * 门诊预约列表
     * @auth kingofzihua
     * @return \Dingo\Api\Http\Response
     */
    public function clinicList($date)
    {
        $lists = $this->model->get_today_clinic_lists_by_doctor_and_date(\Auth::id(), $date);

        return $this->response()->paginator($lists, new BespeakTransformer());
    }

    /**
     * 预约详情
     * @auth kingofzihua
     * @param $bespeak_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($bespeak_id)
    {
        $bespeak = $this->model->get_data_by_id($bespeak_id);

        if ($bespeak && $bespeak->doctor_id != \Auth::id()) { //如果有预约 但是预约里面的医生编号不是当前的

            return $this->error(403, '您没有权限获取当前预约信息');
        }

        return $this->response()->item($bespeak, new BespeakTransformer());
    }

    /**
     * 医生接诊
     * @Auth: kingofzihua
     * @param $bespeak_id
     * @return mixed
     */
    public function accept($bespeak_id)
    {
        $bespeak = $this->model->get_data_by_id($bespeak_id);

        if (empty($bespeak)) {
            return $this->error(404, '预约不存在！');
        }

        if ($bespeak->doctor_id != \Auth::id()) { //如果有预约 但是预约里面的医生编号不是当前的

            return $this->error(403, '您没有权限获取当前预约信息');
        }

        $edit = $bespeak->loadEditData(['status' => '10', 'take_time' => Carbon::now()])->save();

        (new TemplateRepository())->send_accept_template_to_user($bespeak_id); //给患者发送接诊成功模板消息

        return $edit ? $this->success([], '接诊成功') : $this->error(500, '系统错误');
    }

    /**
     * 医生拒绝接诊 用户显示转诊中
     * @param $bespeak_id
     * @return mixed
     */
    public function refuse($bespeak_id)
    {
        $bespeak = $this->model->get_data_by_id($bespeak_id);

        if (empty($bespeak)) {
            return $this->error(404, '预约不存在！');
        }

        if ($bespeak->doctor_id != \Auth::id()) { //如果有预约 但是预约里面的医生编号不是当前的

            return $this->error(403, '您没有权限获取当前预约信息');
        }

        $edit = $bespeak->loadEditData(['status' => '30'])->save();

        return $edit ? $this->success([], '接诊成功') : $this->error(500, '系统错误');
    }


}
