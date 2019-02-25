<?php

namespace App\Http\WxControllers;

use App\Models\Schedules;
use Illuminate\Http\Request;
use App\Repository\ConfigRepository;
use App\Repository\DoctorRepository;
use App\Transformers\DoctorTransformer;

/**
 * Class DoctorController
 * @package App\Http\WxControllers
 */
class DoctorController extends Controller
{
    /**
     * @var DoctorRepository
     */
    protected $model;

    /**
     * DoctorController constructor.
     * @param DoctorRepository $doctorRepository
     */
    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->model = $doctorRepository;
    }

    /**
     * 获取医生详情
     * @Auth: kingofzihua
     * @param $doctor_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($doctor_id)
    {
        $doctor = $this->model->get_data_by_id($doctor_id);

        return $this->response()->item($doctor, new DoctorTransformer());
    }

    /**
     * 获取门诊医生列表
     * @param Request $request
     * @return mixed
     */
    public function Lists(Request $request)
    {
        switch ($request->type) {
            case 'video': //网诊
            case 'web': //网诊
                $lists = $this->model->get_web_list($request->all());
                break;
            case 'clinic'://门诊
                $lists = $this->model->get_clinic_list($request->all());
                break;
            default:
                $lists = $this->model->get_empty_page_list();
                break;
        }
//        unset($lists['sex']);
//        unset($lists['level']);
//        return $lists;
//        return $this->response()->paginator($lists);
        return $this->response()->paginator($lists, new DoctorTransformer());
    }

    /**
     * 全局搜索
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function search(Request $request)
    {
        switch ($request->type) {
            case 'video': //网诊
            case 'web': //网诊
                $lists = $this->model->global_search_web_list($request->all());
                break;
            case 'clinic'://门诊
                $lists = $this->model->global_search_clinic_list($request->all());
                break;
            default://全局搜索
                $lists = $this->model->global_search_doctor_list($request->all());
        }

        return $this->response()->paginator($lists, new DoctorTransformer());
    }

    /**
     * 获取推荐列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function recommend(Request $request)
    {
        switch ($request->type) {
            case 'video': //网诊
            case 'web': //网诊
                $lists = $this->model->recommend_web_list();
                break;
            case 'clinic'://门诊
                $lists = $this->model->recommend_clinic_list();
                break;
            default:
                $lists = $this->model->recommend_doctor_list();
                break;
        }

        return $this->response()->paginator($lists, new DoctorTransformer());
    }

    /**
     * 获取医生的职称
     * @Auth: kingofzihua
     * @param ConfigRepository $configRepository
     */
    public function title(ConfigRepository $configRepository)
    {
        $title = $configRepository->get_doctor_title();

        return $this->response()->array($title);
    }

}