<?php

namespace App\Repository;

use App\Models\AppUser;
use App\Models\Bespeak;

/**
 * Class BespeakRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class BespeakRepository extends Repository
{
    /**
     * 获取登录用户的预约列表
     * @Auth: kingofzihua
     * @return mixed
     */
    public function get_auth_user_lists()
    {
        return $this->model->queryUser(\Auth::id())->orderBy('id', 'desc')->paginate($this->page);
    }

    /**
     * 根据医生获取待接诊的网诊列表
     * @param $doctor_id
     * @return mixed
     */
    public function get_web_lists_by_doctor_for_loading($doctor_id)
    {
        return $this->model->where('type','<>',1)->queryDoctor($doctor_id)->queryStatus(5)->orderBy('id', 'desc')->paginate($this->page);
    }

    /**
     * 根据医生获取待接诊的网诊列表
     * @param $doctor_id
     * @param $date 日期
     * @return mixed
     */
    public function get_today_clinic_lists_by_doctor_and_date($doctor_id, $date)
    {
        return $this->model->queryType(1)->queryDoctor($doctor_id)->queryStartDate($date)->paginate($this->page);
    }

    /**
     * 获取用户当前网诊的数量
     * @param AppUser $user
     * @return mixed
     */
    public function count_now_web_clinic_num_by_user_and_doctor(AppUser $user, $doctor_id = '')
    {
        return $user->bespeaks()->where('type','<>',1)->queryDoctor($doctor_id)->queryStatusLt(25)->queryStatusSt(10)->count();
    }

    public function get_detail($id)
    {
        return $this->model->getFind($id);
    }

    /**
     * @Auth: kingofzihua
     * @return Bespeak
     */
    public function model()
    {
        return new Bespeak();
    }
}