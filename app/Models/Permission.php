<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;
use Illuminate\Notifications\Notifiable;
use DB;
use Auth;
/**
 * App\Models\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @mixin \Eloquent
 */
class Permission extends EntrustPermission
{
    use Notifiable;
    protected $table = "permissions";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'name','pid' ,'display_name', 'description'
    ];

    //判断是否是管理员
    public  function  isAuth(){
        $uid = Auth::id();
        $user = DB::table('role_user')->where('user_id',$uid)->select('role_id')->get();
        $roleArr = array();
        if(count($user)>0){
            foreach ($user as $v){
                $roleArr[] = $v->role_id;
            }
            $set = array_intersect([1,2],$roleArr);
            return $set;
        }else{
            return array();
        }

    }
    //获取当前用户组的权限展示
    public function doGetMyJson($id){
        $myRolesId = DB::table('permission_role')->where('role_id',$id)->get();
        $rolesIdArr = array();
        foreach ($myRolesId as $v){
            $rolesIdArr[] = $v->permission_id;
        }
        $data = self::where('pid',0)->select('id','display_name')->get();
        foreach ($data as $v){
            $v['text'] = $v->display_name;
            $v['state'] = array('opened'=>true);
            unset($v->display_name);
            $childData = self::where('pid',$v->id)->select('id','display_name')->get();
            //unset($v->id);
            foreach ($childData as $cv){
                $cv['text'] = $cv->display_name;
                if(in_array($cv->id,$rolesIdArr)){
                    $cv['state'] = array("selected"=>true);
                }
                unset($cv->display_name);
            }
            if (count($childData) >0){
                $v['children'] = $childData;
            }
        }
        return $data;
    }
    //获取所有权限展示
    public function doGetJson(){
        $data = self::where('pid',0)->select('id','display_name')->get();
        foreach ($data as $v){
            $v['text'] = $v->display_name;
            $v['state'] = array('opened'=>true);
            unset($v->display_name);
            $childData = self::where('pid',$v->id)->select('id','display_name')->get();
            //unset($v->id);
            foreach ($childData as $cv){
                $cv['text'] = $cv->display_name;
            }
            if (count($childData) >0){
                $v['children'] = $childData;
            }
        }
        return $data;
    }
    //分页查询权限列表
    public function getAllAuth(){
        return self::orderBy('pid')->paginate(200);
    }
    /*
     * 创建事务递归删除子级目录
     */
    public function doDel($id){
        DB::beginTransaction();
        if($this->tranDel($id)){
            DB::commit();
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $info = self::find($id);
            $log['user_id'] = Auth::id();
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = Auth::user()->user_name;
            $log['operation_detail'] = '删除了'.$info['display_name'].'权限';
            $result = $operation->add($log);
            return true;
        }
        DB::rollback();
        return false;
    }
    /*
     * 递归删除权限和他的子级
     */
    public function tranDel($id){
        if(!self::where('id',$id)->delete()) return false;
        $data = self::where('pid',$id)->get();
        if($data){
            foreach($data as $k=>$v){
                $this->tranDel($v['id']);
            }
        }
        return true;
    }
    //添加权限
    public function doAdd($arr){
        $arr['created_at'] = date('Y-m-d H:i:s');
        //加入操作日志
        $operation = new OperationLog();
        $log = [];
        $log['user_id'] = Auth::id();
        $log['send_people'] = Auth::user()->user_name;
        $log['receive_people'] = Auth::user()->user_name;
        $log['operation_detail'] = '添加了'.$arr['display_name'].'权限';
        $result = $operation->add($log);
        return self::insertGetId($arr);
    }
    //获取所有的一级、二级的权限  阶梯展示
    public function getOneAuth($id=0,$num=-1,$data=array()){
        $arr = array();
        $arr = self::where('pid',$id)->get();
        if($arr){
            $num++;
            foreach($arr as $k=>$v){
                $arr[$k]['name'] = str_repeat('--',$num).$v['name'];
                $arr[$k]['display_name'] = str_repeat('--',$num).$v['display_name'];
                $data[] = $arr[$k];
                $data = $this->getOneAuth($v['id'],$num,$data);
            }
        }
        return $data;
    }
    //获取指定ID的父级信息
    public function ThisAuth($id){
        $arr =  self::select('id','name','display_name','description','pid')->find($id);
        if($arr['pid'] == 0){
            $arr['fname'] = '==第一级权限类==';
            return $arr;
        }else{
            $fArr = self::select('name')->where('id',$arr['pid'])->first();
            $arr['fname'] = $fArr['name'];
        }
        return $arr;
    }
    //修改指定的权限
    public function doUpdate($arr, $id){
        $auth = self::find($id);
        if(!$auth){
            return false;
        }
        unset($arr['id']);
        $fill = $this->getFillable();
        foreach($arr as $k=>$v){
            if(!in_array($k,$fill)) continue;
            $auth->{$k} = $v;
        }
        if($auth->save()!==false){
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = Auth::id();
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = Auth::user()->user_name;
            $log['operation_detail'] = '删除了'.$auth['display_name'].'权限';
            $result = $operation->add($log);
            return true;
        }else{
            return false;
        }
    }

}
