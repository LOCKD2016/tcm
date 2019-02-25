<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 16/9/8
 * Time: 下午8:21
 */

namespace App\Http\Controllers\Api;

use App\Models\Logs;
use App\Transformers\Api\LogsTransformer;
use Auth;

class LogsController extends ApiController
{
    private $log;

    public function __construct(Logs $log)
    {
        $this->log = $log;
    }

    /**
     * 获取自己的登录日志
     * @param Logs $logs
     */
    public function getLogs(Logs $logs)
    {
        return $this->response()->paginator(
            $logs->getUserLogs(Auth::id()),
            new LogsTransformer()
        );
    }

    /**
     * 获取所有人的登录日志
     * 管理员查看
     */
    public function index(Logs $logs)
    {
        return $this->response()->paginator(
            $logs->getAllLogs(),
            new LogsTransformer()
        );
    }

    public function test()
    {
        $pwd = '$2y$10$FGB2jUv5lOCbgEpzifQ/DeyD9WyLucFpdRe9znmMvEREzsIdDCLMK';//;app("hash")->make("1234655BuO4B");

        dd(app("hash")->check('123465vHj7k9', $pwd));
    }

    //查看日志详情

    public function show($id)
    {
        return $this->response()->array($this->log->getLogDetail($id));

    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function job_field()
    {
        return \DB::table('field_log')->orderBy('id', 'desc')->paginate(10);
    }
}