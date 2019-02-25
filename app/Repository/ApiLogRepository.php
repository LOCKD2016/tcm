<?php

namespace App\Repository;

use App\Models\ApiLog;

/**
 * Class ApiLogRepository
 * @package App\Repository
 */
class ApiLogRepository extends Repository
{
    /**
     * 记录 soap的日志
     * @param $method 请求的方法
     * @param array $send 发送的数据
     * @param array $return 返回的数据
     * @param $status 状态
     * @return static
     */
    protected function soapLog($method, array $send, array $return, $status)
    {

        $data = [
            'method' => $method,
            'type' => 'send',
            'send' => json_encode($send),
            'return' => json_encode($return),
            'status' => $status,
        ];

        return $this->model->create($data);
    }

    /**
     * @return ApiLog
     */
    public function model()
    {
        return new ApiLog();
    }
}