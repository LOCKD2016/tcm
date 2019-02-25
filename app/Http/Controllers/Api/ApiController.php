<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;

abstract class ApiController extends Controller
{
    use Helpers;

    public function success($data = null, $message = 'ok')
    {
        return $this->send(1, 200, $message, $data);
    }

    public function error($code, $message = 'error', $data = '')
    {
        return $this->send(0, $code, $message, $data);
    }

    public function send($status, $code, $message = 'ok', $data = '')
    {
        $senddata = [
            'status' => $status,
            'errcode' => $code,
            'msg' => $message,
            'data' => $data,
        ];
        return $this->response()->array($senddata);
    }
}