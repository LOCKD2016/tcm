<?php

namespace App\Http\Controllers\Api;

use App\Models\Disease;
use App\Models\DoctorDisease;
use App\Models\DoctorLeave;
use Auth;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Services\JPushServices;
use App\Http\Controllers\PaginatorController;
use App\Transformers\Api\DoctorTransformer;
use App\Transformers\Api\DoctorDetailTransformer;
use App\Transformers\Api\DoctorCountTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use PhpParser\Comment\Doc;

class DoctorController extends ApiController
{

    /**
     * @var Doctor
     */
    protected $doctor;

    protected $page = 10;

    /**
     * DoctorController constructor.
     * @param Doctor $doctor
     */
    public function __construct(Doctor $doctor)
    {
        $this->doctor = $doctor;
    }

    /**
     *分页显示所有用户，每页20
     * @param Doctor $doctor
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */

    public function index(Request $request)
    {
        $search = $request->all();

        $lists = $this->doctor
            ->doctorName($search['name'] ?? '')//医生姓名
            ->doctorMobile($search['mobile'] ?? '')//医生手机号
            ->status($search['status'] ?? '')//状态
            ->source($search['source'] ?? '')//来源
            ->isDelete()
            ->doctorOrderByDesc()
            ->paginate($this->page);

        return $this->response()->paginator($lists, new DoctorTransformer());
    }

    /**
     * 获取医师详情
     * @param $id
     */
    public function show($id)
    {
        $doctor = $this->doctor->findId($id);

        return $this->response()->item($doctor, new DoctorDetailTransformer());

    }

    /**
     * 修改医师信息
     * @param $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $data = $request->all();

        $doctor = $this->doctor->findId($id);

        if (!$doctor) {
            return $this->error(100, '该医生不存在');
        }

        $update = $this->doctor->updated_data_by_id($id, $data);

        if (!$update) {
            return $this->error(101, '修改失败');
        }

        return $this->success(200, '修改成功');
    }

    /**
     * 新增医生擅长
     * @param $id
     * @param Request $request
     */
    public function adDisease($id, Request $request)
    {

        $createData = [];

        foreach ($request->data as $key => $val) {
            $createData[$key][$request->type . '_id'] = $val;
            $createData[$key]['doctor_id'] = $id;
        }

        $insert = \DB::table('doctor_' . $request->type)->insert($createData);

        if ($insert) {
            return $this->success();
        }

        return $this->error(1000, '添加成功');
    }

    /**
     * 医生擅长删除
     * @param $doctor_id
     * @param $disease_id
     */
    public function delDisease($doctor_id, $disease_id, Request $request)
    {
        $delete = \DB::table('doctor_' . $request->type)->where(['doctor_id' => $doctor_id, $request->type . '_id' => $disease_id])->delete();

        if ($delete) {
            return $this->success();
        }

        return $this->error(100, '删除失败');
    }

    /**
     * 医生休息状态修改
     * @param $id
     * @param Request $request
     */
    public function doctorLeave($id, Request $request)
    {
        $leave = DoctorLeave::find($id);

        if (!$leave) {
            return $this->error(100, '该记录不存在');
        }

        $leave->status = $request->status;

        if ($leave->save()) {
            return $this->success();
        }

        return $this->error(101, '修改失败');
    }

    /**
     * 数据管理--医师统计
     * @param Request $request
     */
    public function doctorCount(Request $request)
    {
    	$data = $request->all();

        $doctors = $this->doctor
			->doctorName($data['name'] ?? '')
			->get();

		$startTime = $data['time']['startTime'] ?: '2018-01-01';
		$endTime = $data['time']['endTime'] ?: '2050-01-01';

        $data = [];
        //5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消
        foreach ($doctors as $key => $val) {
        	$bespeaks = collect($val->bespeaks)->where('created_at', '>', $startTime)->where('created_at', '<', $endTime);
			$prescription = collect($val->prescription)->where('created_at', '>', $startTime)->where('created_at', '<', $endTime);
            $data[$key]['doctor'] = $val->name; //医师
            $data[$key]['bespeaks'] = $bespeaks->count(); //预约人数
            $data[$key]['accept'] = $bespeaks->where('type', 0)->whereIn('status', [10, 15, 25])->count(); //接诊数
            //$data[$key]['accept'] = collect($val->bespeaks)->where('status', )->count(); //转诊数
            $data[$key]['clinic'] = $bespeaks->where('type', 1)->count(); //现场看诊数
            $data[$key]['web'] = $bespeaks->where('type', 0)->count(); //远程问诊数
            $data[$key]['medicine'] = $prescription->count(); //抓药数
            $data[$key]['tisane'] = $prescription->where('tisane', 1)->count(); //代煎量
            $data[$key]['express'] = $prescription->where('express', 1)->count(); //快递量
        }

        $data = PaginatorController::index($data, $request->page);

        return $this->success($data);

    }

    /**
     * 数据管理--医师疗效统计
     * @param Request $request
     */
    public function doctorComment(Request $request)
    {
        $search = $request->all();

        $doctors = $this->doctor
            ->with('comments')
            ->get();

        $data = [];

		$startTime = $search['time']['startTime'] ?: '2018-01-01';
		$endTime = $search['time']['endTime'] ?: '2050-01-01';

        foreach ($doctors as $key => $val) {
        	$comment = collect($val->comments)->where('created_at', '>', $startTime)->where('created_at', '<', $endTime);
            $data[$key]['name'] = $val->name;
            $data[$key]['total'] = $comment->count(); //总数
            $data[$key]['recovery'] = $comment->where('condition', 1)->count(); //痊愈
            $data[$key]['better'] = $comment->where('condition', 2)->count(); //明显好转
            $data[$key]['good'] = $comment->where('condition', 3)->count(); //好转
            $data[$key]['noChange'] = $comment->where('condition', 4)->count(); //没变化
        }

        $data = PaginatorController::index($data, $request->page);

        return $this->success($data);
    }

    /**
     * 医生收入统计
     * @param Request $request
     * 订单类型 1门诊 2网诊 3药方 4商品
     * 订单的状态 0 未支付 2正在支付 5已支付 9退款中 10已退款
     */
    public function doctorIncome(Request $request)
    {
        $doctors = $this->doctor
            ->doctorName($request->name ?? '')//医生姓名
            ->get();

        $startTime = $request->startTime ?: '2018-01-01';
        $endTime = $request->endTime ?: '2050-01-01';

        $data = [];

        foreach ($doctors as $key => $val) {
            $bespeakOrder = collect($val->bespeakOrders)->pluck('order')->where('created_at', '>', $startTime)->where('created_at', '<', $endTime);
            $medicineOrder = collect($val->prescriptionOrders)->pluck('order')->where('created_at', '>', $startTime)->where('created_at', '<', $endTime);

            $data[$key]['id'] = $val->id;
            $data[$key]['name'] = $val->name;
            $data[$key]['clinic'] = $bespeakOrder->where('status', '>', 1)->where('order_type', 1)->sum('amount') / 100;//门诊
            $data[$key]['web'] = $bespeakOrder->where('status', '>', 1)->where('order_type', 2)->sum('amount') / 100;//网诊
            $data[$key]['medicine'] = $medicineOrder->where('status', '>', 1)->where('order_type', 3)->sum('amount') / 100;//药方
            $data[$key]['refund'] = $bespeakOrder->where('status', '>', 3)->sum('refund_amount') / 100;//退款
        }

        $data = PaginatorController::index($data, $request->page);

        return $this->success($data);
    }

    /**
     * 新增医生擅长2
     * @param $id：医生id
     * @param Request $request
     */
    public function adDisease2($id, Request $request)
    {
        $params['doctor_id'] = $id;           /*****/
        if (empty($id)){
            return $this->error(1000,'添加失败');
        }
        if (empty($request->data)){
            return $this->error(1000, '请输入添加内容');
        }
        $input = $request->data;
        $createData = explode('，', $input);
        $disease = new Disease();
        foreach ($createData as $key=>$value){
            $obj = $disease->where('name', '=', $value)->first();
            if (empty($obj)){
                $obj = $disease->create(['name'=>$value]);
            }
            $params['disease_id'] = $obj->id;

            $re = DoctorDisease::where('disease_id', '=', $obj->id)->where('doctor_id', '=', $id)->first();
            if (!$re){
                $insert = DB::table('doctor_' . $request->type)->insert($params);
                if (!$insert){
                    return $this->error(1000, '添加'.$value.'失败');
                }
            }

        }

        return $this->success();


//        $createData = [];
//
//        foreach ($request->data as $key => $val) {
//            $createData[$key][$request->type . '_id'] = $val;
//            $createData[$key]['doctor_id'] = $id;
//        }

//        $insert = \DB::table('doctor_' . $request->type)->insert($createData);
//
//        if ($insert) {
//            return $this->success();
//        }
//
//        return $this->error(1000, '添加失败');
    }
    
    /**
     * 删除医生
     * 
     */
    public function deleteDoctor(Request $request)
    {
        $id = $request->id;
        $doctor = Doctor::find($id);
        $doctor->is_del = 1;
        $re = $doctor->save();
        if ($re){
            return $this->success($id, '删除成功');
        }else{
            return $this->error('删除失败');
        }
    }

}
