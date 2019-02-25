<?php

namespace App\Repository;

use DB;
use App\Models\Doctor;
use App\Models\Comment;
use App\Models\Section;
use App\Util\Tools;
use Illuminate\Support\Str;
use App\Models\DoctorLeave;
use App\Models\DoctorRemark;
use App\Models\Schedules;
use Illuminate\Pagination\LengthAwarePaginator;
use PhpParser\Comment\Doc;

/**
 * Class DoctorRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class DoctorRepository extends Repository
{

    /**
     * 推荐人数
     * @var int
     */
    public $recommendNum = 3;

    /**
     * 创建医生
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $data['salt'] = Str::random(6);
        $data['password'] = bcrypt($data['password'] . $data['salt']);
        return $this->model->create($data);
    }

    /**
     * 创建医生休息
     * @param $data
     * @return static
     */
    public function leave_create($data)
    {
        return $this->leave_model()->create($data);
    }

    /**
     * 医生休息的记录列表
     * @param $doctor_id
     * @return mixed
     */
    public function doctor_leave_list($doctor_id)
    {
        return $this->leave_model()->queryDoctor($doctor_id)->paginate($this->page);
    }

    /**
     * 通过手机号查询医生
     * @param $mobile
     * @return mixed
     */
    public function get_data_by_mobile($mobile)
    {
        return $this->model->queryMobile($mobile)->first();
    }

    /**
     * 获取网诊的列表
     * @Auth: kingofzihua
     * @param $search
     * @return mixed
     */
    public function get_web_list($search)
    {
//        return $search;
        return $this->model
            ->queryTitle($search['title'] ?? '')//医生职称
            ->queryName($search['name'] ?? '')//医生姓名
            ->querySections($search['sections_id'] ?? '')//医生科室
            ->queryOrDisease($search['name'] ?? '')//医生所擅长疾病
            ->querySectionAmount($search['fees'] ?? '', $search['type'])//医生的诊费
            ->queryWeb()
            ->orderBy('rest', 'asc')
            ->paginate($this->page);
    }

    /**
     * 全局搜索 网诊医生
     * @Auth: kingofzihua
     * @param $search
     * @return mixed
     */
    public function global_search_web_list($search)
    {
        return $this->model
            ->queryOrDisease($search['name'] ?? '')//医生所擅长疾病
            ->queryOrName($search['name'] ?? '')//医生姓名
            ->queryWeb()
            ->paginate($this->page);
    }

    /**
     * 获取网诊推荐人数
     * @return LengthAwarePaginator
     */
    public function recommend_web_list()
    {
        $doctor = $this->model->queryWeb()->orderBy('level', 'desc')->limit($this->recommendNum)->get();

        return new LengthAwarePaginator($doctor, count($doctor), $this->recommendNum);
    }

    /**
     * 获取门诊列表
     * @Auth: kingofzihua,@edit:haiming
     * @param $search
     * @return mixed
     */
    public function get_clinic_list($search)
    {
        $conds = [//$query->where(['clinic' => '1', 'status' => '1'])//门诊
            ['clinic', '=', 1],
            ['status', '=', 1],
            ['is_del', '=', 0]
        ];

        $query = Doctor::select(DB::raw('doctors.*, schedules.doctor_id, schedules.clinique_id, max(date) as max_date'))
                        ->leftJoin('schedules', 'doctors.id', '=', 'schedules.doctor_id')
                        ->orderBy('max_date', 'desc');
        //医生职称
        if (!empty($search['title'])){
            $conds[] = ['doctors.title', '=', $search['title']];
        }
        //医生姓名||医生所擅长疾病
        if (!empty($search['name'])) {
//            $conds[] = ['doctors.name', 'like','%' . $search['name'] . '%'];
            $disease_id = DB::table('disease')->where('name', 'like', '%'.$search['name'].'%')->pluck('id')->toArray();
            $doctor_id = DB::table('doctor_disease')->whereIn('disease_id', $disease_id)->pluck('doctor_id')->toArray();
            if ($doctor_id){
                $query = $query->where(function ($que) use($search, $doctor_id) {
                    $que->where('doctors.name', 'like','%' . $search['name'] . '%')
                        ->orWhereIn('doctors.id', $doctor_id);
                });//whereIn('doctors.id', $doctor_id);//return $doctor_id;
            }else {
                $query = $query->where('doctors.name', 'like','%' . $search['name'] . '%');
            }

        }
        //医生科室
        if (!empty($search['sections_id'])) {
            $doctor_id = DB::table('doctor_section')
                        ->where('section_id', '=', $search['sections_id'])
                        ->pluck('doctor_id')
                        // ->get()
                        ->toArray();// return $doctor_id;
            if ($doctor_id){
                $query = $query->whereIn('doctors.id', $doctor_id);
            }
        }
        //诊所
        if (!empty($search['clinique'])) {
            $doctor_id = DB::table('doctor_clinique')->where('clinique_id', '=', $search['clinique'])->pluck('doctor_id')->toArray();
            if ($doctor_id){
                $query = $query->whereIn('doctors.id', $doctor_id);
            }
        }
        //医生排班
        if (!empty($search['schedule'])) {
            $schedule = $search['schedule'];
            if (is_array($schedule)) {//如果多个
                $query = $query->whereIn('date', $schedule);
            } else {//单个
                $query = $query->where('date', '=', $schedule);
            }
        }
        
        $doctor_list = $query->where($conds)->groupBy('doctors.id')->paginate($this->page);
        return $doctor_list;

        // return $aa;
        // if(isset($search['title'])){
        //     return gettype($search['title']);
        // }else {
        //     return 22;
        // }

/*        return $this->model
            ->queryTitle($search['title'] ?? '')//医生职称
            ->queryOrName($search['name'] ?? '')//医生姓名
            ->querySections($search['sections_id'] ?? '')//医生科室
            ->queryOrDisease($search['name'] ?? '')//医生所擅长疾病
            ->queryClinique($search['clinique'] ?? '')//诊所
            ->querySchedule($search['schedule'] ?? '', $search['clinique'] ?? '')//医生排班
            ->queryClinic()//门诊
            ->orderBy('date')
            ->paginate($this->page);*/
//        SQLSTATE[42S22]: Column not found: 1054 Unknown column 'isSchedules' in 'order clause' (SQL:
//        select * from `doctors` where exists (select * from `cliniques` inner join `doctor_clinique` on `cliniques`.`id` = `doctor_clinique`.`clinique_id`
//        where `doctor_clinique`.`doctor_id` = `doctors`.`id`) and exists (select * from `schedules` where `schedules`.`doctor_id` = `doctors`.`id`) and
//        (`clinic` = 1 and `status` = 1) and `doctors`.`deleted_at` is null order by `isSchedules` desc limit 15 offset 0)"

    }

    /**
     * 全局搜索 门诊医生
     * @param $search
     * @return mixed
     */
    public function global_search_clinic_list($search)
    {
        return $this->model
            ->queryOrDisease($search['name'] ?? '')//医生所擅长疾病
            ->queryOrName($search['name'] ?? '')//医生姓名
            ->queryClinic()//门诊
            ->paginate($this->page);
    }

    /**
     * 全局搜索医生列表
     * @param $search
     * @return mixed
     */
    public function global_search_doctor_list($search)
    {
        return $this->model
            ->queryOrDisease($search['name'] ?? '')//医生所擅长疾病
            ->queryOrName($search['name'] ?? '')//医生姓名
            ->queryClinicOrWeb()//门诊 或者网诊
            ->paginate($this->page);
    }

    /**
     * 获取门诊推荐医生
     * @return LengthAwarePaginator
     */
    public function recommend_clinic_list()
    {
        $doctor = $this->model->queryClinic()->querySchedule($search['schedule'] ?? '', $search['clinique'] ?? '')->orderBy('level', 'desc')->limit($this->recommendNum)->get();

        return new LengthAwarePaginator($doctor, count($doctor), $this->recommendNum);
    }

    /**
     * 推荐医生
     * @return LengthAwarePaginator
     */
    public function recommend_doctor_list()
    {
        $doctor = $this->model->queryClinicOrWeb()->orderBy('level', 'desc')->limit($this->recommendNum)->get();

        return new LengthAwarePaginator($doctor, count($doctor), $this->recommendNum);
    }


    /**
     * 获取空的列表页
     * @return LengthAwarePaginator
     */
    public function get_empty_page_list()
    {
        return new LengthAwarePaginator([], 0, 1, 1);
    }

    /**
     * 获取登录的医生
     * @return \App\Models\User|null
     */
    public function get_login_doctor()
    {
        return \Auth::user();
    }

    /**
     * 获取登录医生的医嘱
     * @return mixed
     */
    public function get_auth_remark_list()
    {
        return \Auth::user()->remark()->paginate($this->page);
    }

    /**
     * 构建医嘱
     * @param $data
     * @return DoctorRemark
     */
    public function builderRemark($data)
    {
        return new DoctorRemark($data);
    }

    /**
     * 获取登录医生的用户列表
     * @param $name
     * @return mixed
     */
    public function get_login_doctor_user_list_by_search($name)
    {


        $doctor = $this->get_login_doctor();

        if (isset($name)) {
            $doctor_user = $doctor->users()
                ->wherePivot('extend', 'like', '%' . $name . '%')//按照疾病查询
                ->paginate($this->page);
        } else {
            $doctor_user = $doctor->users()->paginate($this->page);
        }
        foreach($doctor_user as $key=>$val){
            if(is_array(json_decode($val['pivot']['extend'], true))){
                $val['pivot']['extend'] = json_decode($val['pivot']['extend'], true);
            }
        }
        return $doctor_user;
    }


    /**
     * 通过诊所获取医生的code码
     * @param Doctor $doctor
     * @param $clinique_id
     * @return null
     */
    public function get_doctor_code_by_clinque_id(Doctor $doctor, $clinique_id)
    {
        $doctor_clinique = $doctor->cliniques()->where('clinique_id', $clinique_id)->first();

        return count($doctor_clinique) ? $doctor_clinique->pivot->code : null;
    }

    /**
     * 医生休息
     * @return DoctorLeave
     */
    public function leave_model()
    {
        return new DoctorLeave();
    }

    /**
     * @Auth: kingofzihua
     * @return Doctor
     */
    public function model()
    {
        // TODO: Implement model() method.
        return new Doctor();
    }


    /**
     * 多条件查询医生列表
     * @param $search
     * @return mixed
     */
    public function get_platform_doctor_list($search)
    {
        return $this->model
            ->doctorType($search['type'] ?? '')//web网诊 clinic门诊
            ->queryName($search['name'] ?? '')//医生姓名
            ->doctorMobile($search['mobile'] ?? '')//医生手机号
            ->doctorIdNo($search['idNo'] ?? '')//医生身份证
            ->paginate($this->page);
    }

    /**
     * 通过医生的code码获取id
     * @param $doctor_id
     * @return null
     */
    public function get_doctor_by_coustomer_code($code)
    {
        $doctor_clinique = \DB::table('doctor_clinique')->where('code', $code)->first();

        return $doctor_clinique ? $doctor_clinique->doctor_id : null;
    }

    /**
     * 添加医生
     * @param $data
     */
    public function create_doctor($data)
    {
        return $this->model->insertGetId($data);
    }

    /**
     * 查询医生
     * @param $data
     */
    public function get_doctor_by_name_mobile_sex($data)
    {
        return $this->model->where(['name' => $data['name'], 'mobile' => $data['mobile'], 'sex' => $data['sex']])->first();
    }

    /**
     * 更新医生数据
     * @param $id
     * @param $data
     */
    public function update_doctor($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * 根据id获取医生数据
     * @param $id
     * @return mixed
     */
    public function get_doctor_by_id($id)
    {
        return $this->model->find($id);
    }

    /**
     * 获取评论信息
     * @param $search
     * @return mixed
     */
    public function get_doctor_data_statistics($search){
        return $this->model->getDoctorDataStatistics(\Auth::id(), $search);

    }

    /**
     *不知为何缺少了这个方法，统计医生星星并修改进doctor表的level字段
     * @author haiming
     */
    public function update_doctor_level($doctor_id)
    {
        $avg_manner = Comment::where('doctor_id', $doctor_id)->select(DB::raw('avg(manner) as avg_manner'))->first();
        $doctor = Doctor::find($doctor_id);
        $doctor->level = ceil($avg_manner->avg_manner);
        if ($doctor->save()){
            return true;
        }else {
            return false;
        }
    }


    public function test(){

        $week = date('w');
        $monday = date('Y-m-d', strtotime('+' . 1 - $week . ' days'));
        $sunday = date('Y-m-d', strtotime('+' . 7 - $week . ' days'));
        $scheduling = Schedules::where('doctor_id', \Auth::id())
            ->whereDate('date', '>=', $monday)
            ->whereDate('date', '<=', $sunday)
            ->with('clinque')
            ->orderBy('date')
            ->get()->toArray();
        $handleData = [];
        foreach ($scheduling as $v) {
            $start_time = substr($v['start_time'] , 8 , 2);
            $end_time = substr($v['end_time'] , 8 , 2);
            $workTime = '';
            if($start_time== '09' && $end_time == '12'){
                $workTime = 0;
            }else if($start_time== '13' && $end_time == '18'){
                $workTime = 1;
            }
//            else if($start_time > '18'){
//                $workTime = 2;
//            }
            else{
                $workTime = 3;
            }

            $handleData[] = [
                'workTime' => $workTime,
                'week' => date('w', strtotime($v['date'])),
                //'data' =>$v['date'],
                'clinique_name' => $v['clinque']['name']
            ];
        }
        $handleGroupData = collect($handleData)->groupBy('clinique_name')->toArray();

        //日 一 。。。。。六
        foreach ($handleGroupData as $k => $v) {
            $retArr = [];
            //上午
            $weeks = collect($v)->whereIn('workTime', [0,3])->pluck('week')->toArray();
            $retArr[] = Tools::weeksHandle($weeks);
            //下午
            $weeks = collect($v)->whereIn('workTime',[1,3])->pluck('week')->toArray();
            $retArr[] = Tools::weeksHandle($weeks);
            //晚上
            $weeks = collect($v)->whereIn('workTime', [2,3])->pluck('week')->toArray();
            $retArr[] = Tools::weeksHandle($weeks);
            $handleGroupData[$k] = $retArr;
        }
        return $handleGroupData;
    }
}