<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Http\Request;
use App\Models\OperationLog;
use App\Transformers\OperationLogTransformer;

class OperationLogController extends ApiController
{
    protected $operationlog;

    public function __construct(OperationLog $operationlog)
    {
        $this->operationlog = $operationlog;
    }

    protected $messages = [
        'operation_detail.required' => '操作内容不能为空',
        'send_people.required' => '发送人不能为空',
        'receive_people.required' => '接收人不能为空'
    ];

    /**
     * 获取操作日志列表
     * @param Request $request
     * @param $id
     */
    public function index(Request $request)
    {
//        return $this->operationlog->getAllList($request->all());
        return $this->response()->paginator($this->operationlog->getAllList($request->all()), new OperationLogTransformer());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operation_detail' => 'required',
            'send_people' => 'required',
            'receive_people' => 'required',
        ], $this->messages);
        if ($validator->fails()) {
            $msg = $validator->errors()->toArray();
            return array_pop($msg);
        }
        $row = $this->operationlog->add($request->all());
        if ($row == false) return $this->error(0);
        return $this->success();
    }

    public function show($id)
    {
        return $this->response()->item($this->operationlog->getOne($id), new OperationLogTransformer());
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            'content' => 'required',
        ], $this->messages);
        if ($validator->fails()) {
            $msg = $validator->errors()->toArray();
            return array_pop($msg);
        }
        $row = $this->operationlog->doUpdate($request->all());
        if ($row == false) return $this->error(0);
        return $this->success();
    }

    public function destroy($id)
    {
        $row = $this->operationlog->del($id);
        if ($row == false) return $this->error(0);
        return $this->success();
    }

    public function read($id)
    {
        $row = $this->operationlog->read($id);
        if ($row == false) return $this->error(0);
        return $this->success();
    }

    public function count()
    {
        return $this->response()->array($this->operationlog->count());
    }

    public function doSearch(Request $request)
    {
        return $this->response()->array($this->operationlog->doSearch($request->all()));
    }
}
