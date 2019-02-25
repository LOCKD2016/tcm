<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**客服手机号
 * Class Admtelephones
 * @package App\Models
 */
class Admtelephones extends Model
{
//    public $connection = 'daguoyi';
    protected $table = 'adm_telephone';
    protected $primaryKey = 'id';
    protected $guarded = [];
    public $timestamps = true;

    /*客服电话列表*/
    public function getTelephoneList($fields = null)
    {
        $fields = ['adm_telephone.id', 'telephone', 'cliniques.created_at', 'name', 'address'];
        $telephone = Admtelephones::select($fields)
//                    ->offset($offset)
//                    ->limit($limit)
                    ->orderBy('adm_telephone.id', 'desc')
                    ->leftJoin('cliniques', 'clinique_id', '=', 'cliniques.id')
                    ->get()
                    ->toArray();
        return $telephone;
    }

    public function clinique()
    {
        return $this->hasOne(Clinique::class,'id','clinique_id');
    }

    public function get_list()
    {
        $list = Admtelephones::with('clinique')->get();
        return $list;
    }


}
