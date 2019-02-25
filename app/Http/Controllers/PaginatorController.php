<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/22 0022
 * Time: 17:39
 */
namespace App\Http\Controllers;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class PaginatorController extends  Controller  {

    //自定义分页类
    public static function index($data,$current_page=1,$perPage=10){
        if(is_object($data)){
            $data = $data->toArray();
        }
        $item = array_slice($data, ($current_page-1)*$perPage, $perPage); //处理返回的数据
        $total = count($data);
        $paginator =new LengthAwarePaginator($item, $total, $perPage, $current_page, [
            'path' => Paginator::resolveCurrentPath(), //点击上一页 下一页的链接
            'pageName' => 'page',
        ]);
        //dd($paginator);
        return $paginator;
    }
}