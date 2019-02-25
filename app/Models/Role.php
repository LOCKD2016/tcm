<?php

namespace App\Models;
use Illuminate\Pagination\Paginator;
use Zizaco\Entrust\EntrustRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use DB;
class Role extends  EntrustRole
{
    use Notifiable,SoftDeletes;
    protected $table = "roles";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable = [
        'name','description'
    ];
    //分页获取
    public function getAll(){
        return self::paginate(config("app:paginate"));
    }
    //删除
    public function doDel($id){
        $info = self::find($id);
        if(!$info) return false;
        $info->delete();
        if($info->trashed()){
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = Auth::id();
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = Auth::user()->user_name;
            $log['operation_detail'] = '删除了'.$info['name'].'权限组';
            $result = $operation->add($log);
            return true;
        }
        return false;
//        return  self::where('id',$id)->delete();
    }

    //获取指定用户组
    public function getOne($id){
        $data =  self::find($id);
        $auth = new Permission();
        $data['auth'] = $auth->doGetMyJson($id);
        return $data;
    }

    //获取所有用户组
    public function getOnes(){
        return self::select('id','display_name')->get();
    }
    //添加

    public function add($arr){
        if(isset($arr['auth'])){
            $auth = $arr['auth'];
            unset($arr['auth']);
        }
        DB::beginTransaction();
        $arr['created_at'] = date('Y-m-d H:i:s');
        $row = DB::table('roles')->insertGetId($arr);
        if($row >=1){
            if(isset($auth)){
                $this->doAddAuth($auth,$row);
            }
            DB::commit();
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = Auth::id();
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = Auth::user()->user_name;
            $log['operation_detail'] = '添加了'.$arr['name'].'权限组';
            $result = $operation->add($log);
            $info = array('msg'=>'添加成功','status'=>1);
        }else{
            DB::rollBack();
            $info = array('msg'=>'添加失败','status'=>0);
        }
        return $info;
    }
    //循环添加权限
    public  function  doAddAuth($arr,$id){
        $authArr = array();
        foreach ($arr as $v){
            $authArr['permission_id'] = $v;
            $authArr['role_id'] = $id;
            $row = DB::table('permission_role')->where('permission_id',$v)->where('role_id',$id)->first();
            if($row){
                continue;
            }
            DB::table('permission_role')->insert($authArr);
            $arow = DB::table('permissions')->where('pid',$v)->get();
            if(count($arow)>0){

                $childArr = array();
                foreach ($arow as $rv){
                    $childArr[] = $rv->id;
                }
                $this->doAddAuth($childArr,$id);
            }
        }
        return  true;
    }

    //修改
    public function dosave($arr,$id){
        //DB::beginTransaction();
        if(isset($arr['auth'])){
            $auth = $arr['auth'];
            unset($arr['auth']);
            DB::table('permission_role')->where('role_id',$id)->delete();
            $this->doAddAuth($auth,$id);
        }
        unset($arr['id']);
        $data = self::find($id);
        if(!$data){
            return false;
        }
        $fill = $this->getFillable();
        foreach($arr as $k=>$v){
            if(!in_array($k,$fill)) continue;
            $data->{$k} = $v;
        }
        $data->save();
        // if($data->save() !== false){
        //  DB::commit();
        //加入操作日志
        $operation = new OperationLog();
        $log = [];
        $log['user_id'] = Auth::id();
        $log['send_people'] = Auth::user()->user_name;
        $log['receive_people'] = Auth::user()->user_name;
        $log['operation_detail'] = '修改了'.$data['name'].'权限组';
        $result = $operation->add($log);
        $info = array('msg'=>'修改成功','status'=>1);
        // }else{
        //  DB::rollBack();
        //     $info = array('msg'=>'修改失败','status'=>0);
        //  }
        return $info;
    }
}
