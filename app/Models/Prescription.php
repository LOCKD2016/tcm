<?php

namespace App\Models;

use Validator;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Prescription
 * @package App\Models
 */
class Prescription extends Model
{
    /**
     * @var string
     */
    protected $table = 'prescription';

    /**
     * @var array
     */
    protected $fillable = [
        'title', 'detail', 'doctor_id', 'clinic_id', 'recipe_head', 'recipe', 'recipe_remark', 'disease','disease_en','disease_zh', 'user_id', 'medicine_price', 'order_id', ''
    ];

    /**
     * @var array
     */
    protected $casts = [
        'detail' => 'json',
        'recipe' => 'array',
        'recipe_head' => 'array',
    ];

    /**
     * 管理员
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    /**
     * 订单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    /**
     * 医生
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * 患者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(AppUser::class);
    }

    /**
     * 诊疗
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * 获取时间 将创建时间转化下
     * @return false|string
     */
    public function getTimeAttribute()
    {
        return date('Y-m-d H:i:s', strtotime($this->created_at));
    }

    /**
     * 处方类型
     * @param $query
     * @param int $type 0医生 1系统处方
     */
    public function scopeQueryTypes($query, $type = 0)
    {
        return $query->where('type', '=', $type);
    }

    /**
     * 按照订单编号查询
     * @param $query
     * @param $order_id
     * @return string
     */
    public function scopeQueryOrderId($query, $order_id)
    {
        return $order_id ? $query->where('order_id', $order_id) : '';
    }

    /**
     * 按照诊疗编号查询
     * @param $query
     * @param $clinic_id
     * @return string
     */
    public function scopeQueryClinicId($query, $clinic_id)
    {
        return $clinic_id ? $query->where('clinic_id', $clinic_id) : '';
    }

    /**
     * 患者姓名
     * @param $query
     * @param $name
     */
    public function scopeQueryUserName($query, $name)
    {
        return $name ? $query->WhereHas('user', function ($que) use ($name) {

            $que->where('realname', 'like', '%' . $name . '%');

        }) : '';
    }

    /**
     * 医师姓名
     * @param $query
     * @param $name
     */
    public function scopeQueryDoctorName($query, $name)
    {
        return $name ? $query->WhereHas('doctor', function ($que) use ($name) {

            $que->where('name', 'like', '%' . $name . '%');

        }) : '';
    }

    /**
     * 医生编号
     * @param $query
     * @param $name
     */
    public function scopeQueryDoctorCustomerCode($query, $CustomerCode)
    {
        $id = \DB::table('doctor_clinique')->where('code', $CustomerCode)->value('doctor_id');

        return $CustomerCode ? $query->WhereHas('doctor', function ($que) use ($id) {

            $que->where('id',  $id);

        }) : '';
    }

    /**
     * 患者编号
     * @param $query
     * @param $name
     */
    public function scopeQueryUserCustomerCode($query, $CustomerCode)
    {
        return $CustomerCode ? $query->WhereHas('user', function ($que) use ($CustomerCode) {

            $que->where('customer_code', 'like', '%' . $CustomerCode . '%');

        }) : '';
    }
//***************************************************APP开始(@Auth:lx)*****************************************************************

    /**
     * @param $id
     * @return mixed
     */
    public function getFind($id)
    {
        return Prescription::find($id);
    }

    public function update_prescription_data($id, $data)
    {
        return Prescription::where('id', $id)->update($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function creates($data)
    {
        return Prescription::create($data);
    }

    /**
     * @param $data
     * @return mixed 123123
     */
    public function up($id, $data)
    {
        $data['recipe'] = json_encode($data['recipe']);
        $data['recipe_head'] = json_encode($data['recipe_head']);
        return Prescription::where('id', $id)->update($data);
    }


    /**
     * 查询是否开过药方
     * @param $clinic_id
     * @return mixed
     */
    public function get_clinic_id($clinic_id)
    {
        return Prescription::where('clinic_id', '=', $clinic_id)->first();
    }

    /**
     * 添加验证
     */
    public function verification_data($data)
    {
        return Validator::make($data, [
            'clinic_id' => 'required', //诊疗ID
            'recipe_head' => 'required', //计量
            'recipe' => 'required', //药方
            'recipe_remark' => 'required', //医嘱
            'disease' => 'required', //中医辩证
            //'disease_zh' => 'required', //中医诊断
            'disease_en' => 'required' //西医诊断
        ], [
            'clinic_id.required' => '诊疗编号必填',
            'recipe_head.required' => '计量必填',
            'recipe.required' => '药方必填',
            'recipe_remark.required' => '医嘱必填',
            'disease.required' => '中医辩证必填', //中医辩证
            //'disease_zh.required' => '中医诊断必填', //中医诊断
            'disease_en.required' => '西医诊断必填' //西医诊断
        ]);
    }

    /**
     * 添加验证
     */
    public function verification_edit($data)
    {
        return Validator::make($data, [
            'recipe_head' => 'required', //计量
            'recipe' => 'required', //药方
            'recipe_remark' => 'required',
            'disease' => 'required', //中医辩证
            //'disease_zh' => 'required', //中医诊断
            'disease_en' => 'required' //西医诊断
        ], [
            'recipe_head.required' => '计量必填',
            'recipe.required' => '药方必填',
            'recipe_remark.required' => '医嘱必填',
            'disease.required' => '中医辩证必填', //中医辩证
            //'disease_zh.required' => '中医诊断必填', //中医诊断
            'disease_en.required' => '西医诊断必填' //西医诊断
        ]);
    }


//***************************************************APP结束***************************************************************************
}
