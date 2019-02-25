<?php

namespace App\Http\WxControllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class Controller
 * @package App\Http\WxControllers
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;

    /**
     * 模型
     * @var
     */
    protected $model;

    /**
     * @param null $data
     * @param string $message
     * @return mixed
     */
    public function success($data = null, $message = 'ok')
    {
        return $this->send(1, 200, $message, $data);
    }

    /**
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

    /**
     * @SWG\Swagger(
     *      schemes={"http"},
     *      host="tcm.dev",
     *      basePath="/api/",
     *      produces={"application/x.daguoyi.wxv1+json"},
     *      consumes={"application/json"},
     *      @SWG\Info(
     *          version="1.0.0",
     *          title="微信端API 接口文档",
     *      ),
     * )
     */

    /**
     * Authorized
     * @SWG\SecurityScheme(
     *   securityDefinition="petstore_auth",
     *   type="oauth2",
     *   authorizationUrl="http://swagger.dev/oauth/authorize ",
     *   tokenUrl="http://swagger.dev/oauth/token",
     *   flow="password",
     *   scopes={
     *     "read:pets": "read your pets",
     *     "write:pets": "modify pets in your account"
     *   }
     * )
     */

}
