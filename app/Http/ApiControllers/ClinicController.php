<?php

namespace App\Http\ApiControllers;

use App\Repository\ClinicRepository;
use App\Transformers\ClinicTransformer;

/**
 * Class ClinicController
 * @package App\Http\ApiControllers
 */
class ClinicController extends Controller
{

    /**
     * @var ClinicRepository
     */
    protected $model;

    /**
     * ClinicController constructor.
     * @param $clinic
     */
    public function __construct(ClinicRepository $clinic)
    {
        $this->model = $clinic;
    }

    /**
     * 查看问诊单
     * @desc 查看问诊单
     * @param $clinic_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($clinic_id)
    {
        $clinic = $this->model->get_data_by_id($clinic_id);

        return $this->response()->item($clinic, new ClinicTransformer());
    }

    /**
     * 用户的诊疗
     * @param ClinicRepository $clinicRepository
     * @param $user_id
     * @return \Dingo\Api\Http\Response
     */
    public function lists($user_id)
    {
        $lists = $this->model->get_list_data_by_user_and_doctor($user_id);

        return $this->response()->paginator($lists, new ClinicTransformer());
    }

    /**
     * 获取有关当前登录医生的用户诊疗
     * @param ClinicRepository $clinicRepository
     * @param $user_id
     * @return \Dingo\Api\Http\Response
     */
    public function aboutMe($user_id)
    {
        $lists = $this->model->get_list_data_by_user_and_doctor($user_id, \Auth::id());

        return $this->response()->paginator($lists, new ClinicTransformer());
    }
}
