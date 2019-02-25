<?php

namespace App\Repository;

use App\Models\Exam;
use App\Models\AppUser;
use App\Models\ExamOption;

/**
 * Class ExamRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class ExamRepository extends Repository
{

    /**
     * 创建 option对象 用户批量添加
     * @param $data
     * @return ExamOption
     */
    public function builderOption($data)
    {
        return new ExamOption($data);
    }

    /**
     * 获取系统数据
     * @return mixed
     */
    public function get_data_by_type($type)
    {
        return $this->model->queryType($type)->get();
    }

    /**
     * 通过类型获取登录用户的问诊单 第一条
     * @param $type
     * @return mixed
     */
    public function get_auth_data_by_type($type)
    {
        return $this->model->queryType($type)->queryDoctorId(\Auth::id())->first();
    }

    /**
     * 通过医生编号
     * @param $doctor_id
     * @return mixed
     */
    public function get_data_lists_by_doctor_id($doctor_id)
    {
        return $this->model->queryDoctorId($doctor_id)->paginate($this->page);
    }

    /**
     * 通过医生和类型来获取问诊单
     * @param $doctor_id
     * @param $type
     * @return mixed
     */
    public function get_data_by_doctor_and_type($doctor_id, $type)
    {
        return $this->model->queryDoctorId($doctor_id)->queryType($type)->first();
    }

    /**
     * 获取问诊单的类型
     * @param AppUser $user
     * @return int
     */
    public function get_exam_type(AppUser $user)
    {

        $age = (strtotime(date("Y-m-d", time())) - strtotime($user->birthday)) / (365 * 24 * 3600);

        if ($age <= AppUser::CHILDREN) return 3; //儿童

        if ($user->sex == AppUser::SEX_MAN) return 1;//男

        if ($user->sex == AppUser::SEX_WMAN) return 2; //女

        return 0;
    }

    /**
     * @Auth: kingofzihua
     * @return Exam
     */
    public function model()
    {
        return new Exam();
    }

    /**
     * 更新问诊单
     * @param Exam $exam
     * @param $option
     * @return array|\Traversable
     */
    public function update_exam_option(Exam $exam, $option ,$is_delete)
    {

        //首先创建题目
        $options = $this->format_exam_option($option, $exam->id);

        //获取原来的题目的ids
        $old_option_ids = $this->get_option_ids($exam->options());

        //删除就数据  如果没人做过 就直接删除  做过就软删除
        if($is_delete){
            ExamOption::whereIn('id', $old_option_ids)->delete();
        }else{
            ExamOption::whereIn('id', $old_option_ids)->forceDelete();
        }

        //创建新数据
        return $exam->options()->saveMany($options);
    }

    /**
     * 获取 选项的ids
     * @param $options
     * @return array
     */
    public function get_option_ids($options)
    {
        return $options->pluck('id');
    }

    /**
     * 创建 题目
     * @param $option
     * @param $exam_id
     * @return array[new ExamOption()]
     */
    public function format_exam_option($option, $exam_id)
    {
        $must = 0;
        return array_map(function ($val) use ($exam_id, &$must) {
            return new ExamOption(array_merge($val, ['exam_id' => $exam_id, 'sort' => ++$must])); //排序自增
        }, $option);
    }

}