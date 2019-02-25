<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CliniqueRecipe extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'clinique_recipe';

    /**
     * @var string
     */
    protected $primaryKey = 'id';



    protected $guarded = [];

}
