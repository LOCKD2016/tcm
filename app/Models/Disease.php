<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Disease
 * @Auth: kingofzihua
 * @package App\Models
 */
class Disease extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'disease';

    /**
     * @var array
     */
    public $fillable = [
        'name', 'section_id'
    ];

    /**
     * 通过 名称获取编号
     * @desc 如果没找到 就创建新想
     * @func Disease::getIdByName('感冒')
     * @param $name
     * @return mixed
     */
    protected function getIdByNameWithCreate($name)
    {
        $disease = self::where('name', $name)->first() ?: self::create(['name' => $name]);

        return $disease->id;
    }

    /**
     * 通过科室id获取疾病信息
     * @param $id
     * @return mixed
     */
    public function get_disease_by_section_id($id)
    {
        return Disease::where('section_id', $id)->get();
    }

    /**
     * 删除疾病
     * @param $id
     * @return mixed
     */
    public function delete_disease_by_id($id)
    {
        return self::where('id', $id)->delete();
    }
}
