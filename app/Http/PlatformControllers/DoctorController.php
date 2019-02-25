<?php

namespace App\Http\PlatformControllers;

use App\Http\Requests\DoctorSaveRequest;
use App\Models\Config;
use App\Models\Doctor;
use App\Repository\ConfigRepository;
use App\Transformers\DoctorTransformer;
use App\Repository\CliniqueRepository;
use App\Repository\DoctorRepository;
use App\Util\Tools;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use DB;

/**
 * Class DoctorController
 * @Auth: kingofzihua
 * @package App\Http\PlatformControllers
 */
class DoctorController extends Controller
{
    /**
     * @Auth: kingofzihua
     * @var
     */
    protected $model;

    /**
     * 注册医生的基础密码
     * @Auth: kingofzihua
     * @var
     */
    protected $basePwd = '111111';

    /**
     * 注册医生的头像
     * @Auth: kingofzihua
     * @var
     */
    protected $baseImage = 'https://tcm-pro.vmh5.com/img/doctor_default.png';

    /**
     * @Auth: kingofzihua
     * DoctorController constructor.
     * @param $model
     */
    public function __construct(DoctorRepository $model)
    {
        $this->model = $model;
    }

    /**
     * @Auth: kingofzihua
     * @param DoctorSaveRequest $request [
     *      index
     * ]
     */
    public function save(Request $request, CliniqueRepository $cliniqueRepository, ConfigRepository $configRepository)
    {
        if (!isset($request->clinique) || empty($request->clinique)) {
            return $this->error(1000021, '诊所编号不能为空');
        }

        //判断是否是新增医生绑定诊所
        $bindDoctor = $this->model->get_doctor_by_name_mobile_sex($request->all());

        if ($bindDoctor) {
            $clinique = $cliniqueRepository->get_data_by_code_with_create($request->clinique);

            $doctorClinique = DB::table('doctor_clinique')->where(['doctor_id' => $bindDoctor->id, 'clinique_id' => $clinique->id])->first();

            if ($doctorClinique && $doctorClinique->code != $request->customer_code) {
                return $this->error(1000032, '同一诊所绑定医生编号不同');
            }

            if (!$doctorClinique) {
                $bindDoctor->cliniques()->attach($bindDoctor->id, ['clinique_id' => $clinique->id, 'code' => $request->customer_code]);
            }

            return $this->success();
        }

        $validator = $this->DoctorValidator($request->all());

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        //添加 医生的个人信息
        $salt = str_random(6);
        $password = $request->password ?: $this->basePwd;
        //头衔
        $title = $configRepository->get_title_id_by_name_or_save($request->title);

        if(!$title) {
            return $this->error(103, '医生头衔创建失败');
        }

        $baseData = [
            'salt' => $salt,
            'password' => bcrypt($password . $salt),
            'source' => '0',//医生来源
            'web' => '0', //是否开通网诊 1:开通 0:关闭
            'clinic' => '1',//是否门诊 1:开通 0:关闭
            'head_img' => $request->head_img ?: 'http://'.$_SERVER['SERVER_NAME'].'/img/doctor_default.png',
            'head_img_L' => $request->head_img ?: 'http://'.$_SERVER['SERVER_NAME'].'/img/doctor_default.png',
            'birthday' => $request->birthday ?? '',
            'length' => $request->length,
            'idType' => $request->idType ?? '',
            'idNo' => $request->idNo ?? '',
            'im_token' => Tools::getChatToken(),
            'clinic_monopoly' => $request->clinic_monopoly ?? 1,
            'title' => $title['id']
        ];
        $request->sex = $request->sex == 1?0:1;//性别转换 接口1男2女 本站0男2女
        $create = array_merge($request->only(['name', 'mobile', 'sex']), $baseData);

        //获取诊所信息
        DB::beginTransaction();
        $clinique = $cliniqueRepository->get_data_by_code_with_create($request->clinique);

        //医生不存在 创建
        $createDoctorId = $this->model->create_doctor($create);

        $doctorData = $this->model->get_doctor_by_id($createDoctorId);

        //添加关联诊所
        if ($doctorData) {
            $doctorData->cliniques()->attach($createDoctorId, ['clinique_id' => $clinique->id, 'code' => $request->customer_code]);
            DB::commit();
            return $this->success();
        }

        DB::rollBack();
        return $this->error(100000, '添加失败，请稍后再试');

    }

    /**
     * 医生编辑
     * @param Request $request
     * {
 *     customer_code   String        Y    医生编号
     * clinique        String        Y    诊所编号
     * length          int           Y    医生坐诊时间间隔（分）
     * name            String        Y    患者姓名
     * mobile          String        Y    患者手机号
     * sex             String        Y    性别 {男：1, 女：2}
     * birthday        String        N    出生日期（yyyy-mm-dd）
     * idType          int           N    证件类型{身份证;0,军官证;1,护照:2;台胞证:3;其他;4;无证件:9}
     * idNo            String        N    证件号码
     * head_img        String        N    医生头像
     * }
     */

    public function edit(Request $request, ConfigRepository $configRepository)
    {
        $data = $request->all();

        unset($data['clinique']);

        $validator = $this->DoctorEditValidator($data);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $doctor_id = $this->model->get_doctor_by_coustomer_code($request->customer_code);

        if (!$doctor_id) {
            return $this->error(100009, '医生不存在');
        }

        //头衔
        if ($data['title']) {
            $title = $configRepository->get_title_id_by_name_or_save($request->title);

            if(!$title) {
                return $this->error(103, '医生头衔创建失败');
            }

            $data['title'] = $title['id'];
        }

        unset($data['customer_code']);

        $updateDoctor = $this->model->update_doctor($doctor_id, $data);

        if (!$updateDoctor) {
            return $this->error(100010, '修改失败');
        }

        return $this->success();
    }

    /**
     * 多条件查询医生信息
     * @param Request $request
     */
    public function index(Request $request)
    {
        $lists = $this->model->get_platform_doctor_list($request->all());

        return $this->response()->paginator($lists, new DoctorTransformer());
    }

    /**
     * 删除医生信息
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $doctorClinique = DB::table('doctor_clinique')->where('code', $request->customer_code)->first();

        if (!$doctorClinique) {
            return $this->error(100000, '请输入正确的患者编号');
        }
        //获取医生数据
        $doctor = $this->model->get_doctor_by_id($doctorClinique->doctor_id);

        $destroy = DB::table('doctor_clinique')->where('doctor_id', $doctorClinique->doctor_id)->delete();

        if ($destroy) {
            //关闭医生的门诊状态
            $doctor->clinic = 0;
            $doctor->is_del = 1;
            $doctor->save();
            return $this->success();
        }

        return $this->error(100000, '操作失败');
    }

    /**
     * 医生添加验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function DoctorValidator($data)
    {
        $validator = \Validator::make($data, [
            'name' => 'bail|required',
            'mobile' => 'bail|required|unique:doctors',//手机号
            'sex' => ['required', Rule::in([0, 1]),],//性别
            'clinique' => 'bail|required',
//            'idType' => ['required', Rule::in([0, 1, 2, 3, 4, 9])],
//            'idNo' => 'bail|required',//证件号码
//            'birthday' => 'bail|required|date',//出生日期
        ], [
            'name.required' => '医生姓名不能为空',
            'mobile.required' => '医生手机号不能为空',
            'mobile.unique' => '医生手机号重复',
            'sex.required' => '医生性别不能为空',
            'clinique.required' => '诊所编号不能为空',
            'sex.in' => '医生性别不合法',
//            'idType.required' => '医生证件类型不能为空',
//            'idNo.required' => '医生证件号码不能为空',
//            'birthday.required' => '出生日期不能为空',
//            'birthday.date' => '出生日期类型不正确',
        ]);

        return $validator;
    }

    /**
     * 医生信息修改验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function DoctorEditValidator($data)
    {
        $validator = \Validator::make($data, [
            'customer_code' => 'bail|required',
        ], [
            'customer_code.required' => '医生编号不能为空',
        ]);

        return $validator;
    }


}