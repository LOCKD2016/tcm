<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CliniqueExam extends Model
{
    //

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'clinique_exam';

    /**
     * @var string
     */
    protected $primaryKey = 'id';



    protected $guarded = [];
}
