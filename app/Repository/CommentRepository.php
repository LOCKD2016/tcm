<?php

namespace App\Repository;

use App\Models\Comment;

/**
 * 评论
 * Class CommentRepository
 * @package App\Repository
 */
class CommentRepository extends Repository
{
    /**
     * @return Comment
     */
    public function model()
    {
        return new Comment();
    }

    /**
     * 获取医生 审核通过状态的 评论 的平均值
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/26 18:27
     * @param $doctor_id
     */
    public function get_level_avg_by_doctor_id($doctor_id)
    {
        return $this->model()->where(['doctor_id' => $doctor_id, 'status' => 1])->avg('manner');
    }
}