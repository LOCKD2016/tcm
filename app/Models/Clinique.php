<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 诊所
 * Class Clinique
 * @package App\Models
 */
class Clinique extends Model
{
    /**
     * @var string
     */
    protected $table = 'cliniques';

    /**
     * @var array
     */
    protected $casts = [
        'content' => 'array'
    ];

    /**
     * @var array
     */
    public $fillable = [
        'name', 'address', 'content', 'code', 'id', 'telephone'
    ];

    public $paiban;

    /**
     * 查询门店编号
     * @Auth: kingofzihua
     * @param $query
     * @param $title_id
     * @return string
     */
    public function scopeQueryCode($query, $code)
    {
        return $code ? $query->where('code', '=', $code) : '';
    }


}
