<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Request;
use DB;
/**
 * App\Models\Logs
 *
 * @property-read \App\Models\User $user
 * @mixin \Eloquent
 */
class Logs extends Model
{
    public $fillable=[
        'user_id','ip','address','useragent'
    ];

    //设置登录日志
    public static function setLogs($user){
        self::create([
            'user_id'=>$user['user_id'],
            'ip'=>Request::getClientIp(),
            'address'=>self::getAddress(Request::getClientIp()),
            'useragent'=>Request::header("user-agent"),
        ]);
    }

    public static function getAddress($ip){
        //$url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip={$ip}";
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
        $json = json_decode(file_get_contents($url),true);
        if($json && isset($json['ret']) && $json['ret']==1){
            return trim("{$json['country']} {$json['province']} {$json['city']}");
        }
        return "未知";
    }

    public function user(){
        return $this->hasOne('App\Models\User','user_id','user_id');
    }

    public function getUserLogs($uid){
        //TODO: group_name
        $logs = Logs::where('user_id',$uid)->paginate(config('app.pagesize'));
       /* $user = User::find($uid);
        foreach($logs as &$log){
            $log->user_name = $user->user_name;
            $log->user_realname = $user->user_realname;
            $log->group_name = '默认111';
        }*/

        return $logs;
    }
    public function getAllLogs(){
        //TODO: group_name
        $logs = Logs::query()
                    ->leftJoin('user','logs.user_id','=','user.user_id')
                    ->orderBy("id","desc")
                     ->select('logs.*','user.user_name','user.user_realname','user.user_id')
                     ->paginate(config('app.pagesize'));
        foreach($logs as $k=> $v){
            $role = DB::table('role_user')
                ->join('roles','role_user.role_id','=','roles.id')
                ->where('user_id',$v->user_id)
                ->select('display_name','roles.id as rid','user_id')
                ->get();
            foreach ($role as $rv){
                $logs[$k]['group_name']  .= isset($rv->display_name)?$rv->display_name:"";
                $logs[$k]['group_name']  .= ',';
            }
        }
        return $logs;
    }

    public function getLogDetail($id){
        $log = Logs::query()
            ->leftJoin('user','logs.user_id','=','user.user_id')
            ->orderBy("id","desc")
            ->where('logs.id',$id)
            ->select('logs.*','user.user_name','user.user_realname')
            ->first();
        return $log;
    }
}
