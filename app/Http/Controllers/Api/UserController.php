<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Transformers\Api\RoleUserTransformer;
use App\Http\Requests\RoleUserRequest;
use Auth;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    //实例化Model
    private $user;

    function __construct(User $user)
    {
        $this->user = $user;
    }

    public function me(User $user)
    {
        return $this->response()->item(
            $user->getUser(Auth::id()),
            new RoleUserTransformer
        );
    }

    /*
    *分页显示所有用户，每页10
    */

    public function index(User $user)
    {
        return $this->response()->paginator(
            $user->getAllUser(),
            new RoleUserTransformer()
        );
    }

    /*
     * 添加用户UserRequest
     */
    public function store(RoleUserRequest $request)
    {
        $row = $this->user->doAddUser($request->all());
        if ($row == false) return $this->error(0, '添加失败');
        return $this->success('添加成功');
    }

    /*@param int $usre_id
    * @return Response
     * 密码初始化
     */
    public function resetPwd($id)
    {
        if (empty($id)) return false;
        $row = $this->user->resetPwd($id, '123456');
        if ($row > 0) {
            $info = array('msg' => '初始化成功', 'status' => 1);
        } else {
            $info = array('msg' => '初始化失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    //修改用户的禁用状态
    public function forbidden($id)
    {
        if ($id == 1) {
            return array('msg' => '不能冻结超级管理员', 'status' => 0);
        }
        $row = $this->user->forbidden($id);
        if ($row == false) {
            return $this->error(0);
        }
        return $this->success();
    }

    /*
    * @param Id
    * @return json
    * 获取指定用户信息
    */
    public function show($id)
    {
        return $this->response()->item(
            $this->user->getUser($id),
            new RoleUserTransformer()
        );
    }

    /*
     * @param array
     * @return json
     * 修改用户信息RoleUserRequest
     */
    public function update(Request $request, $uid)
    {
        $row = $this->user->doEditUserInfo($uid, $request->all());
        if ($row) {
            $info = array('msg' => '修改成功', 'status' => 1);
        } else {
            $info = array('msg' => '修改失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    /*@param int $id
    * @return json
     * 删除用户
     * */
    public function delete($id)
    {
        if ($id == 1) {
            return $this->error(0, "超级管理员不能删除");
        }
        $row = $this->user->delUser($id);
        if ($row > 0) {
            $info = array('msg' => '删除成功', 'status' => 1);
        } else {
            $info = array('msg' => '删除失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    /*用户修改自己密码
     *
     */
    public function updatePwd(RoleUserRequest $request)
    {
        return $this->response()->array($this->user->reset($request->all()));
    }

    //获取所有操作组
    public function group()
    {
        return $this->response()->array($this->user->getGroup());
    }

    //修改用户的权限组
    public function saverole(Request $request, $id)
    {
        return $this->response()->array($this->user->updateRule($request, $id));
    }

    //获取自己的操作组ID
    public function role($id)
    {
        return $this->response()->array($this->user->getRule($id));
    }
}