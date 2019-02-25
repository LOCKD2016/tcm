<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Transformers\Api\RoleTransformer;
use App\Http\Requests\RolesRequest;

class RoleController extends ApiController
{
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /*分页展示用户组
     * 2016-9-20 16:50  不完整版
     * */
    public function index()
    {
        return $this->response()->paginator(
            $this->role->getAll(),
            new RoleTransformer()
        );
    }

    /*获取指定用户组
     * @params id
     */
    public function show($id)
    {
        if ($id == 0) {
            return $this->response()->item($this->role->getOnes(),
                new RoleTransformer());
        }
        return $this->response()->item(
            $this->role->getOne($id),
            new RoleTransformer()
        );
    }

    /*删除用户组
     * 2016-9-20 16:50
     * */
    public function destroy($id)
    {
        if (in_array($id, [1])) {
            $info = array('msg' => '系统内置用户组无法删除', 'status' => 0);
            return $this->response()->array($info);
        }
        $row = $this->role->doDel($id);
        if ($row) {
            $info = array('msg' => '删除成功', 'status' => 1);
        } else {
            $info = array('msg' => '删除失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    /*
     * 添加用户组
     */
    public function store(RolesRequest $request)
    {
        return $this->response()->array($this->role->add($request->all()));
    }

    /*
     * 修改
     * @params int | id
     * @params array
     */
    public function update(RolesRequest $request, $id)
    {
        return $this->response()->array(
            $this->role->dosave($request->all(), $id)
        );
    }

}