<?php

namespace App\Http\ApiControllers;


use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    public function success($data=null,$message='ok'){
        return $this->send(1,200,$message,$data);
    }

    public function error($code,$message='error',$data=''){
        return $this->send(0,$code,$message,$data);
    }

    public function send($status,$code,$message='ok',$data=''){
        $senddata = [
            'status'=>$status,
            'errcode'=>$code,
            'msg'=>$message,
            'data'=>$data,
        ];
        return $this->response()->array($senddata);
    }

}
