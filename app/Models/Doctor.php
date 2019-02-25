<?php

namespace App\Models;

use Carbon\Carbon;
use App\Util\Tools;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * 医生
 * Class Doctor
 * @package App\Models
 */
class Doctor extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * 未知
     */
    const SEX_UNKNOWN = 2;

    /**
     * 男
     */
    const SEX_MAN = 0;

    /**
     * 女
     */
    const SEX_WMAN = 1;

    /**
     * 表名
     * @var string
     */
    protected $table = 'doctors';

    /**
     * 主键
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * 可批量赋值字段
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * 隐藏 字段
     * @var array
     */
    protected $hidden = ['password', 'salt'];

    /**
     * 属性类型转换
     * @var array
     */
    protected $casts = [
//        'profession_auth' => 'json', //职业证书
//        'qualification_auth' => 'array', //资格证书
        'expert' => 'array', //擅长
    ];


    /**
     * 获取密码
     * @return mixed
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * 获取盐值
     * @return mixed
     */
    public function getAuthSalt()
    {
        return $this->salt;
    }

    /**
     * 获取手机号
     * @return mixed
     */
    public function getAuthName()
    {
        return $this->mobile;
    }

    /**
     * 获取 当前数据模型的 表名
     * @return string
     */
    protected function getTableName()
    {
        return $this->table;
    }

    /*-------------------------------------------------属性访问修改器---------------------------------------------------*/

    /**
     * 定义 就诊时长属性访问修改器
     * @Auth: kingofzihua
     * @param $value
     * @return int
     */
    public function getLengthAttribute($value)
    {
        return intval($value) > 0 ? intval($value) : 15;
    }

    /**
     * 定义年龄属性访问修改器
     * @Auth: kingofzihua
     * @return string
     */
    public function getAgeAttribute()
    {
        return isset($this->birthday) ? Carbon::parse($this->birthday)->diffInYears() : '未知';
    }

    /**
     * 获取医生的职称
     * @Auth: kingofzihua
     * @param $value
     * @return string
     */
    public function getTitleNameAttribute()
    {
        return $this->title ? Config::getTitle($this->title) : '';
    }

    /*-------------------------------------------------查询构造器------------------------------------------------------*/

    /**
     * 登录用户
     * @Auth: kingofzihua
     * @param $query
     * @return mixed
     */
    public function scopeQueryAuth($query)
    {
        return $query->where('user_id', \Auth::id());
    }

    /**
     * 医师职称
     * @Auth: kingofzihua
     * @param $query
     * @param $title_id
     * @return string
     */
    public function scopeQueryTitle($query, $title_id)
    {
        return $title_id ? $query->where('title', '=', $title_id) : '';
    }

    /**
     * 医师名称
     * @Auth: kingofzihua
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeQueryName($query, $name)
    {
        return $name ? $query->where('name', 'like', '%' . $name . '%') : '';
    }

    /**
     * 医师名称
     * @Auth: kingofzihua
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeQueryOrName($query, $name)
    {
        return $name ? $query->orWhere('name', 'like', '%' . $name . '%') : '';
    }

    /**
     * 医师手机号
     * @Auth: kingofzihua
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeQueryMobile($query, $mobile)
    {
        return $mobile ? $query->where('mobile', trim($mobile)) : '';
    }

    /**
     * 开通网诊
     * @Auth: kingofzihua
     * @param $query
     * @return mixed
     */
    public function scopeQueryWeb($query)
    {
        return $query->where(['web' => '1', 'status' => '1', 'is_del' => '0']);
    }

    /**
     * 开通门诊
     * @Auth: kingofzihua
     * @param $query
     * @return mixed
     */
    public function scopeQueryClinic($query)
    {
        return $query->where(['clinic' => '1', 'status' => '1', 'is_del' => '0']);
    }

    /**
     * 网诊或者门诊的医生
     * @param $query
     * @return mixed
     */
    public function scopeQueryClinicOrWeb($query)
    {
        return $query->where(function ($que) {
            $que->where('web', '1')->orWhere('clinic', '1');
        })->where('status', '1')->where('is_del', '0');
    }

    /**
     * 科室
     * @Auth: kingofzihua
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeQuerySections($query, $section_id)
    {
        return $section_id ? $query->whereHas('sections', function ($que) use ($section_id) {
            $que->where('id', '=', $section_id);
        }) : '';
    }

    /**
     * 疾病
     * @Auth: kingofzihua
     * @param $query
     * @param $disease
     * @param string $type : name or id
     * @return string
     */
    public function scopeQueryDisease($query, $disease, $type = 'name')
    {
        return $disease ? $query->whereHas('diseases', function ($que) use ($type, $disease) {
            if ($type == 'name')
                $que->where($type, 'like', '%' . $disease . '%');
            else
                $que->where($type, '=', $disease);
        }) : '';
    }

    /**
     * 疾病
     * @Auth: kingofzihua
     * @param $query
     * @param $disease
     * @param string $type : name or id
     * @return string
     */
    public function scopeQueryOrDisease($query, $disease, $type = 'name')
    {
        return $disease ? $query->orWhereHas('diseases', function ($que) use ($type, $disease) {
            if ($type == 'name')
                $que->where($type, 'like', '%' . $disease . '%');
            else
                $que->where($type, '=', $disease);
        }) : '';
    }

    /**
     * 诊所
     * @desc 默认为 全部诊所
     * @Auth: kingofzihua
     * @param $query
     * @param $clinique_id
     */
    public function scopeQueryClinique($query, $clinique_id)
    {
        if (empty($clinique_id)) {
            return $query->has('cliniques', '>=', 1);
        } else {
            return $query->whereHas('cliniques', function ($que) use ($clinique_id) {
                return $que->where('id', '=', $clinique_id);
            });
        }
    }

    /**
     * 根据医生排版获取
     * @Auth: kingofzihua
     * @param $query
     * @param $schedule 排班 2017-11-14
     * @param $clinique_id 诊所id 1
     * @return mixed
     */
    public function scopeQuerySchedule($query, $schedule, $clinique_id)//schedules
    {
        return $query->whereHas('schedules', function ($que) use ($schedule, $clinique_id) { //分下组 防止多个

//            $que->orderBy('date');
            if (empty($schedule)) {
//                $que->where('date', '>', date('Y-m-d', time()));
            } else {
                if (is_array($schedule)) {//如果多个
                    $que->whereIn('date', $schedule);
                } else {//单个
                    $que->where('date', '=', $schedule);
                }
            }

            if (!empty($clinique_id)) { //选择了诊所
                $que->where('clinique_id', '=', $clinique_id);
            }
        });
    }

    /**
     * 价格区间
     * @param $query
     * @param $amount
     */
    public function scopeQuerySectionAmount($query, $amount, $type)
    {
        return $query->where(function ($que) use ($amount, $type){
            if (is_array($amount) && count($amount)) {
                array_map(function ($fee) use ($que, $type) {
                    if ($fee) {
                        $num = explode('-', $fee);
                        switch ($type){
                            case 'web':
                                $amount = 'web_amount';
                                break;
                            case 'video':
                                $amount = 'video_amount';
                                break;
                            default:
                                $amount = 'web_amount';
                                break;

                        }

                        if (count($num) == 2) {
                            $num[0] = $num[0] * 100;
                            //元 改为分
                            $num[1] = $num[1] * 100;
//                        $query->orWhereBetween('web_amount', $num);//这是之前的代码连个return都没有，但是奇怪的是好像还好用，可能不需要return?
                            $query = $que->orWhereBetween($amount, $num);
                        }

                        if (count($num) == 1) {
                            $num2 = explode('+', $num[0]);
//                        $query->orWhere('web_amount', '>', $num2[0]);
                            $query = $que->orWhere($amount, '>', $num2[0] * 100);
                        }
                    }
                }, $amount);

                return $que;//这个之前也没有
            }
        });

    }

    /*--------------------------------------------------修改构造器------------------------------------------------------*/

    /**
     * 修改医生的擅长
     * @Auth: kingofzihua
     * @param $value
     * @return void
     */
    public function setExpertAttribute($value)
    {
        //删除所有的疾病
        $this->diseases()->detach();

        $expert = \GuzzleHttp\json_decode($value, true);

        if (is_array($expert)) {

            //获取所输入的疾病的编号
            $ids = array_map(function ($item) { // 如果是 数字类型 则认为是 疾病的ID,如果是 字符串类型 则认为是 疾病的名称

                return is_numeric($item) ? $item : (is_string($item) ? Disease::getIdByNameWithCreate($item) : null);

            }, $expert);

            if (count($ids))
                $this->diseases()->attach($ids);
        }
    }

    /**
     * 修改医生的科室
     * @Auth: kingofzihua
     * @param $value
     * @return void
     */
    public function setSectionsAttribute($value)
    {
        //删除所有的科室
        $this->sections()->detach();

        $sections = \GuzzleHttp\json_decode($value, true);

        if (is_array($sections)) {

            //获取所输入的疾病的编号
            $ids = array_map(function ($item) { // 如果是 数字类型 则认为是 疾病的ID,如果是 字符串类型 则认为是 疾病的名称

                return is_numeric($item) ? $item : (is_string($item) ? Section::getIdByNameWithCreate($item) : null);

            }, $sections);

            if (count($ids))
                $this->sections()->attach($ids);
        }
    }

    /**
     * 设置小头像地址
     * @desc 兼容之前的接口
     *   'photoSUrl' => 'min:3', //小头像地址
     * @param $value
     */
    public function setPhotoSUrlAttribute($value)
    {
        $this->head_img_L = $value;
    }

    /**
     * 设置大头像地址
     * @desc 兼容之前的接口
     *   'photoLUrl' => 'min:3', //大头像地址
     * @param $value
     */
    public function setPhotoLUrlAttribute($value)
    {
        $this->head_img = $value;
    }

    /*--------------------------------------------------关联模型-------------------------------------------------------*/

    /**
     * 预约
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bespeaks()
    {
        return $this->hasMany(Bespeak::class);
    }

    /**
     * 药方
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescription()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * 评论
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 排班
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * 分组
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    /**
     * 获取 医嘱
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function remark()
    {
        return $this->hasMany(DoctorRemark::class);
    }

    /**
     * 医生的患者
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(AppUser::class, 'doctor_users', 'doctor_id', 'user_id')
            ->withPivot(['extend', 'time']);
    }

    /**
     * 诊所
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function cliniques()
    {
        return $this->belongsToMany(Clinique::class, 'doctor_clinique',
            'doctor_id', 'clinique_id')->withPivot(['code']);
    }

    /**
     * 科室
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'doctor_section');
    }

    /**
     * 疾病
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'doctor_disease');
    }

    /**
     * 休息记录
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave()
    {
        return $this->hasMany(DoctorLeave::class);
    }

    /*--------------------------------------------------自定义方法-----------------------------------------------------*/

    /**
     * 通过用户的编号 获取所在当前医生的分组
     * @Auth: kingofzihua
     * @param int $user_id
     * @return \Illuminate\Support\Collection
     */
    public function selectGroupByUserId(int $user_id)
    {
        $doctor_group_ids = $this->groups()->pluck('id')->toArray();//获取当前医生的所有分组的id

        if (count($doctor_group_ids)) {
            $user = AppUser::find($user_id);

            if ($user) {
                return $user->groups()->whereIn('group_id', $doctor_group_ids)->get();
            }
        }

        return collect();
    }

    /**
     * 准备修改的数据
     * @Auth: kingofzihua
     * @param array $data
     * @return $this
     */
    public function loadEditData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }

        return $this;
    }

    /**
     * 获取 性别
     * @Auth: kingofzihua
     * @param null $ind
     * @return array|mixed
     */
    public function sex($ind = null)
    {
        $arr = [
            self::SEX_UNKNOWN => '未知',
            self::SEX_MAN => '男',
            self::SEX_WMAN => '女',
        ];

        return $ind !== null ? array_key_exists($ind, $arr) ? $arr[$ind] : $arr[self::SEX_UNKNOWN] : $arr;
    }

    /**
     * 关联患者
     * @param $user_id
     * @param null $extend 扩展字段
     * @return boolean true || false
     */
    public function relevance_user($user_id, $extend = null)
    {
        try {
            $this->users()->attach($user_id);
        } catch (QueryException $exception) {
            if ($exception->getCode() !== "23000") return false; //判断下 如果错误不是因为 联合主键约束 就停止
        }

        if ($extend) {
            $pivot['extend'] = $extend;
        }

        $pivot['time'] = Carbon::now();

        if ($extend) $this->users()->updateExistingPivot($user_id, $pivot);

        return true;
    }

    /*******************************************************************后台**********************************************************************/


    /**
     * 排班
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedulesClinique()
    {
        return $this->hasMany(Schedule::class)->with('clinque')->orderBy('date', 'desc');
    }

    /**
     * 预约订单
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bespeakOrders()
    {
        return $this->hasMany(Bespeak::class)->with('order');
    }

    /**
     * 药方订单
     * @Auth: Nnn
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prescriptionOrders()
    {
        return $this->hasMany(Prescription::class)->with('order');
    }


    public function findId($id)
    {
        return Doctor::find($id);
    }

    /**
     * 医师姓名
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeDoctorName($query, $name)
    {
        return $name ? $query->where('name', 'like', '%' . trim($name) . '%') : '';
    }

    /**
     * 诊疗创建时间
     * @Auth: Nnn
     * @param $query
     * @param $time
     * @return string
     */
    public function scopeClinicStartTime($query, $time)
    {
        return $time ? $query->WhereHas('bespeaks', function ($que) use ($time) {

			$que->whereDate('created_at', '>=', $time);

		}) : '';
    }

    /**
     * 诊疗创建时间
     * @Auth: Nnn
     * @param $query
     * @param $time
     * @return string
     */
    public function scopeClinicEndTime($query, $time)
    {
        return $time ? $query->WhereHas('bespeaks', function ($que) use ($time) {

			$que->whereDate('created_at', '<', $time);

		}) : '';
    }

    /**
     * 医师手机号
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeDoctorMobile($query, $mobile)
    {
        return $mobile ? $query->Where('mobile', 'like', '%' . trim($mobile) . '%') : '';
    }

    /**
     * 医师审核状态
     * @Auth: Nnn
     * @param $query
     * @param $status
     * @return string
     */
    public function scopeStatus($query, $status)
    {
        switch ($status) {
            case 1:
            case 2:
            case 0:
                return $query->where('status', $status);
                break;
            default:
        }
    }

    /**
     * 医生是否删除
     * @Auth: haiming
     * @param $query
     * @return string
     */
    public function scopeIsDelete($query)
    {
        return $query->where('is_del', 0);
    }

    /**
     * 医师来源
     * @Auth: Nnn
     * @param $query
     * @param $sex
     * @return string
     */
    public function scopeSource($query, $source)
    {
        return $source === '' ? '' : $query->where('source', $source);
    }

    /**
     * 修改医生信息审核状态
     * @param $id
     * @param $data
     */
    public function updated_status_or_source_by_id($id, $data)
    {
        return Doctor::where('id', $id)->update([$data['type'] => $data['status']]);
    }

    /**
     * 修改医生信息
     * @param $id
     * @param $data
     */
    public function updated_data_by_id($id, $data)
    {
        return Doctor::where('id', $id)->update($data);
    }

    /**
     * 评价起始时间
     * @param $query
     * @param $starTime
     */
    public function scopeCommentStartTime($query, $starTime)
    {
        return $starTime ? $query->whereHas('comments', function ($que) use ($starTime) {
            $que->where('created_at', '>', $starTime);
        }) : '';
    }

    /**
     * 评价终止时间
     * @param $query
     * @param $starTime
     */
    public function scopeCommentEndTime($query, $endTime)
    {
        return $endTime ? $query->whereHas('comments', function ($que) use ($endTime) {
            $que->where('created_at', '<', $endTime);
        }) : '';
    }

    /**
     * 根据医生门诊 网诊查询
     * @param $query
     * @param $type
     */
    public function scopeDoctorType($query, $type)
    {
        return $type ? $type == 'web' ? $query->where('web', 1) : $query->where('clinic', 1) : '';
    }

    /**
     * 根据医生身份证号查询
     * @param $query
     * @param $idNo
     */
    public function scopeDoctorIdNo($query, $idNo)
    {
        return $idNo ? $query->where('idNo', $idNo) : '';
    }

    /**
     * 医生倒叙排
     * @param $query
     */
    public function scopeDoctorOrderByDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }


    public function getDoctorDataStatistics($doctor_id, $search){
        $query = Comment::where('doctor_id', $doctor_id)->whereNotNull('disease')->orderBy('id', 'desc');
        if (count($search)) {
            switch ($search['type']) {
                case 'week':
                    $params = explode(',', $search['date']);
                    $query = $query->whereBetween('created_at', [$params[0], $params[1]]);
                    break;
                case 'month':
                    $params = explode('-', $search['date']);
                    $query = $query->whereYear('created_at', $params[0])->whereMonth('created_at', $params[1]);
                    break;
                case 'year':
                    $query = $query->whereYear('created_at', $search['date']);
                    break;
            }
        }
        //患者评论列表12
        if(isset($search['comment']) &&  $search['comment'] == 1){
            if(!empty($search['name'])){
                $query->where('disease', 'like', '%' . $search['name'] . '%');
            }
            if(!empty($search['condition'])){
                $query->where('condition',$search['condition']);
            }
            $data = $query->paginate(10);
            return $data;
        }

        $data = $query->get();
        $disease = $data->groupBy('disease');

        $diseaseArr = [];
        foreach ($disease as $k => $v) {
            $diseaseArr[] = ['name' => $k, 'sum' => count($v), 'data' => $v];
        }
        unset($disease);
        $handleSortData = collect($diseaseArr)->sortByDesc('sum');
        unset($diseaseArr);
        $retArr =[];
        foreach ($handleSortData as $v) {
            $retArr[] = array_merge([$v['name']], Tools::handleData($v['data'], 1));
        }

        return ['condition' => !empty($data) ? Tools::handleData($data) : [], 'disease' => collect($retArr)->reverse()->toArray()];

    }
}
