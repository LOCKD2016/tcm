<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class OperationLog extends Model
{
    use Notifiable,SoftDeletes;
    protected  $table="operation_logs";
    protected  $fillable=[
        'id','user_id','operation_detail','updated_at','created_at'
    ];
    protected $messages =  [
        'operation_detail.required' => '操作内容不能为空',
        'send_people.required' => '发送人不能为空',
        'receive_people.required' => '接收人不能为空'
    ];
    //
    protected $hidden = ['deleted_at','updated_at'];//created_at

    public function returnRealName($name){
        $realname = DB::table('user')->where('user_name',$name)->value('user_realname');
        return !empty($realname)?$realname:'';
    }

    public function getAllList($arr){
        $sql = self::query();
        if(isset($arr['searchs']['name']) && !empty($arr['searchs']['name'])){
            $name  = trim($arr['searchs']['name']);
            $sql = $sql->where('content', 'like', "%$name%");
        }
        $data = $sql->orderBy('id','desc')->paginate(config('app.pagesize'));
        foreach($data as $k=>$v){
            $data[$k]->i = $k+1;
            $data[$k]->send_people = $this->returnRealName($v->send_people);
            $data[$k]->receive_people = $this->returnRealName($v->receive_people);
        }
        return $data;
    }

    public  function add($arr){
        $validator = Validator::make($arr, [
            'operation_detail' => 'required',
            'send_people' => 'required',
            'receive_people' => 'required',
        ],$this->messages);
        if ($validator->fails())
        {
            $msg =  $validator->errors()->toArray();
            return array_pop($msg);
        }
        $arr['created_at'] = new Carbon();
        $row = self::insertGetId($arr);
        if($row >=1) return true;
        return false;
    }

    public  function  del($id){
        $info = $this->getOne($id);
        if(!$info) return false;
        $info->delete();
        if($info->trashed()){
            return true;
        }
        return false;
    }

    public  function  getOne($id){
        return $res =  self::find($id);

    }

    public  function  doUpdate($arr){
        $id = $arr['id'];
        $data = self::find($id);
        if(!$data) return false;
        $row = self::where('id',$id)->update($arr);
        if($row>0){
            return true;
        }else{
            return false;
        }
    }

    public function read($id){
        $info = self::find($id);
        if(!$info) return false;
        $row = self::where('id',$id)->update(['read_flag'=>1]);
        if($row>0){
            return true;
        }
        return false;
    }

    public function count(){
        return self::where('read_flag',0)->count();
    }

    public  function  doSearch($arr){
        $name = $arr['search'];
        $class = new Category();
        $data = [];
        //appUser的搜索结果
        $peopleData = DB::table('app_users')->orderBy('id','desc')
            ->where('nickname','like',"%$name%")
            ->where('status',0)->get();
        foreach ($peopleData as $k1=>$v1){
            $peopleData[$k1]->i = $k1+1;
        }
        //专家的搜索结果
        $expertData = DB::table('experts')->orderBy('id','desc')
            ->where('name','like',"%$name%")
            ->get();
        foreach ($expertData as $k2=>$v2){
            $expertData[$k2]->i = $k2+1;
        }
        //卡片的搜索结果
        $articleData = DB::table('articles')->leftJoin('experts','experts.id','=','articles.expert_id')
            ->leftJoin('categories','categories.id','=','articles.category_id')
            ->orderBy('id','desc')
            ->where('title','like',"%$name%")
            ->select('articles.id','articles.title','articles.category_id','articles.expert_id','articles.robot_id','articles.read_num',
                'articles.help_yes_num','articles.help_no_num','articles.collection_num','articles.comment_num','articles.like_num',
                'articles.review','articles.share_num','articles.type')->get();
        foreach ($articleData as $k3=>$v3){
            $articleData[$k3]->i = $k3+1;
            $name = $class->getParent($v3->category_id);
            krsort($name);
            $articleData[$k3]->category_name = implode('--',$name);
            $articleData[$k3]->type = $this->type($v3->type);
            $articleData[$k3]->expert = $this->returnExpertName($v3->expert_id);
        }
        //常见问题的搜索结果
//        $commonData = DB::table('common_problem')->orderBy('id','desc')
//            ->where('problem','like',"%$name%")->get();
//        foreach ($commonData as $k4=>$v4){
//            $commonData[$k4]->i = $k4+1;
//        }
        $data['peopleData'] = $peopleData;
        $data['expertData'] = $expertData;
        $data['articleData'] = $articleData;
//        $data['commonData'] = $commonData;
        return $data;
    }

    public  function  type($model){
        switch($model){
            case 0:
                $model = '备孕';
                break;
            case 1:
                $model = '育儿';
                break;
            case 2:
                $model = '辣妈';
                break;
            default:
                $model = '未知';
                break;
        }
        return $model;
    }

    public function returnExpertName($id){
        $name = DB::table('experts')->where('id',$id)->value('name');
        return !empty($name)?$name:'';
    }
}
