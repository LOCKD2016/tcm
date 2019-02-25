<?php

namespace App\Http\WxControllers;

use App\Repository\CliniqueRepository;
use App\Services\SoapServices;
use App\Repository\ClinicRepository;
use App\Repository\DoctorRepository;
use App\Repository\ScheduleRepository;
use App\Transformers\ScheduleTransformer;

/**
 * 排班
 * Class ScheduleController
 * @package App\Http\WxControllers
 */
class ScheduleController extends Controller
{
    /**
     * @var ScheduleRepository
     */
    protected $model;

    /**
     * ScheduleController constructor.
     * @param $model
     */
    public function __construct(ScheduleRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 医生排班列表
     * @param $dcotor_id
     * @param $clinique_id
     */
    public function lists($dcotor_id, $clinique_id)
    {
        $lists = $this->model->get_data_lists($dcotor_id, $clinique_id);

        return $this->response()->item($lists, new ScheduleTransformer());
    }

    /**
     * 医生排班详情 直接读取数据库 ，这个只能获取医生的排班日期 没法体现出 哪个时间已经有人预约了
     * @param $dcotor_id 医生编号
     * @param $clinique_id 诊所编号
     * @param $date 日期
     */
    public function detail_by_database(DoctorRepository $doctorRepository, $doctor_id, $clinique_id, $date)
    {
        //获取医生信息
        $doctor = $doctorRepository->get_data_by_id($doctor_id);

        if (empty($doctor)) {
            return $this->error(404, '该医生不存在！');
        }

        //获取医生的排班
        $dates = $this->model->get_data_by_doctor_and_clinic_and_date($doctor_id, $clinique_id, $date);

        //默认为空
        $list = [];

        if ($dates) {
            //取出所有的 开始时间和结束时间
            $dates->transform(function ($data) {
                return [
                    'STR_ARRANGE_STARTTIME' => $data->start_time,
                    'STR_ARRANGE_ENDTIME' => $data->end_time,
                ];
            });

            //collect -> array
            $list = $dates->toArray();

            //按照开始时间排序 正序拍
            $list = array_values(array_sort($list, function ($value) {
                return $value['STR_ARRANGE_STARTTIME'];
            }));

            if (count($list)) {
                $list = $this->getTimeLineByDataList($list, $doctor->length);
            }
        }

        return $this->success(['space' => $doctor->length, 'list' => $list], '获取成功'); //space:医生就诊间隔 list:可预约的时间(开始时间)


    }

    /**
     * 医生排班详情 通过接口直接获取
     * @param $dcotor_id 医生编号
     * @param $clinique_id 诊所编号
     * @param $date 日期
     */
    public function detail(DoctorRepository $doctorRepository, SoapServices $soapServices, CliniqueRepository $cliniqueRepository, $doctor_id, $clinique_id, $date)
    {
        $doctor = $doctorRepository->get_data_by_id($doctor_id);

        //获取医生的在诊所的编号
        $doctor_code = $doctorRepository->get_doctor_code_by_clinque_id($doctor, $clinique_id);

        if (!$doctor_code) { //没有查询到code
            return $this->error(404, '医生不存在');
        }

        //获取诊所的编号
        $clinique = $cliniqueRepository->get_data_by_id($clinique_id);

        if (!$clinique) {
            return $this->error(404, '诊所不存在');
        }

        //处理时间
        $startDate = date('YmdHis', strtotime($date));//2017-12-20 => 20171220000000
        $endDate = date('YmdHis', strtotime($date) + 3600 * 24);//2017-12-21 => 20171221000000
//        return date($endDate);
        //请求接口查询数据
        $list = $soapServices->getScheduleDate($doctor_code, $doctor->name, $startDate, $endDate, $clinique->code);//return $list;

       //return $this->success(['space' => $doctor->length, 'list' => $list], '获取成功'); //space:医生就诊间隔 list:可预约的时间(开始时间)

        //判断下是否是空的 ，可能报错了或者是 本来就没有排班 通过日志来排查
        if (count($list)) {

            //有排班 将排班 处理下
            $list = $this->getTimeLineByDataList($list, $doctor->length, (bool)$doctor->clinic_monopoly);//return $list;
        }

        if(isset($list[$date])){
            $list = $list[$date];
        }else{
            $list = [];
        }

        return $this->success(['space' => $doctor->length, 'list' => $list], '获取成功'); //space:医生就诊间隔 list:可预约的时间(开始时间)
    }

    /**
     * 通过日期列表 获取医生的可就诊时间
     * @desc 这个地方牵扯到 数值和时间的转换 千万注意！！
     * @back 数值间隔是100 但是日期是60 千万别混了
     * @param $list [
     *      [
     *          "RESOURCE_CODE" => $code,
     *          "RESOURCE_NAME" => $name,
     *          "STR_ARRANGE_STARTTIME" => $head . '083000',
     *          "STR_ARRANGE_ENDTIME" => $head . '113000'
     *      ],
     *  ]
     * @param int $space 间隔时间 (分钟)
     * @param bool $monopoly 是否是独占 如果是独占的话 同一个时间点是不能同时预约的
     * @return array[
     *      '08:30',
     *      '08:45',
     *      ...
     *      '11:30'
     * ]
     */
    public function getTimeLineByDataList(array $list, int $space, bool $monopoly)
    {
        /**
         * 我觉得我有必要重写一下这个方法了，什么逻辑呀，浪费了很多时间在这里，罪恶的根源啊
         */
//        return $list;
        if ($space <= 0) return [];

        $schedule = [];

        foreach ($list as $key => $value){
            //转换为时间戳方便换算
            $start_time = strtotime($value['STR_ARRANGE_STARTTIME']);
            $end_time = strtotime($value['STR_ARRANGE_ENDTIME']);

            if ($end_time - $start_time < 0){
                return [];
            }

            $time_point = $start_time;
            $schedule[date('Y-m-d', $start_time)][] = date('H:i', $time_point);

            while ($time_point < $end_time - $space * 60){
                $time_point = $time_point + $space * 60;
                $schedule[date('Y-m-d', $start_time)][] = date('H:i', $time_point);
            }

        }

        return $schedule;

        /**
         * 以后若是有bug，用arrar_unique()函数处理
         */
//        for($i=0; $i<5; $i++){
//            $arr['uu'][] = 3;
//        }
//
//        $arr = array_unique($arr['uu']);
//
//        return $arr;





/*        if ($space <= 0) return []; //判断下 如果是0 就说明程序错误 不让他执行

        $line = $space * 100; //获取间隔(注意是数值的，不是秒数)*/
        //便利所有数组 一天的排班 可能有三个 早上 中午 下午
        /*$res_list = array_map(function ($item) use ($line, $space, $monopoly) {
            //获取 数值中间的间隔
            $length = ($item['STR_ARRANGE_ENDTIME'] - $item['STR_ARRANGE_STARTTIME']); //3000 11300-08300 = 3000(数值)

            //获取 数量
            $num = intval(($length / $line) * 0.6); //获取总的数量(总共可以看多少个人) 3000/300(数值间隔) * 0.6(间隔比例) 一小时只有60分钟

            if ($num <= 0) return; //明显有错误啊 调试下吧

            //重置 返回数组 防止 引用重复等
            $res = [];

            //转化下
            $statTime = strtotime($item['STR_ARRANGE_STARTTIME']); //先把字符串的 日期形式转换为日期时间戳

            //开始时间 遍历 可以预约的时间段的个数
            for ($i = 0; $i < $num; $i++) {

                //第$i个时间段与最开始的时间的间隔
                $timeLength = ($i * $space * 60);//与开始时间 的 时间间隔

                //判断下 是否是独占
                if ($monopoly && count($item['ResourceList'])) { //如果是独占 并且 已经预约的数量 不为空

                    //剔除 已经预约的号
                    $now_date_num = date('YmdHis', $statTime + $timeLength - 1); //当前的预约时间 数值

                    $next_date_num = date('YmdHis', $statTime + $timeLength + ($space * 60)); //下一个时间段

                    //判断当前时间到下一个时间段能不能预约
                    if (!$this->nowDataCanBespeak($item['ResourceList'], $now_date_num, $next_date_num)) { //已经有人预约了 跳过，不能让人预约
                        continue;
                    }
                }

                //将返回的时间处理成 08:30 这种形式
                $res[] = date('H:i', $statTime + $timeLength);
            }

            return $res;
        }, $list);*/

        /*$res = [];
        $ret = [];
        foreach ($list as $item){
            //获取 数值中间的间隔
            $length = ($item['STR_ARRANGE_ENDTIME'] - $item['STR_ARRANGE_STARTTIME']); //3000 113000-083000 = 30000(数值) 20180305083000

            //获取 数量
            $num = intval(($length / $line) * 0.6); //获取总的数量(总共可以看多少个人) 3000/300(数值间隔) * 0.6(间隔比例) 一小时只有60分钟

            if ($num <= 0) return; //明显有错误啊 调试下吧

            //重置 返回数组 防止 引用重复等


            //转化下
            $statTime = strtotime($item['STR_ARRANGE_STARTTIME']); //先把字符串的 日期形式转换为日期时间戳

            //开始时间 遍历 可以预约的时间段的个数
            for ($i = 0; $i < $num; $i++) {

                //第$i个时间段与最开始的时间的间隔
                $timeLength = ($i * $space * 60);//与开始时间 的 时间间隔

                //判断下 是否是独占
                if ($monopoly && count($item['ResourceList'])) { //如果是独占 并且 已经预约的数量 不为空

                    //剔除 已经预约的号
                    $now_date_num = date('YmdHis', $statTime + $timeLength - 1); //当前的预约时间 数值

                    $next_date_num = date('YmdHis', $statTime + $timeLength + ($space * 60)); //下一个时间段

                    //判断当前时间到下一个时间段能不能预约
                    if (!$this->nowDataCanBespeak($item['ResourceList'], $now_date_num, $next_date_num)) { //已经有人预约了 跳过，不能让人预约
                        continue;
                    }
                }

                //将返回的时间处理成 08:30 这种形式
                $res[] = date('H:i', $statTime + $timeLength);
                $ret[date('Y-m-d', $statTime + $timeLength)][] = date('H:i', $statTime + $timeLength);
            }
        }
        return $ret;*/

        //return array_collapse($res_list); //多个数组合并为一个数组
    }


    /**
     * 当前时间能不能预约
     * @desc 我日他大爷，妈的 烧脑子
     * @auth kingofzihua
     * @param $item 已经预约的数组
     * @param $now_date_num 当前的时间段 数值 20171213094500 (2017-12-13 09:45:00)
     * @param $next_date_num 下一个时间段的 数值 20171213100000 (2017-12-13 10:00:00)
     * @return bool  是否可以预约  1可以 | 0不可以
     */
    public function nowDataCanBespeak($item, $now_date_num, $next_date_num)
    {
        $continue = true;

        //判断当前开始时间是否已经有人预约了  @desc我不在别人的区间内 左区间
        array_map(function ($listItem) use ($now_date_num, &$continue) {
            if (($now_date_num - $listItem['STR_APPOINT_STARTTIME']) >= 0 && ($now_date_num - $listItem['STR_APPOINT_ENDTIME']) <= 0) {
                //dump($listItem['STR_APPOINT_STARTTIME'] . '-' . $now_date_num . '-' . $listItem['STR_APPOINT_ENDTIME']);
                $continue = false;
            }
        }, $item);

        //判断当前结束时间是否已经有人预约了  @desc我不在别人的区间内 右区间
        array_map(function ($listItem) use ($next_date_num, &$continue) {
            if (($next_date_num - $listItem['STR_APPOINT_STARTTIME']) >= 0 && ($next_date_num - $listItem['STR_APPOINT_ENDTIME']) <= 0) {
                //dump($listItem['STR_APPOINT_STARTTIME'] . '-' . $now_date_num . '-' . $listItem['STR_APPOINT_ENDTIME']);
                $continue = false;
            }
        }, $item);

        //没人预约 判断下 当前时间到下个时间内是否已经被预约了 @desc我和下一个区间内 没有别人
        if (!$continue) {
            array_map(function ($listItem) use ($now_date_num, $next_date_num, &$continue) {
                if (($now_date_num - $listItem['STR_APPOINT_STARTTIME']) <= 0 && ($next_date_num - $listItem['STR_APPOINT_ENDTIME']) >= 0) {
                    //dump($now_date_num . '-' . $listItem['STR_APPOINT_STARTTIME'] . '-' . $listItem['STR_APPOINT_ENDTIME'] . '-' . $next_date_num);
                    $continue = false;
                }
            }, $item);
        }

        return $continue;
    }

}