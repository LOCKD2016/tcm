<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;

/**
 * Api的基础控制器
 * Class ApiBaseController
 * @package App\Http\Controllers
 */
abstract class ApiBaseController extends Controller
{
    use Helpers;

    /**
     * 成功
     * @param null $data
     * @param string $message
     * @return mixed
     */
    public function success($data = null, $message = 'ok')
    {
        return $this->send(1, 200, $message, $data);
    }

    /**
     * 失败
     * @param $code
     * @param string $message
     * @param string $data
     * @return mixed
     */
    public function error($code, $message = 'error', $data = '')
    {
        return $this->send(0, $code, $message, $data);
    }

    /**
     * 发送
     * @param $status
     * @param $code
     * @param string $message
     * @param string $data
     * @return mixed
     */
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