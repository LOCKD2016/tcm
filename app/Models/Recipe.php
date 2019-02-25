<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * Class Recipe
 * @package App\Models
 */
class Recipe extends Model
{
    /**
     * @var string
     */
    protected $table = "recipe";

    /**
     * @var array
     */
    protected $casts=[
        'content'=>'json',
    ];

    /**
     * @var array
     */
    protected $fillable=[
        'title','content','doctor_id','type'
    ];

    /**
     * 获取诊疗
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    /**
     * 就诊人
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo(Family::class, 'family_id', 'id');
    }

    /**
     * 是否快递
     * @param $query
     */
    public function scopeIsExpress($query)
    {
        $query->where('is_express', '=', '1');
    }

    /**
     * 药方过期
     * @Auth: kingofzihua
     * @return bool
     */
    public function overdue()
    {
        $this->is_price = 6;
        return $this->save();
    }
//*******************************************************APP(@Auth:lx)********************************************

    /**
     * 处方名称
     * @param $query
     * @param $title
     */
    public function scopeTitle($query, $title)
    {
        return $title === '' ? '' : $query->where('title', 'like', '%' . $title . '%');

    }

    /**
     * 处方类型
     * @param $query
     * @param $type(0:系统处方 1:医生处方)
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);

    }

    /**
     * 处方状态
     * @param $query
     * @param $status(1:正常 0:禁用)
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);

    }

    /**
     * 添加处方
     */
    public function cresteDate($data)
    {
        return Recipe::create($data);
    }

    /**
     * 修改处方
     * @param $id,$data
     */
    public function updateDate($id,$data)
    {
        return Recipe::where('id',$id)->update($data);
    }

    /**
     * 处方详情
     * @param $id
     */
    public function getFind($id)
    {
        return Recipe::find($id);
    }

    /**
     * 删除
     * @param id
     */
    public function deleteRecipe($id)
    {
        return Recipe::where('id', $id)->delete();

    }



    /**
     * 验证数据
     * @param $data
     */
    public function Verification_data($data)
    {
        return Validator::make($data, [
            'title' => 'required', //姓名
            'content' => 'required', //姓名
        ], [
            'title.required' => '处方名必填',
            'content.required' => '药材必填',
        ]);
    }






}
