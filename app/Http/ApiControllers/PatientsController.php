<?php
namespace App\Http\ApiControllers;

use App\Models\CommonCard;
use App\Models\DoctorGroup;
use Illuminate\Http\Request;
use App\Repository\App\DoctorRepository;
use App\Repository\App\PatientRepository;
use App\Repository\App\SubscribeRepository;
use App\Transformers\APP\PatientsInfoTransformer;
use App\Transformers\APP\InquiryRecordTransformer;
use App\Transformers\APP\PatientsGroupTransformer;
use App\Transformers\APP\PatientsListsTransformer;
use App\Transformers\APP\PatientsGroupListsTransformer;


/**
 * 患者
 * Class PatientsController
 * @package App\Http\ApiControllers
 */
class PatientsController extends Controller
{
    /**
     * @var DoctorRepository
     */
    protected $doctor;

    /**
     * @var PatientRepository
     */
    protected $patients;

    /**
     * @var SubscribeRepository
     */
    protected $subscribeRepository;

    /**
     * PatientsController constructor.
     * @param PatientRepository $patients
     * @param DoctorRepository $doctor
     */
    public function __construct(PatientRepository $patients, DoctorRepository $doctor, SubscribeRepository $subscribeRepository)
    {
        $this->patients = $patients;
        $this->doctor = $doctor;
        $this->subscribeRepository = $subscribeRepository;
    }

    /**
     * 获取患者列表
     * @param Request $request
     */
    public function lists(Request $request, $group_id = 0)
    {
        $data = $this->patients->get_auth_patients_page_by_search($request->all());

        return $this->response()->paginator($data, new PatientsListsTransformer($group_id));
    }

    /**
     * 患者信息
     * @param $id
     * @param int $subscribe_id [0=>获取患者信息,否则获取用户提交的普通问诊单和患者信息]
     * @return \Dingo\Api\Http\Response
     */
    public function info($id, $subscribe_id = 0)
    {
        $patient = $this->patients->get_patients_by_id($id);

        if ($subscribe_id) {
            $subscribeRepository = $this->subscribeRepository->get_order_id_by_subscribe_id($subscribe_id);
            if (!$subscribeRepository)
                return $this->error(102, '获取失败');

            $commonCard = $subscribeRepository->common_cards;

            if (!$commonCard)
                return $this->error(103, '获取失败');
            $patient['subscribe_card'] = [
                'is_first' => $subscribeRepository->is_first,
                'user_clinic_title' => $commonCard->user_clinic_title,
                'user_clinic_content' => $commonCard->user_clinic_content,
            ];
        }
        if ($patient) {
            return $this->response->item($patient, new PatientsInfoTransformer($subscribe_id, $id));
        }

        return $this->error(101, '获取失败');
    }

    /**
     * 添加分组1
     * @param Request $request
     */
    public function addGroup(Request $request)
    {
        $validator = $this->doctor->verification_group_add_data($request->only('name'));

        if ($validator->fails()) { //验证失败
            return $this->error(101, $validator->errors()->first());
        }
        $res = $this->doctor->add_patients_group($request->name);

        if ($res) {

            return $this->success($res, '分组创建成功');
        }

        return $this->error(101, '创建失败');

    }

    /**
     * 修改 分组
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function editGroup(Request $request, $id)
    {
        $validator = $this->doctor->verification_group_add_data($request->only('name'));

        if ($validator->fails()) { //验证失败
            return $this->error(101, $validator->errors()->first());
        }

        $res = $this->doctor->edit_patients_group($id, $request->name);

        if ($res) {
            return $this->success($res, '修改成功');
        }

        return $this->error('101', '修改失败');
    }

    /**
     * 分组内添加组员
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function assignedGroup(Request $request, $group_id)
    {
        $arr = $request->ids;

        $group = $this->doctor->get_group_by_id($group_id);

        if ($group) {
            $this->doctor->group_add_patients($group, $arr);

            $this->doctor->sync_group_num($group_id);

            return $this->success([], '添加成功');
        }


        return $this->error('101', '添加失败');
    }

    /**
     * 获取分组内的成员
     * @param $id
     * @return mixed
     */
    public function getGroupPatients($id)
    {
        $group = $this->doctor->get_group_by_id($id);

        if ($group) {
            if ($res = $group->patients) {
                return $this->response()->collection($res, new PatientsGroupTransformer());
            }
        }

        return $this->error('101', '获取失败');
    }

    /**
     * 通过患者获取分组
     * @param $id 患者编号
     * @return mixed
     */
    public function getGroupByPatient($id)
    {
        $group = $this->patients->get_group_array_by_patients($id);

        $group = $this->handleGetGroupByPatient($group, $id);

        return $this->success($group);
    }

    /**
     * 处理用户的数据信息
     * @param $group
     * @param $id
     * @return mixed
     */
    public function handleGetGroupByPatient($group, $id)
    {
        if ($group) {
            foreach ($group as $k => $v) {
                $group[$k]['show'] = false;
                $group[$k]['fid'] = $id;
            }
        }
        return $group;
    }

    /**
     * 用户添加分组多个
     * @param Request $request ids => 分组的id数组
     * @param $id
     * @return mixed
     */
    public function addGroupByPatient(Request $request, $id)
    {
        $res = $this->doctor->add_group_by_patient($id, collect($request->ids)->toArray(), collect($request->name)->toArray());

        if ($res[0]) {

            $this->doctor->sync_group_num($res[1]);

            return $this->success(['doctor' => $this->handleGroupList(DoctorGroup::where('doctor_id',\Auth::id())->get(), $id), 'family' => $this->handleGetGroupByPatient($this->patients->get_group_array_by_patients($id), $id)], '操作成功');
        }

        return $this->success(['doctor' => $this->handleGroupList(DoctorGroup::where('doctor_id',\Auth::id())->get(), $id), 'family' => []], '操作成功');
    }

    /**
     * 获取分组列表
     * @return mixed
     */
    public function groupList($family_id = 0)
    {
        $groups = $this->doctor->get_group_List();

        return $this->response()->collection($groups, new PatientsGroupListsTransformer($family_id));
    }

    /**
     * 格式化医生分组的数据 判断当前用户是否在分组内
     * @param $data
     * @return mixed
     */
    public function handleGroupList($data, $id)
    {
        foreach ($data as $k => $v) {
            if ($v->patients->where('id', $id)->count()) {
                $data[$k]['show'] = true;
            } else {
                $data[$k]['show'] = false;
            }
            $data[$k] = collect($data[$k])->only(['id', 'name', 'show', 'num']);
        }
        return $data;
    }

    /**
     * 获取问诊记录列表
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function getInquiryRecordList($id)
    {
        $clinics = $this->patients->get_inquiry_record_by_id($id);
        if ($clinics) {
            return $this->response()->collection($clinics, new InquiryRecordTransformer);
        }

        return $this->error('101', '查询失败');
    }

    /**
     * 删除分组内就诊人接口
     * @desc
     *      1、查询用户分组
     *      2、删除分组内用户
     *      3、同步分组的num
     * @param $group_id :分组ID
     * @param $family_id :就诊人ID
     * @return mixed
     */
    public function deleteGroupPatients($group_id, $family_id)
    {
        //查询用户分组
        $group = $this->doctor->get_group_by_id($group_id);

        if ($group) {
            //删除分组内用户
            $this->doctor->group_delete_patients($group, $family_id);

            //同步分组的num
            $this->doctor->sync_group_num($group_id);

            return $this->success([], '删除成功');
        }

        return $this->error('101', '删除失败');
    }

}
