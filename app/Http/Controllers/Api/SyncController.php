<?php

namespace App\Http\Controllers\Api;

use App\Jobs\SyncDoctorInfo;
use App\Models\Clinique;
use App\Repository\WX\CliniqueRepository;
use App\Util\YunZhongYi;
use App\Jobs\GetYzyDataPageList;
use App\Jobs\Scheduling;

class SyncController extends ApiController
{
    /**
     * @var
     */
    protected $yzy;

    /**
     * SyncController constructor.
     * @param YunZhongYi $yzy
     */
    public function __construct(YunZhongYi $yzy)
    {
        $this->yzy = $yzy;
    }

    public function index()
    {
        //获取路由对应的方法名
        $pathArr = explode('/', \Request::path());

        $route = array_pop($pathArr);
        //分发队列
        dispatch((new GetYzyDataPageList($route)));
        //return $this->$route();
    }

    public function clinique()
    {
        return (new CliniqueRepository())->sync_data();
    }


    /**
     * 循环获取所有的医生
     * @param $clinic_id
     * @return array
     */
    protected function getYzyDoctorListInfo($clinic_id)
    {

        $res = $this->yzy->getClinicAllDoctors($clinic_id);

        $resArr = collect($res)->toArray();
        foreach ($resArr as $v) {
            $this->getYzyDoctorDetail($v->id);
        }
    }

    /**
     * 获取医生的详细信息
     * @param $doctor_id
     * @return mixed
     */
    protected function getYzyDoctorDetail($doctor_id)
    {

        $res = $this->yzy->getDoctor($doctor_id);

        //$this->dispatch((new SyncDoctorInfo($res))->onConnection('redis'));
        $this->dispatch((new SyncDoctorInfo($res)));

    }

    public function doctor()
    {
        //获取诊所id集合
        $clinic_id = $this->yzy->getClinicIds();

        foreach ($clinic_id as $v) {
            $this->getYzyDoctorListInfo($v);
        }
        //同步用户数据
        //return $this->syncDoctor($retData);
    }

    public function scheduling()
    {
        $clinic_id = $this->yzy->getClinicIds();
        $params = [
            'first' => [
                'startDate' => strtotime(date("Y-m-d", time())) * 1000,
            ],
            'second' => [
                'startDate' => strtotime('1 week', strtotime(date("Y-m-d", time()))) * 1000,
            ]
        ];


        foreach ($clinic_id as $v) {
            foreach ($params as $value) {
                //获取排班
                $ret = $this->yzy->getScheduling($v, $value['startDate']);
                foreach ($ret as $k => $vv) {
                    dispatch((new Scheduling($k, $vv, $v)));
                }
            }
        }

    }
}