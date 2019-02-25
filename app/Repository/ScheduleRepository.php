<?php

namespace App\Repository;

use App\Models\Schedule;
use Carbon\Carbon;

/**
 * Class ScheduleRepository
 * @package App\Repository
 */
class ScheduleRepository extends Repository
{
    /**
     * 获取数据列表
     * @desc 注意参数顺序 第一个是 doctor_id 第二个是clinique_id
     * @param $doctor_id
     * @param $clinique_id
     * @return mixed
     */
    public function get_data_lists()
    {
        list($doctor_id, $clinique_id) = func_get_args();

        $two_weeks_after_time = Carbon::parse('+1 month')->toDateTimeString();//两周后的时间

        return $this->model->queryDoctor($doctor_id)->where('date', '>', Carbon::now())->where('date', '<=', $two_weeks_after_time)->queryClinique($clinique_id)->get();
    }

    /**
     * 通过医生诊所和时间查询排班
     * @param $doctor_id
     * @param $clinique_id
     * @param $date
     * @return mixed
     */
    public function get_data_by_doctor_and_clinic_and_date($doctor_id, $clinique_id, $date)
    {
        return $this->model->queryDoctor($doctor_id)->queryDate($date)->queryClinique($clinique_id)->get();
    }

    /**
     * @return Schedule
     */
    public function model()
    {
        return new Schedule();
    }

    /**
     * @Auth: Nnn
     * @param $data
     * @return mixed
     */
    public function get_data_by_insert_schedules($data)
    {
        return $this->model->insert($data);
    }

    /**
     * 根据code查询数据
     * @Auth: Nnn
     * @param $code
     * @return mixed
     */
    public function get_data_by_code($code)
    {
        return $this->model->where('code', $code)->first();
    }

    /**
     * 根据code删除数据
     * @Auth: Nnn
     * @param $code
     * @return mixed
     */
    public function delete_data_by_code($code)
    {
        return $this->model->whereIn('code', $code)->delete();
    }

}