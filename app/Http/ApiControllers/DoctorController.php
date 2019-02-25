<?php

namespace App\Http\ApiControllers;

use Illuminate\Http\Request;
use App\Repository\DoctorRepository;
use App\Transformers\UserTransformer;
use App\Transformers\DoctorTransformer;
use App\Transformers\DoctorCommentTransformer;
use App\Http\Requests\DoctorRestRequest;
use Illuminate\Contracts\Hashing\Hasher;
use App\Http\Requests\DoctorUpdateRequest;
use App\Http\Requests\DoctorRemarkRequest;
use App\Transformers\DoctorLeaveTransFormer;
use App\Http\Requests\DoctorRegisterRequest;
use App\Transformers\DoctorRemarkTransformer;
use App\Http\Requests\DoctorResetPasswordRequest;
use App\Http\Requests\DoctorForgetPasswordRequest;

/**
 * Class DoctorController
 * 医生控制器
 * @package App\Http\ApiControllers
 */
class DoctorController extends Controller
{
    /**
     * @var DoctorRepository
     */
    private $model;

    /**
     * DoctorController constructor.
     * @param $model
     */
    public function __construct(DoctorRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 注册
     * @param DoctorRegisterRequest $request [
     *      'mobile', //手机号
     *      'password',//密码
     *      'password_confirmation',//确认密码
     *      'code', //验证码
     * ]
     */
    public function register(DoctorRegisterRequest $request)
    {
        //本地创建医生
        $created = $this->model->create(array_merge($request->only(['mobile', 'password']),
            ['source' => '1', 'web' => 1, 'clinic' => 0]
        ));

        return $created ? $this->success($request->all(), '注册成功') : $this->error('500', '注册失败，请稍后重试');
    }

    /**
     * 重置密码
     * @param DoctorResetPasswordRequest $request [
     *      'mobile',
     *      'oldPassword',
     *      'password',
     *      'password_confirmation',
     * ]
     * @param Hasher $hasher
     */
    public function resetPassword(DoctorResetPasswordRequest $request, Hasher $hasher)
    {
        $doctor = $this->model->get_data_by_mobile($request->mobile);

        $check = $hasher->check($request->oldPassword . $doctor->salt, $doctor->getAuthPassword());

        if ($check) { //密码输入正确

            $password = bcrypt($request->password . $doctor->salt);

            $edit = $doctor->loadEditData(['password' => $password])->save();

            return $edit ? $this->success([], '密码修改成功') : $this->error(502, '密码修改失败');
        }

        return $this->error(403, '密码错误');
    }

    /**
     * 忘记密码
     * @param DoctorForgetPasswordRequest $request [
     *      'mobile',
     *      'code',
     *      'password',
     *      'password_confirmation',
     * ]
     */
    public function forgetPassword(DoctorForgetPasswordRequest $request)
    {
        $doctor = $this->model->get_data_by_mobile($request->mobile);

        $password = bcrypt($request->password . $doctor->salt);

        $edit = $doctor->loadEditData(['password' => $password])->save();

        return $edit ? $this->success([], '密码重置成功') : $this->error(502, '密码重置失败');
    }

    /**
     * 医生详情
     * @return \Dingo\Api\Http\Response
     */
    public function detail()
    {
        $detail = $this->model->get_login_doctor();

        return $this->response()->item($detail, new DoctorTransformer());
    }

    /**
     * 修改信息
     * @param DoctorUpdateRequest $request
     * @return mixed
     */
    public function edit(DoctorUpdateRequest $request)
    {
        $editData = array_filter($request->except(['timestamp']));

        if (!empty($editData)) {
            $doctor = $this->model->get_login_doctor();
            $doctor->loadEditData($editData)->save();
        }

        return $this->success($editData, "修改成功");
    }


    /**
     * 当前登录医生的用户列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function users(Request $request)
    {
        $lists = $this->model->get_login_doctor_user_list_by_search($request->name);


        return $this->response()->paginator($lists, new UserTransformer());
    }

    /**
     * 申请休息
     * @param DoctorRestRequest $request [
     *      'starTime',
     *      'endTime',
     *      'day',
     * ]
     * @return mixed
     */
    public function rest(DoctorRestRequest $request)
    {
        $leave = $this->model->leave_create(
            array_merge(
                $request->only(['start_time', 'end_time', 'day']),
                ['doctor_id' => \Auth::id(), 'status' => '0']
            )
        );

        return $leave ? $this->success($request->all(), '添加成功 审核中!') : $this->error(500, '添加失败，请稍后重试');
    }

    /**
     * 休息列表
     * @return \Dingo\Api\Http\Response
     */
    public function restList()
    {
        $lists = $this->model->doctor_leave_list(\Auth::id());

        return $this->response()->paginator($lists, new DoctorLeaveTransFormer());
    }

    /**
     * 切换诊疗 状态
     * @param $status 0(关闭) || 1(开启)
     * @return mixed
     */
    public function toggleClinic($status)
    {
        $edit = \Auth::user()->loadEditData(['rest' => $status])->save();

        return $edit ? $this->success(['rest' => $status], '操作成功!') : $this->error(500, '操作失败');
    }

    /**
     * 获取医嘱列表
     * @return \Dingo\Api\Http\Response
     */
    public function remarkList()
    {
        $remark_list = $this->model->get_auth_remark_list();

        return $this->response()->paginator($remark_list, new DoctorRemarkTransformer());
    }

    /**
     * 添加医嘱
     * @param DoctorRemarkRequest $request
     * @return mixed
     */
    public function saveRemark(DoctorRemarkRequest $request)
    {
        //构建 医嘱
        $comment = $this->model->builderRemark(
            array_merge($request->only(['content']), ['doctor_id' => \Auth::id()]));

        //添加
        $add = \Auth::user()->remark()->save($comment);

        return $add ? $this->success([], '添加成功') : $this->error(500, '添加失败，请稍后重试');
    }

    /**
     * 获取登录医生的疗效统计
     * @return mixed
     */
    public function dataStatistics(Request $request)
    {
        $data = $this->model->get_doctor_data_statistics($request->all());
        return $this->success($data);
    }

    /**
     * 用户对医生的评论
     * @return mixed
     */
    public function comment(Request $request){

        $data = $this->model->get_doctor_data_statistics($request->all());
        return $this->response()->paginator($data, new DoctorCommentTransformer());

    }

    /**
     * 医生排班
     * @return mixed
     */
    public function schedules(){
        return $this->success($this->model->test());

    }


}
