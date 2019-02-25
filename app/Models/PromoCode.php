<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table = 'active_promocodes';
    protected  $fillable = [
        'id','name','total' , 'discount', 'start_time','end_time', 'url','status','created_at'
    ];
    // 优惠码列表
    public  function  index($sort){
        //分页序号显示设置
        $i = ($sort['page']-1)*config('app.pagesize')+1;
        $sql = self::query();
        $data =  $sql->paginate(20);
        foreach ($data as $k=>$v){
            $data[$k]['i'] = $i++;
        }
        return $data;
    }
    
    // 添加优惠码
    public function add($arr){
        DB::begintransaction();
        $arr['created_at'] = date('Y-m-d H:i"s');
        $res = self::insertGetId($arr);
        if($res){
            $array = array();
            for($i = 0;$i < $arr['total'];$i++){
                $code = 'ABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
                $rand = $code
                    .strtoupper(dechex(date('m')))
                    .date('d').substr(time(),-5)
                    .substr(microtime(),2,5)
                    .sprintf('%02d',rand(0,99));
                $str = str_shuffle($rand);
                $array[$i]['code'] = substr($str,0,6);
                $array[$i]['active_id'] = $res;
                $array[$i]['created_at'] = date('Y-m-d H:i:s');
            }
            $insert = DB::table('promocodes')->insert($array);
            if(!$insert){
                DB::rollBack();
                return array('msg'=>'请稍后再试','status'=>0);
            }else{
                DB::commit();
                return true;
            }
        }
        DB::rollBack();
        return false;
    }

    // 优惠码详情
    public function detail($id){
        $res = self::find($id);
        if($res) return $res;
        return false;
    }

    //优惠码发放
    public function record($sort){
        //分页序号显示设置
        $sql = MobilePro::leftJoin('promocodes as p', 'p.id', 'mobile_pro.code_id')
                            ->select('mobile_pro.id','p.code','mobile_pro.mobile','mobile_pro.status','mobile_pro.created_at');
        //判断是否输入订单编号
        if(isset($sort['search']['mobile']) && $sort['search']['mobile'] != 0){
            $mobile = $sort['search']['mobile'];
            $sql = $sql->where('mobile_pro.mobile','like',"%$mobile%");
        }
        $data =  $sql->orderBy('mobile_pro.created_at', 'desc')->paginate(20);

        return $data;
    }

    /**
     * 导出优惠码发放记录数据
     * @auth Nnn
     * @dateTime 2017-04-28 13:00:00
     *
     */
    public function exportCode($sort){
        $sql = MobilePro::leftJoin('promocodes as p', 'p.id', 'mobile_pro.code_id')
            ->select('mobile_pro.id','mobile_pro.mobile','p.code','mobile_pro.status','mobile_pro.created_at');
        //判断是否输入订单编号
        if(isset($sort['search']['mobile']) && $sort['search']['mobile'] != 0){
            $mobile = $sort['search']['mobile'];
            $sql = $sql->where('mobile_pro.mobile','like',"%$mobile%");
        }
        $data =  $sql->orderBy('mobile_pro.created_at', 'desc')->get();
        return $data;
    }
}
