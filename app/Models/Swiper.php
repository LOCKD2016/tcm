<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Swiper extends Model
{
    /**
     * @Auth: Nnn
     * @var string
     */
    protected $table = 'swiper';

    public $fillable = [
        'title', 'desc', 'image', 'url', 'status'
    ];


}
