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
class Schedules extends Model
{
    /**
     * @Auth: kingofzihua
     * @var string
     */
    protected $table = 'Schedules';

    /**
     * 关联诊所
     * @Auth: kingofzihua
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clinque()
    {
        return $this->hasOne(Clinique::class, 'id', 'clinique_id');
    }



}