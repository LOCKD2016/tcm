<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\MessageList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessagesController extends ApiController
{
    public $messageList;

    public $message;

    public function __construct(MessageList $messageList, Message $message)
    {
        $this->messageList = $messageList;
        $this->message = $message;
    }

    public function getMessagesList(Request $request)
    {
        $input = $request->all();
        $conds = [];
        if (!empty($input['name'])){
            $conds = ['name' => $input['name']];
        }
        $list = $this->messageList->whereHas('doctor', function ($query) use($conds) {
            $query->where('is_del', 0)->where($conds);
        })->with('doctor', 'user')->orderBy('id','desc')->paginate(15);

        return $this->success($list);
    }

    /**
     * 获取医生和用户聊天对话的内容详情
     */
    public function getMessagesDetail($id)
    {
        $list_id = $id;
        $detail = $this->message->where('list_id', $list_id)->where('msg_type', 'text')->get();

        return $this->success($detail);
    }
}
