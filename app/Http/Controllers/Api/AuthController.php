<?php

namespace App\Http\Controllers\Api;

use App\Models\Permission;
use App\Transformers\Api\AuthTransformer;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\AuthSaveRequest;
use Illuminate\Http\Request;

class AuthController extends ApiController
{
    private $auth;

    public function __construct(Permission $permission)
    {
        $this->auth = $permission;
    }

    public function getJson()
    {
        return $this->response()->array($this->auth->doGetJson());
    }

    //分页查询权限列表
    public function index()
    {
        return $this->response()->paginator(
            $this->auth->getAllAuth(),
            new AuthTransformer()
        );
    }

    //删除指定权限
    public function destroy($id)
    {
        $row = $this->auth->doDel($id);
        if ($row) {
            $info = array('msg' => '删除成功', 'status' => 1);
        } else {
            $info = array('msg' => '删除失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    //添加权限
    public function store(AuthRequest $request)
    {
        $row = $this->auth->doAdd($request->all());
        if ($row) {
            $info = array('msg' => '添加成功', 'status' => 1);
        } else {
            $info = array('msg' => '添加失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    /*@param int id=0 查询所有一级二级的权限
       @ id ！= 0返回其对应的父级名字
    */
    public function show($id)
    {
        if ($id == 0) {
            return $this->response()->array($this->auth->getOneAuth());
            // return $this->response()->item($this->auth->getOneAuth(), new AuthTransformer);
        } else {
            return $this->response()->array($this->auth->ThisAuth($id));
        }
    }

    //修改权限
    public function update(AuthSaveRequest $requests, $id)
    {
        $row = $this->auth->doUpdate($requests->all(), $id);
        if ($row) {
            $info = array('msg' => '修改成功', 'status' => 1);
        } else {
            $info = array('msg' => '修改失败', 'status' => 0);
        }
        return $this->response()->array($info);
    }

    public function getMyJson($id)
    {
        return $this->response()->array($this->auth->doGetMyJson($id));
    }

}

?>