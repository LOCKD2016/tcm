<?php
namespace App\Http\PlatformControllers;

use Dingo\Api\Routing\Helpers;
/**
 * Class Controller
 * @Auth: kingofzihua
 * @package App\Http\PlatformControllers
 */
abstract class Controller extends \App\Http\Controllers\Controller
{
    use  Helpers;

    /**
     * @Auth: kingofzihua
     * @param null $data
     * @param string $message
     * @return mixed
     */
    public function success($data = null, $message = 'ok')
    {
        return $this->send(1, 200, $message, $data);
    }

    /**
     * @Auth: kingofzihua
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
     * @Auth: kingofzihua
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
