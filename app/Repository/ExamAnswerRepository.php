<?php

namespace App\Repository;

use App\Models\ExamAnswer;

/**
 * Class ExamAnswerRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class ExamAnswerRepository extends Repository
{
    /**
     * 通过诊疗获取答案
     * @param $clinic_id
     * @return \Illuminate\Support\Collection
     */
    public function get_answer_by_clinic($clinic_id)
    {
        return $this->model->queryClinicId($clinic_id)->select();
    }

    /**
     * 通过诊疗删除所填写的问诊单
     * @param $clinic_id
     * @return mixed
     */
    public function delete_answer_by_clinic($clinic_id)
    {
        return $this->model->queryClinicId($clinic_id)->delete();
    }

    /**
     * @Auth: kingofzihua
     * @return ExamAnswer
     */
    public function model()
    {
        return new ExamAnswer();
    }

}