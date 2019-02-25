<?php
namespace App\Http\PlatformControllers;

use App\Repository\UserRepository;
use App\Transformers\UserTransformer;
use App\Util\Tools;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @Auth: Nnn
 * @package App\Http\PlatformControllers
 */
class UserController extends Controller
{
    /**
     * @Auth: Nnn
     * @var
     */
    protected $model;

    protected $basePwd = '111111';

    /**
     * @Auth: Nnn
     * UserHealthController constructor.
     * @param $model
     */
    public function __construct(UserRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 多条件查询用户信息及体征信息
     * @param Request $request
     * {
     *      'customer_code' 泰和国医用户唯一标识
     *      'name' 姓名
     *      'mobile' 电话
     *      'idNo' 身份证号
     *
     * }
     */
    public function index(Request $request)
    {
        $lists = $this->model->get_platform_user_list($request->all());

        if(!count($lists))
        {
            return $this->error(1000020, '没有数据');
        }

        return $this->response()->paginator($lists, new UserTransformer());
    }

    /**
     * 患者新增
     * @param Request $request
     * {
     *      'customer_code': 对方系统患者编号 Y
     *      'mobile': 手机号  Y
     *      'realname': 真实姓名 Y
     *      'birthday': 生日 Y
     *      'sex': 性别 Y
     *      'idType': 证件类型 Y
     *      'idNo': 证件号码 Y
     *      'password': 密码 N
     *      'height': 身高 N
     *      'weight': 体重 N
     *      'province': 省份 N
     *      'city': 城市 N
     *      'area': 区县 N
     * }
     *
     */
    public function save(Request $request)
    {
        $data = $request->all();

        $validator = $this->UserValidator($data);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $user = $this->model->get_user_by_customer_code($data['customer_code']);

        if($user)
        {
            return $this->error(100005, '患者已存在');
        }

        $salt = str_random(6);
        $password = $request->password ?: $this->basePwd;

        $baseData = [
            'source' => 2, //患者来源 对方系统
            'password' => bcrypt($password . $salt),
            'salt' => $salt,
            'height' => $request->height ?: 0,
            'weight' => $request->weight ?: 0,
            'im_token' => Tools::getChatToken()
        ];

        $create = array_merge($request->only(['realname', 'mobile', 'sex', 'idType', 'idNo', 'customer_code','birthday','province', 'city', 'area']), $baseData);

        $save = $this->model->create($create);

        if($save)
        {
            return $this->success();
        }

        return $this->error(100005, '添加失败');

    }

    /**
     * 患者信息修改
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $data = $request->all();

        $validator = $this->UserEditValidator($data);

        if ($validator->fails())
        {
            return $this->error(100, $validator->errors()->first());
        }

        $customer = $data['customer_code'];

        unset($data['customer_code']);

        $user = $this->model->get_user_by_customer_code($customer);

        if(!$user)
        {
            return $this->error(100011, '患者不存在');
        }

        $update = $this->model->update_user_data($customer, $data);

        if($update)
        {
            return $this->success();
        }

        return $this->error(100000, '修改失败');

    }

    /**
     * 根据customer_code查询患者详情
     * @param $id
     */
    public function detail($id)
    {
        $detail = $this->model->get_user_by_customer_code($id);

        if(!$detail)
        {
            return $this->error(100020, '没有数据');
        }

        return $this->response()->item($detail, new UserTransformer());
    }

    /**
     * 根据customer_code删除患者
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $user = $this->model->get_user_by_customer_code($request->customer_code);

        if(!$user)
        {
            return $this->error(100000, '请输入正确的患者编号');
        }

        $destroy = $this->model->soft_delete_data($request->customer_code);

        if($destroy)
        {
            return $this->success();
        }

        return $this->error(100000, '操作失败');
    }

/*********************************************************************************验证************************************************************************/

    /**
     * 患者信息新增验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function UserValidator($data)
    {
        $validator = \Validator::make($data, [
            'customer_code' => 'bail|required|unique:app_users',
            'mobile' => 'bail|required|unique:app_users',
            'realname' => 'bail|required',
            'sex' => 'bail|required',
            'idType' => 'bail|required',
            'idNo' => 'bail|required',
            'birthday' => 'bail|required',
        ], [
            'customer_code.required' => '患者编号不能为空',
            'mobile.required' => '手机号不能为空',
            'mobile.unique' => '手机号已存在',
            'realname.required' => '患者姓名不能为空',
            'sex.required' => '患者性别不能为空',
            'idType.required' => '证件类型不能为空',
            'idNo.required' => '证件号码不能为空',
            'birthday.required' => '出生日期不能为空',
        ]);

        return $validator;
    }

    /**
     * 患者信息编辑
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function UserEditValidator($data)
    {
        $validator_data=[
            'customer_code' => 'bail|required',
        ];

        if( !empty($data['mobile']))
        {
            $validator_data['mobile']= 'bail|unique:app_users,mobile,'.$data['mobile'].',mobile';
        }

        $validator = \Validator::make($data, $validator_data, [
                'customer_code.required' => '患者编号不能为空',
                'mobile.unique' => '手机号已存在',
        ]);

        return $validator;
    }

}