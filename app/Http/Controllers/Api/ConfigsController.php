<?php

namespace App\Http\Controllers\Api;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigsController extends ApiController
{
    /**
     * @var Comment
     */
    protected $config;

    protected $page = 20;

    /**
     * CommentController constructor.
     * @param Comment $comment
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 获取app和微信端注册协议
     * @param Request $request
     */
    public function agreementIndex()
    {
        $data = Config::whereIn('key', ['app_agreement', 'wechat_agreement'])->get();

        return $this->success($data);
    }

    /**
     * 用户协议修改
     * @param Request $request
     */
    public function agreementEdit(Request $request)
    {

        $config = $this->config->find($request->id);

        if (!$config) {
            return $this->error(100, '该协议不存在');
        }

        $config->value = $request->value;

        if ($config->save()) {
            return $this->success(200, '操作成功');
        }

        return $this->error(100, '操作失败');
    }


}
