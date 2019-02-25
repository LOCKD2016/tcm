<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AppUser
 * @package App\Models
 */
class AppUser extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    /**
     * 未知
     */
    const SEX_UNKNOWN = 0;

    /**
     * 男
     */
    const SEX_MAN = 1;

    /**
     * 女
     */
    const SEX_WMAN = 2;

    /**
     * 儿童的年龄
     */
    const CHILDREN = 16;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'app_users';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return mixed
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getAuthSalt()
    {
        return $this->salt;
    }

    /**
     * 定义年龄属性访问修改器
     * @return string
     */
    public function getAgeAttribute()
    {
        return isset($this->birthday) ? Carbon::parse($this->birthday)->diffInYears() : '未知';
    }

    /**
     * 获取 性别
     * @param null $ind
     * @return array|mixed
     */
    public function sex($ind = null)
    {
        $arr = [
            self::SEX_UNKNOWN => '未知',
            self::SEX_MAN => '先生',
            self::SEX_WMAN => '女士',
        ];

        if ($ind !== null)
            return array_key_exists($ind, $arr) ? $arr[$ind] : $arr[self::SEX_UNKNOWN];

        return $arr;
    }

    /**
     * 通过诊所编号获取用户的code
     * @param $clinique_id
     * @return [
     *      'userCode',//用户所在门店的code
     *      'cliniqueCode',//门店的code
     * ]
     */
    public function getCodeDateByCliniqueId($clinique_id)
    {
        $clinique = $this->cliniques()->where('id', $clinique_id)->first();

        return $clinique ? [$clinique->pivot->code, $clinique->code] : [null, null];
    }

    /**
     * follow医生
     * @param $doctor_id 医生编号
     * @param int $status 状态 0:仅关注 1:看病的医生
     * @return boolean true || false
     */
    public function followDoctor($doctor_id, $status = '')
    {
        //可能报错 因为 联合主键
        try {
            $this->follow()->attach($doctor_id);
        } catch (QueryException $exception) {
            if ($exception->getCode() !== "23000") return false; //判断下 如果错误不是因为 联合主键约束 就停止
        }

        if ($status !== '') $this->follow()->updateExistingPivot($doctor_id, ['status' => $status]);

        return true;
    }

    /**
     * 用户是否完善信息
     * @return bool true(已完善)||false(未完善)
     */
    public function complete()
    {
        return !empty($this->realname) && !empty($this->sex);
    }

    /**
     * 根据手机号查询
     * @param $query
     * @param $mobile
     * @return mixed
     */
    public function scopeQueryMobile($query, $mobile)
    {
        return $mobile ? $query->where('mobile', $mobile) : '';
    }

    /**
     * 用户体征信息
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userHealth()
    {
        return $this->hasOne(UserHealth::class, 'user_id', 'id');
    }

    /**
     * 诊疗
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinics()
    {
        return $this->hasMany(Clinic::class, 'user_id', 'id')->with('doctor', 'prescription');
    }

    /**
     * 预约
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bespeaks()
    {
        return $this->hasMany(Bespeak::class, 'user_id', 'id');
    }

    /**
     * 订单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orders()
    {
        return $this->hasMany(Orders::class, 'user_id', 'id')->with('bespeak');
    }

    /**
     * 用户关注医生
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function follow()
    {
        return $this->belongsToMany(Doctor::class, 'user_follow', 'user_id', 'doctor_id');
    }

    /**
     * 诊所
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cliniques()
    {
        return $this->belongsToMany(Clinique::class, 'user_clinique', 'user_id', 'clinique_id')->withPivot(['code']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_user', 'user_id', 'group_id');
    }

    /**
     * 准备修改的数据
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

    /*************************************************************后台****************************************************************/


    /**
     * 患者名称
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeOrderByDesc($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 患者名称
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeName($query, $name)
    {
        return $name ? $query->where('nickname', 'like', '%' . $name . '%')->orWhere('realname', 'like', '%' . $name . '%') : '';
    }

    /**
     * 患者手机号
     * @Auth: Nnn
     * @param $query
     * @param $mobile
     * @return string
     */
    public function scopeMobile($query, $mobile)
    {
        return $mobile ? $query->where('mobile', 'like', '%' . trim($mobile) . '%') : '';
    }

    /**
     * 患者性别
     * @Auth: Nnn
     * @param $query
     * @param $sex
     * @return string
     */
    public function scopeSex($query, $sex)
    {
        return $sex === '' ? '' : $query->where('sex', $sex);
    }

    /**
     * 患者身份证号
     * @Auth: Nnn
     * @param $query
     * @param $idNo
     * @return string
     */
    public function scopeIdNo($query, $idNo)
    {
        return empty($idNo) ? '' : $query->where('idNo', 'like', '%' . trim($idNo) . '%');
    }


    /**
     * 泰和国医用户标识
     * @Auth: Nnn
     * @param $query
     * @param $idNo
     * @return string
     */
    public function scopeCustomerCode($query, $customer_code)
    {
        return empty($customer_code) ? '' : $query->where('customer_code', $customer_code);
    }


}
