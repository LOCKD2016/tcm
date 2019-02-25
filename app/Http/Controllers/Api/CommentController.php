<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Repository\CommentRepository;
use App\Repository\DoctorRepository;
use Illuminate\Http\Request;
use App\Transformers\Api\CommentTransformer;

class CommentController extends ApiController
{
    /**
     * @var Comment
     */
    protected $comment;

    protected $page = 10;

    /**
     * CommentController constructor.
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * 评论列表
     * @param Request $request
     */
    public function index(Request $request)
    {
        $data = $request->all();

        $lists = $this->comment
            ->commentDisease($data['search']['disease'] ?? '')
            ->commentDoctor($data['search']['doctor'] ?? '')
            ->commentUser($data['search']['name'] ?? '')
            ->commentCondition($data['search']['condition'] ?? '')
            ->commentStart($data['search']['start'] ?? '')
            ->commentEnd($data['search']['end'] ?? '')
            ->orderBy('id', 'desc')
            ->paginate($this->page);

        return $this->response()->paginator($lists, new CommentTransformer());
    }

    /**
     * 审核评论状态
     * @param Request $request
     */
    public function save($id, Request $request)
    {
        $comment = $this->comment->find($id);

        if (!$comment) {
            return $this->error(100, '该评论不存在');
        }

        $comment->status = $request->status;

        if ($comment->save()) {
            // 更新 医生星级评定
            $re = (new DoctorRepository())->update_doctor_level($comment->doctor_id);
            if ($re){
                return $this->success(200, '操作成功');
            }else{
                return $this->error(100, '操作失败');
            }
        }

        return $this->error(100, '操作失败');
    }
}
