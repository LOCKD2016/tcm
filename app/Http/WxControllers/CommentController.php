<?php

namespace App\Http\WxControllers;

use App\Repository\ClinicRepository;
use App\Repository\CommentRepository;
use App\Http\Requests\CommentSaveRequest;

/**
 * Class CommentController
 * @package App\Http\WxControllers
 */
class CommentController extends Controller
{
    /**
     * @var CommentRepository
     */
    public $model;

    /**
     * CommentController constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->model = $commentRepository;
    }

    /**
     * 评论
     * @param CommentSaveRequest $request
     * @param ClinicRepository $clinicRepository
     * @param $clinic_id
     * @return mixed
     */
    public function save(CommentSaveRequest $request, ClinicRepository $clinicRepository, $clinic_id)
    {
        $clinic = $clinicRepository->get_data_by_id($clinic_id);

        if (empty($clinic)) return $this->error(404, '诊疗不存在');

        //能不能评论
        if (!$clinic->comment || $clinic->user_id != \Auth::id()) return $this->error(403, '诊疗不能评价');

        //评论
        $comment = $this->model->create(array_merge(
            [
                'clinic_id' => $clinic_id, 'disease' => $clinic->disease,
                'doctor_id' => $clinic->doctor_id, 'user_id' => \Auth::id()
            ]
            , $request->all()
        ));

        if ($comment) {
            $clinic->loadEditData(['comment' => '0'])->save();

            return $this->success([], '评价成功');
        }

        return $this->error(500, '评价失败，请稍后重试');
    }
}