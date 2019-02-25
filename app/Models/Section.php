<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Section
 * @package App\Models
 */
class Section extends Model
{
    /**
     * @var string
     */
    protected $table = 'sections';

    /**
     * @var array
     */
    public $fillable = [
        'name', 'sort', 'status'
    ];

    /**
     * 疾病
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Disease()
    {
        return $this->hasMany(Disease::class);
    }

    /**
     * 通过 名称获取编号
     * @desc 如果没找到 就创建新想
     * @func Disease::getIdByName('感冒')
     * @param $name
     * @return mixed
     */
    protected function getIdByNameWithCreate($name)
    {
        $section = self::where('name', $name)->first() ?: self::create(['name' => $name]);

        return $section->id;
    }
}