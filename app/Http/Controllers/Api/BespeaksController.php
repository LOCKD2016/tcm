<?php

namespace App\Http\Controllers\Api;

use App\Auth\LBWechat;
use App\Http\Controllers\PaginatorController;
use App\Models\AppUser;
use App\Models\Doctor;
use App\Models\Section;
use App\Models\Bespeak;
use App\Models\UserWeixin;
use App\Repository\BespeakRepository;
use App\Repository\JPushRepository;
use Illuminate\Http\Request;
use App\Transformers\Api\BespeakTransformer;
use Auth;

class BespeaksController extends ApiController
{
    /**
     * @var Doctor
     */
    protected $bespeak;

    protected $page = 10;

    /**
     * BespeaksController constructor.
     * @param Bespeak $bespeak
     */
    public function __construct(Bespeak $bespeak)
    {
        $this->bespeak = $bespeak;
    }

    /**
     *分页显示所有用户，每页20
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all()['search'];

        $bespeak = $this->bespeak
            ->bespeaktype($data['type'] ?? '')
            ->bespeakname($data['name'] ?? '')
            ->bespeakdoctorname($data['doc_name'] ?? '')
            ->bespeaktime($data['time'] ?? '')
            ->bespeakstatus($data['status'] ?? '')
            ->orderStatus($data['pay_status'] ?? '')
            ->orderDesc()
            ->paginate($this->page);

        return $this->response()->paginator($bespeak, new BespeakTransformer());
    }

    /**
     * 预约详情
     * @param $id
     */
    public function show($id, Request $request, Section $section, Doctor $doctor)
    {
        $data = $request->all();

        $bespeak = $this->bespeak->with('doctor', 'user')->find($id);

        //图片解码
        if (isset($bespeak->disease) && $bespeak->redundant_first == 0)
            $bespeak->disease = json_decode($bespeak->disease);

        $section = $section->all();

        $doctor = $doctor->querySections($data['section'] ?? '')->where(['status' => 1, 'web' => 1])->get();

        $doctors = PaginatorController::index($doctor, $request->page);

        return $this->response()->array(['bespeak' => $bespeak, 'section' => $section, 'doctor' => $doctors]);
    }

    /**
     * 门诊/网诊预约操作
     * @param $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $data = $request->all();

        $update = false;

        $bespeak = $this->bespeak->find($id);

        if (!$bespeak) {
            return $this->error(100, '该预约不存在');
        }

        $data['admin_id'] = Auth::id();

        $type = $data['type'];
        unset($data['type']);

        switch ($type) {
            case 'remark':
                $update = $this->bespeak->updateData($id, $data);
                break;
            case 'cancle':
                $update = $this->bespeak->updateData($id, $data);
                break;
            case 'suc_sub':
                return $this->referral($bespeak, $data['doctor_id']);

        }

        if (!$update) {
            return $this->error(101, '修改失败');
        }

        return $this->success(200, '修改成功');
    }

    /**
     *  转诊
     * @desc
     * @author Eric Chow
     * @DateTime 2018/3/5 14:39
     * @param $bespeak
     */
    public function referral($bespeak, $doctor_id)
    {
        if (isset($bespeak->status) && $bespeak->status != 5)
            return $this->error(403, '医生已接诊，或者已取消，转诊失败');
        if (isset($bespeak->doctor_id) && $bespeak->doctor_id == $doctor_id)
            return $this->error(403, '转诊医生与患者预约的医生不能是同一人');
        if (!isset($bespeak->user_id) || !$bespeak->user_id)
            return $this->error(403, '获取患者信息失败');
        if (!$doctor_id)
            return $this->error(101, '获取转诊医生信息失败');

        //判断转诊的医生，是否可以接诊
        $count = (new BespeakRepository())->count_now_web_clinic_num_by_user_and_doctor(AppUser::find($bespeak->user_id), $doctor_id);
        if ($count) {//当前用户 对当前医生只能预约一次
            return $this->error(403, '患者与转诊医生有未结束的预约');
        }

        // 更换预约医生信息
        $bespeak->doctor_id = $doctor_id;
        if (!$bespeak->save())
            return $this->error(101, '转诊失败');
        //给转诊医生发送极光推送
        (new JPushRepository())->remind_doctor_accept_clinic($doctor_id);// 通知医生接诊极光推送
        return $this->success(200, '转诊成功');
    }
}