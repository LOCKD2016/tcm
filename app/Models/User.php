<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Auth;
use Illuminate\Hashing\BcryptHasher;
class User extends Authenticatable
{
    use Notifiable,EntrustUserTrait;

    protected $table = "user";
    protected $primaryKey = "user_id";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name','user_realname' ,'user_email', 'user_password','user_salt','user_create_time','user_phone',
        'user_address','user_status','sort_num','mon_sort'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_password', 'remember_token','user_salt',
    ];

    public function getAuthPassword(){
        return $this->user_password;
    }
    public function getAuthSalt(){
        return $this->user_salt;
    }

    //获取用户分页显示信息
    public function getAllUser(){
        $users = self::where('user_status',1)->paginate(config('app.pagesize'));
        foreach($users as $k=> $v){
            $role = DB::table('role_user')
                ->join('roles','role_user.role_id','=','roles.id')
                ->where('user_id',$v->user_id)
                ->select('display_name','roles.id as rid','user_id')
                ->get();
            foreach ($role as $rv){
                $users[$k]['group_name']  .= isset($rv->display_name)?$rv->display_name:"";
                $users[$k]['group_name']  .= ',';
            }
        }
        return $users;
    }

    /**
     * 用户密码重置
     */
    public function resetPwd($id,$pwd){
        if(empty($pwd) || empty($id)) return false;
        if(!self::find($id))  return 0;
        $salt = Str::random(6);
        $row = self::where('user_id','=',$id)
            ->update([
                'user_password'=>bcrypt($pwd.$salt),
                'user_salt'=>$salt,
            ]);
        //加入操作日志
        $operation = new OperationLog();
        $log = [];
        $log['user_id'] = $id;
        $name = self::where('user_id',$id)->value('user_name');
        $log['send_people'] = Auth::user()->user_name;
        $log['receive_people'] = $name;
        $log['operation_detail'] = $log['send_people'].'重置了'.$name.'的密码';
        $result = $operation->add($log);
        return $row;
    }
    //修改用户信息
    public function doEditUserInfo($uid,$arr){
        unset($arr['user_id']);
        $user = self::find($uid);
        if(!$user){
            return false;
        }
        $fill = $this->getFillable();
        foreach($arr as $k=>$v){
            if(!in_array($k,$fill)) continue;
            $user->{$k} = $v;
        }
        if($user->save()!==false){
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = $uid;
            $name = self::where('user_id',$uid)->value('user_name');
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = $name;
            $log['operation_detail'] = $log['send_people'].'修改了'.$name.'的账户信息';
            $result = $operation->add($log);
            //return true;
            if(isset($arr['role_id'])){
                $data = DB::table('role_user')->where('user_id',$user->user_id)->delete();
                if($arr['role_id'] != 0){
                    DB::table('role_user')->insert(['role_id'=>$arr['role_id'],'user_id'=>$user->user_id]);
                }
            }
            return true;
        }else{
            return false;
        }
    }
    //删除用户
    public function delUser($id){
        $row = DB::table('user')->where('user_id' , $id)->update(['user_status'=>2]);
        //加入操作日志
        $operation = new OperationLog();
        $log = [];
        $log['user_id'] = $id;
        $name = self::where('user_id',$id)->value('user_name');
        $log['send_people'] = Auth::user()->user_name;
        $log['receive_people'] = $name;
        $log['operation_detail'] = $log['send_people'].'删除了'.$name.'的账户';
        $result = $operation->add($log);
        return $row;
    }
    /*添加用户信息 判断是否选择用户组
     *
     */
    public function doAddUser($arr){
        $arr['user_salt'] = Str::random(6);
        $arr['user_password'] = bcrypt($arr['user_password'].$arr['user_salt']);
        $arr['user_create_time']=new Carbon();
        $rid = $arr['rid'];unset($arr['rid']);
        unset($arr['user_password_confirmation']);
        DB::beginTransaction();
         $id =self::insertGetId($arr);
        if($id >=1){
            if($rid != 0 ){
                if(DB::table('role_user')->insert([
                    'user_id'=>$id,
                    'role_id'=>$rid
                ])){
                    DB::commit();
                    return 1;
                }else{
                    DB::rollBack();
                    return 2;
                }
            }
            DB::commit();
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = $id;
            $name = self::where('user_id',$id)->value('user_name');
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = $name;
            $log['operation_detail'] = $log['send_people'].'添加了用户:'.$name;
            $result = $operation->add($log);
            return true;
        }
        DB::rollBack();
        return false;
    }
    public  function getUser($id){
       return self::leftJoin('role_user','user.user_id','=','role_user.user_id')->find($id);
    }

    //修改密码 aaa

    public  function reset($arr){
        $checkpassword = new BcryptHasher;
        $password = $arr['user_password'];
        $newpassword = $arr['user_newpassword'];
        $user = self::find(Auth::id());
        $mysql_password = $user->user_password;
        $salt = $user->user_salt;
        $str = $password.$salt;
        if($checkpassword->check($str,$mysql_password)){
            $mysql_newPassword = bcrypt($newpassword.$salt);
            $id =  Auth::id();
            $row = self::where('user_id','=',$id)
                ->update([
                    'user_password'=>$mysql_newPassword,
                ]);
            if($row == 1){
                //加入操作日志
                $operation = new OperationLog();
                $log = [];
                $log['user_id'] = Auth::id();
                $name = self::where('user_id',$id)->value('user_name');
                $log['send_people'] = Auth::user()->user_name;
                $log['receive_people'] = Auth::user()->user_name;
                $log['operation_detail'] = $log['send_people'].'修改了'.$name.'的密码';
                $result = $operation->add($log);
                $info = array('msg'=>'修改成功','status'=>1);
            }else{
                $info = array('msg'=>'修改失败','status'=>0);
            }
            return $info;
        }
        return $info = array('msg'=>'密码验证失败','status'=>0);
    }

    //获取指定的操作组ID

    public function  getGroup(){
        return \App\Models\Role::select('id','display_name')->get();
    }

    //修改用户所属权限组

    public function updateRule($arr,$id){

//        $data = DB::table('role_user')->where('user_id',$id)->first();
//       if(!empty($data)){
//           $row = DB::table('role_user')->where('user_id',$id)->update(['role_id'=>$rid[0]]);
//           if($row){
//               return array('msg'=>'修改成功','status'=>1);
//           }
//           return  array('msg'=>'修改失败，请刷新重试','status'=>0);
//       }else{
//           if(DB::table('role_user')->insert(['user_id'=>$id,'role_id'=>$rid[0]])){
//               return array('msg'=>'添加成功','status'=>1);
//           }else{
//               return  array('msg'=>'添加失败，请刷新重试','status'=>0);
//           }
//       }
        DB::beginTransaction();
        DB::table('role_user')->where('user_id',$id)->delete();
        if(!isset($arr['check'])){
            DB::commit();
            return array('msg'=>'修改成功','status'=>1);
        }
        foreach ($arr['check'] as $v){
            if(DB::table('role_user')->insert(['user_id'=>$id,'role_id'=>$v]) == false){
                DB::rollBack();
                return  array('msg'=>'修改失败，请刷新重试','status'=>0);
            }
        }
        DB::commit();
        return array('msg'=>'修改成功','status'=>1);

        //if(){}
    }

    //获取投资人的列表
    public  function  roleMyUser(){
        $data =  self::leftJoin('role_user','role_user.user_id','=','user.user_id')
                    ->where('role_user.role_id',1)
                    ->where('user.user_status',1)
                    ->orderBy('user.sort_num','desc')
                    ->select('user.user_id','user.user_realname','user.user_name','user.sort_num','user.mon_sort')
                    ->get();
        //$data =  self::whereBetween('user.user_id',[80,100])->get();
        foreach($data as $k=>$v){
            $data[$k]->checked = false;
        }
        return $data;

    }

    //获取离职人员的列表ID
    public function Resignation(){
        $data = self::where('user_status',2)->get();
        $arr = array();
        foreach ($data as $v){
            $arr[] = $v->user_id;
        }
        return $arr;
    }

    //冻结用户
    public function  forbidden($id){
        $user = self::find($id);
        if($user->user_status ==3){
           $row =  self::where('user_id',$id)->update(['user_status'=>1]);
        }else{
            $row =  self::where('user_id',$id)->update(['user_status'=>3]);
        }
        if($row){
            //加入操作日志
            $operation = new OperationLog();
            $log = [];
            $log['user_id'] = $id;
            $log['send_people'] = Auth::user()->user_name;
            $log['receive_people'] = Auth::user()->user_name;
            $log['operation_detail'] = $log['send_people'].'冻结了'.$user['name'].'的账户';
            $result = $operation->add($log);
            return array('msg'=>'操作成功','status'=>1);
        }else{
            return array('msg'=>'操作失败','status'=>0);
        }

    }

    //获取指定的操作组ID

    public function  getRule($id){
        $row = DB::table('role_user')
            ->where('user_id',$id)->get();
        $arr = array();
        foreach ($row as $v){
            $arr[] = $v->role_id;
        }
        $role = new Role();
        $allRole = $role->getOnes();
        foreach ($allRole as $k=>$v){
            if(in_array($v->id,$arr)){
                $allRole[$k]['status'] = 1;
            }else{
                $allRole[$k]['status'] = 0;
            }
        }
        return $allRole;
    }
}
