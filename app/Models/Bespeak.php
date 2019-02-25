<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * 预约
 * Class Bespeak
 * @Auth: kingofzihua
 * @package App\Models
 */
class Bespeak extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'bespeaks';

    /**
     * @Auth: kingofzihua
     * @var array
     */
    protected $fillable = [
        'disease', 'redundant_first', 'redundant_in', 'doctor_id',
        'type', 'start_time', 'user_id', 'status', 'bespeak_code', 'clinique_id',
        'end_time'
    ];

    /**
     * 用户
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class, 'user_id', 'id');
    }

    /**
     * 医生
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * 诊疗
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clinic()
    {
        return $this->hasOne(Clinic::class);
    }

    /**
     * 标准问诊单
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inquiry()
    {
        return $this->hasOne(Inquiry::class);
    }

    /**
     * 订单
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    /**
     * 预约的门店
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinique()
    {
        return $this->belongsTo(Clinique::class);
    }

    /**
     * 管理员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    /**
     * 预约医生
     * @param $query
     * @param $doctor_id
     * @return string
     */
    public function scopeQueryDoctor($query, $doctor_id = '')
    {
        return $doctor_id != '' ? $query->where('doctor_id', $doctor_id) : '';
    }

    /**
     * 预约医生
     * @param $query
     * @param $doctor_id
     * @return string
     */
    public function scopeQueryUser($query, $user_id = '')
    {
        return $user_id != '' ? $query->where('user_id', $user_id) : '';
    }

    /**
     * 预约类型
     * @param $query
     * @param $type
     */
    public function scopeQueryType($query, $type = '')
    {
        return $type !== '' ? $query->where('type', $type) : '';
    }

    /**
     * 预约状态 小于
     * @param $query
     * @param $type
     */
    public function scopeQueryStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : '';
    }

    /**
     * 预约状态 小于
     * @param $query
     * @param $type
     */
    public function scopeQueryStatusLt($query, $status)
    {
        return $status ? $query->where('status', '<', $status) : 25;
    }

    /**
     * 预约状态 小于
     * @param $query
     * @param $type
     */
    public function scopeQueryStatusSt($query, $status)
    {
        return $status ? $query->where('status', '>', $status) : 10;
    }

    /**
     * 根据日期获取当天的预约
     * @param $query
     * @param $type
     */
    public function scopeQueryStartDate($query, $date)
    {
        return $date ? $query->whereDate('start_time', $date) : '';
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

    /****************************************************后台***********************************************************************************/

    /**
     * 预约类型
     * @param $query
     * @param $type
     */
    public function scopeBespeakType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * 预约状态
     * @param $query
     * @param $status
     */
    public function scopeBespeakStatus($query, $status)
    {
        return $status === '' ? '' : $query->where('status', $status);
    }

    /**
     * 预约时间
     * @param $query
     * @param $status
     */
    public function scopeBespeakTime($query, $time)
    {
        return empty($time) ? '' : $query->whereDate('created_at',  $time);
    }

    /**
     * 患者姓名
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeBespeakName($query, $name)
    {
		return $name ? $query->WhereHas('user', function ($que) use ($name) {

			$que->where('realname', 'like', '%' . $name . '%')->orWhere('nickname', 'like', '%' . $name . '%');

		}) : '';
    }

    /**
     * 医生姓名
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeBespeakDoctorName($query, $name)
    {
		return $name ? $query->WhereHas('doctor', function ($que) use ($name) {

			$que->where('name', 'like', '%' . $name . '%');

		}) : '';
    }

    /**
     * 订单状态
     * @Auth: Nnn
     * @param $query
     * @param $name
     * @return string
     */
    public function scopeOrderStatus($query, $pay_status)
    {
        return $pay_status === '' ? '' : $query->WhereHas('order', function ($que) use ($pay_status) {

            $que->where('status', $pay_status);

        });
    }

    /**
     * 修改预约信息
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateData($id, $data)
    {
        return Bespeak::where('id', $id)->update($data);
    }

    /**
     * 按照时间倒序排
     * @param $query
     */
    public function scopeOrderDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }



    //******************************************************APP开始(Auth: lx)*************************************************

    /**
     * 详情
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getFind($id)
    {
        return Bespeak::find($id);
    }

    /**
     * 获取网络预约列表
     */
    public function get_web_lists()
    {
        return Bespeak::where('doctor_id', Auth::id())->with('user')->paginate(10);

        //return Bespeak::where('doctor_id', Auth::id())->where('status', ' < ', '15')->BespeakType(0)->with('user')->paginate(10);
    }

    /**
     * 获取门诊预约列表
     */
    public function get_clinic_lists($arr)
    {
        $sql = Bespeak::where('doctor_id', Auth::id())->BespeakType(1)->with('user');

        if (empty($arr['date']))
            $arr['date'] = date('Y - m - d', time());

        if (!isset($search['count'])) //判断是否是统计所有的数量
            $sql = $sql->whereDate('start_time', $arr['date']);

        return $sql->paginate(10);
    }

    /**
     * 预约人数
     */
    public function nums()
    {
        return [
            'web_num' => collect($this->get_web_lists())->toArray()['total'],
            'clinic_num' => collect($this->get_clinic_lists(['count' => 1]))->toArray()['total']
        ];
    }

    /**
     * 修改状态
     */
    public function setStatus($id, $data)
    {
        $bespeak = $this->bespeak->find($id);
        if (!empty($bespeak)) {

            if ($bespeak->doctor_id == Auth::id()) {

                return Bespeak::where('id', $id)->update($data) ?: false;

            } else {

                return false;

            }

        }

        return false;

    }

    //******************************************************APP结束*************************************************

}