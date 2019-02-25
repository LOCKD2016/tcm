<?php

namespace App\Http\Controllers;

use App\Util\Tools;
use Illuminate\Http\Response;
use App\Http\Requests\SMSCodeRequest;
use App\Repository\SMSCodeRepository;

/**
 * Class SMSCodeController
 * @package App\Http\Controllers
 */
class SMSCodeController extends Controller
{
    /**
     * @var SMSCodeRepository
     */
    protected $model;

    /**
     * SMSCodeController constructor.
     * @param $model
     */
    public function __construct(SMSCodeRepository $model)
    {
        $this->model = $model;
    }

    /**
     * @param SMSCodeRequest $request
     * @return mixed
     */
    public function send(SMSCodeRequest $request)
    {
        $data = [
            'ip' => $request->getClientIp(),
            'code' => Tools::randCode(6),
            'device_id' => $request->header('x-device-id'),
            'system_type' => $request->header('x-system-type') . " " . $request->header('x-system-version'),
            'useragent' => $request->header('user-agent'),
        ];

        $createData = $this->model->create(array_merge($request->only(['mobile', 'type']), $data));

        if ($createData) {
            $ret = Tools::tcmSendSms($request->mobile,
                "您的短信验证码是:{$data['code']}。请勿向任何人泄露。如非本人操作,请忽略该短信。【泰和国医】");

            return ['status' => 1, 'msg' => '发送成功', 'errcode' => 200, 'data' => []];
        } else {
            return ['status' => 0, 'msg' => '发送失败', 'errcode' => 500];
        }
    }
}