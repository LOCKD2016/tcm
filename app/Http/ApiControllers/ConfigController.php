<?php

namespace App\Http\ApiControllers;

use App\Repository\ConfigRepository;
use App\Transformers\ConfigTransformer;

/**
 * Class ConfigController
 * @package App\Http\ApiControllers
 */
class ConfigController extends Controller
{
    /**
     * @var ConfigRepository
     */
    public $model;

    /**
     * ConfigController constructor.
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->model = $configRepository;
    }

    /**
     * 获取注册协议
     * @return \Dingo\Api\Http\Response
     */
    public function agreement()
    {
        $data = $this->model->get_data_by_key('app_agreement');

        if (empty($data)) {
            return $this->error(404, '数据不存在');
        }

        return $this->response()->item($data, new ConfigTransformer());
    }

}