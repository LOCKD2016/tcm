<?php

namespace App\Repository;

use App\Models\UserHealth;

/**
 * Class HealthRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class HealthRepository extends Repository
{
    /**
     * 通过类型获取列表数据(分页)
     * @param int $user_id 用户编号
     * @param int $type 类型
     * @param $search 搜索条件
     * @return mixed
     */
    public function get_list_data_by_user_and_type(int $user_id, int $type, $search)
    {
        return $this->model->queryUserId($user_id)->queryType($type)//登录用户 并且按照类型查询
            ->queryStartDate($search['startDate'] ?? '')//开始时间
            ->queryEndDate($search['endDate'] ?? '')//结束时间
            ->orderBy('date', 'desc')//按照日期排序
            ->paginate($this->page);
    }

    /**
     * 通过类型获取最后一条数据
     * @Auth: kingofzihua
     * @param int $user_id
     * @param $type
     * @return mixed
     */
    public function get_last_data_by_user_and_type(int $user_id, $type)
    {
        return $this->model->queryUserId($user_id)->queryType($type)->orderBy('id', 'desc')->first();
    }

    /**
     * 通过用户和类型获取所有的数据(不分页)
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/11 16:10
     * @param int $user_id
     * @param int $type
     * @param $search
     * @return mixed
     */
    public function get_all_list_data_by_user_and_type(int $user_id, int $type, $search)
    {
        return $this->model->queryUserId($user_id)
            ->queryType($type)//登录用户 并且按照类型查询
            ->queryStartDate($search['startDate'] ?? '')//开始时间
            ->queryEndDate($search['endDate'] ?? '')//结束时间
            ->orderBy('date', 'desc')//按照日期排序
            ->get();
    }

    /**
     *  查询今天的健康数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/12 17:39
     * @param int $user_id
     * @param int $type
     * @param $search
     * @return mixed
     */
    public function get_day_data(int $user_id, int $type, $search)
    {

        return $this->model->queryUserId($user_id)
            ->queryType($type)//登录用户 并且按照类型查询
            ->queryDate($search['startDate'] ?? '')//限定今天的
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     *  获取每天的最新一条健康数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/18 18:12
     * @param int $user_id
     * @param int $type
     * @param $date
     * @return mixed
     */
    public function get_everyday_first_data(int $user_id, int $type, $date)
    {
        return $this->model->queryUserId($user_id)
            ->queryType($type)//登录用户 并且按照类型查询
            ->queryDate($date ?? '')//限定今天的
            ->orderBy('date', 'desc')
            ->first();
    }

    /*---------------------------------------------------------------获取健康数据start----------------------------------------------------------------------------------------------------------------------------------*/
    /**
     * 获取今天的健康数据
     * @desc 以小时返回
     * @author Eric Chow
     * @DateTime 2018/1/12 14:07
     * @param int $user_id
     * @param int $type
     * @param $search
     * @param $tag 'today week month'
     */
    public function get_data(int $user_id, int $type, $search, $tag)
    {
        $systolic = $diastolic = $heart_rate = $sugar_before = $sugar_after = $oxygen = $temp = $height = $weight = $BMI = $time = [];

        $list = $this->get_day_data($user_id, $type, $search);// 获取所有今天的数据

        foreach ($list as $k => $v) {

            $return_data = $this->return_data_by_type($v, $tag);

            foreach ($return_data as $m => $n) {
                if ($m == 'systolic')
                    array_push($systolic, $n);
                if ($m == 'diastolic')
                    array_push($diastolic, $n);
                if ($m == 'heart_rate')
                    array_push($heart_rate, $n);
                if ($m == 'sugar_before')
                    array_push($sugar_before, $n);
                if ($m == 'sugar_after')
                    array_push($sugar_after, $n);
                if ($m == 'oxygen')
                    array_push($oxygen, $n);
                if ($m == 'temp')
                    array_push($temp, $n);
                if ($m == 'height')
                    array_push($height, $n);
                if ($m == 'weight')
                    array_push($weight, $n);
                if ($m == 'BMI')
                    array_push($BMI, $n);
                if ($m == 'time')
                    array_push($time, $n);
            }
        }
        if ($type == 1)
            if ($tag == 'today')
                return ['systolic' => $systolic, 'diastolic' => $diastolic, 'heart_rate' => $heart_rate, 'used_days' => $time];
            else
                return ['systolic' => $systolic, 'diastolic' => $diastolic, 'heart_rate' => $heart_rate];

        if ($type == 2)
            if ($tag == 'today')
                return ['sugar_before' => $sugar_before, 'sugar_after' => $sugar_after, 'used_days' => $time];
            else
                return ['sugar_before' => $sugar_before, 'sugar_after' => $sugar_after];

        if ($type == 3)
            if ($tag == 'today')
                return ['oxygen' => $oxygen, 'used_days' => $time];
            else
                return ['oxygen' => $oxygen];

        if ($type == 4)
            if ($tag == 'today')
                return ['temp' => $temp, 'used_days' => $time];
            else
                return ['temp' => $temp];

        if ($type == 5)
            if ($tag == 'today')
                return ['height' => $height, 'weight' => $weight, 'BMI' => $BMI, 'used_days' => $time];
            else
                return ['height' => $height, 'weight' => $weight, 'BMI' => $BMI];


        return ['systolic' => $systolic, 'diastolic' => $diastolic, 'heart_rate' => $heart_rate, 'sugar_before' => $sugar_before, 'sugar_after' => $sugar_after, 'oxygen' => $oxygen, 'temp' => $temp, 'height' => $height, 'weight' => $weight, 'BMI' => $BMI];

    }

    /**
     *  处理本周本月自定义时间的 (每天最新的) 健康数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/18 15:46
     * @param int $user_id
     * @param int $type
     * @param $search
     * @param $days
     */
    public function get_days_data_list(int $user_id, int $type, $days)
    {
        $systolic = $diastolic = $heart_rate = $sugar_before = $sugar_after = $oxygen = $temp = $height = $weight = $BMI = $used_days = [];

        foreach ($days as $m) {

            $date = date('d', strtotime($m));//天

            $detail = $this->get_everyday_first_data($user_id, $type, $m); //获取每天同一类型最新的一条数据

            //数据处理
            if ($detail) {
                $result = $this->return_data_by_type($detail, 'week');

                if ($result) {
                    if ($type == 1){
                        if (isset($result['systolic']))
                            array_push($systolic, $result['systolic']);
                        if (isset($result['diastolic']))
                            array_push($diastolic, $result['diastolic']);
                        if (isset($result['heart_rate']))
                            array_push($heart_rate, $result['heart_rate']);
                    }elseif ($type == 2){
                        if (isset($result['sugar_before'])){
                            array_push($sugar_before, $result['sugar_before']);
                            array_push($sugar_after, [$date, '']);
                        }

                        if (isset($result['sugar_after'])){
                            array_push($sugar_after, $result['sugar_after']);
                            array_push($sugar_before, [$date, '']);
                        }
                    }elseif ($type == 3){
                        if (isset($result['oxygen']))
                            array_push($oxygen, $result['oxygen']);
                    }elseif ($type == 4){
                        if (isset($result['temp']))
                            array_push($temp, $result['temp']);
                    }elseif ($type == 5){
                        if (isset($result['height']))
                            array_push($height, $result['height']);
                        if (isset($result['weight']))
                            array_push($weight, $result['weight']);
                        if (isset($result['BMI']))
                            array_push($BMI, $result['BMI']);
                    }

                    array_push($used_days, $m);
                }

            } else {
                //如若为空 加上日期，value设为空
                array_push($systolic, [$date, '']);
                array_push($diastolic, [$date, '']);
                array_push($heart_rate, [$date, '']);
                array_push($sugar_before, [$date, '']);
                array_push($sugar_after, [$date, '']);
                array_push($oxygen, [$date, '']);
                array_push($temp, [$date, '']);
                array_push($height, [$date, '']);
                array_push($weight, [$date, '']);
                array_push($BMI, [$date, '']);
            }

        }

        //根据请求的类型返回相应的数据
        if ($type == 1)
            return ['systolic' => $this->translate_data($systolic), 'diastolic' => $this->translate_data($diastolic), 'heart_rate' => $this->translate_data($heart_rate), 'used_days' => $used_days];
        if ($type == 2)
            return ['sugar_before' => $this->translate_data($sugar_before), 'sugar_after' => $this->translate_data($sugar_after), 'used_days' => $used_days];
        if ($type == 3)
            return ['oxygen' => $this->translate_data($oxygen), 'used_days' => $used_days];
        if ($type == 4)
            return ['temp' => $this->translate_data($temp), 'used_days' => $used_days];
        if ($type == 5)
            return ['height' => $this->translate_data($height), 'weight' => $this->translate_data($weight), 'BMI' => $this->translate_data($BMI), 'used_days' => $used_days];

        return ['systolic' => $this->translate_data($systolic), 'diastolic' => $this->translate_data($diastolic), 'heart_rate' => $this->translate_data($heart_rate), 'sugar_before' => $this->translate_data($sugar_before),
            'sugar_after' => $this->translate_data($sugar_after), 'oxygen' => $this->translate_data($oxygen), 'temp' => $this->translate_data($temp), 'height' => $this->translate_data($height), 'weight' => $this->translate_data($weight), 'BMI' => $this->translate_data($BMI), 'used_days' => $used_days];
    }

    /*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++健康数据公用处理部分start++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
    /**
     * 根据类型返回具体的时间和数据形式
     * @desc [小时,数值]
     * @author Eric Chow
     * @DateTime 2018/1/12 14:13
     * @param $val
     */
    public function return_data_by_type($val, $tag)
    {
        if (!isset($val)) return [];

        if (isset($val->type) && $val->type == 1) return $this->get_pressure($val, $tag);
        if (isset($val->type) && $val->type == 2) return $this->get_sugar_val($val, $tag);
        if (isset($val->type) && $val->type == 3) return $this->get_oxygen_val($val, $tag);
        if (isset($val->type) && $val->type == 4) return $this->get_temp_val($val, $tag);
        if (isset($val->type) && $val->type == 5) return $this->get_physique($val, $tag);

        return [];
    }

    /**
     *  获取今天的血压数据
     * @desc [类型,小时,数值]
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/12 15:45
     * @param $val
     * @return array
     */
    public function get_pressure($val, $tag)
    {
        if (isset($val->type) && $val->type == 1) {

            $content = [];

            if ($tag == 'today') {
                $hour = date('H', strtotime($val->date));
                if ($hour == '00') $hour = '24';
                $minute = date('i', strtotime($val->date));
                $day_used_time = date('H:i', strtotime($val->date));//今天数据需要的时间（小时:分钟）
                $date = $hour + number_format($minute / 60, 1);
            } else {
                $date = date('d', strtotime($val->date));
            }


            $systolic = [$date];
            $diastolic = [$date];
            $heart_rate = [$date];

            if (isset($val->content)){
                $content = \GuzzleHttp\json_decode($val->content);
            }

            if (isset($content->systolic))
                array_push($systolic, $content->systolic);
            if (isset($content->diastolic))
                array_push($diastolic, $content->diastolic);
            if (isset($content->heart_rate))
                array_push($heart_rate, $content->heart_rate);

            if($tag == 'today'){
                return ['systolic' => $systolic, 'diastolic' => $diastolic, 'heart_rate' => $heart_rate, 'time'=>$day_used_time];
            }
            return ['systolic' => $systolic, 'diastolic' => $diastolic, 'heart_rate' => $heart_rate];
        }

        return [];
    }

    /**
     * 获取今天的血糖数据
     * @desc [sugar,时间,血糖值]
     * @author Eric Chow
     * @DateTime 2018/1/12 15:48
     * @param $val
     * @return array
     */
    public function get_sugar_val($val, $tag)
    {
        if (isset($val->type) && $val->type == 2) {

            $content = [];

            if ($tag == 'today') {
                $hour = date('H', strtotime($val->date));
                if ($hour == '00') $hour = '24';
                $minute = date('i', strtotime($val->date));
                $day_used_time = date('H:i', strtotime($val->date));//今天数据需要的时间（小时:分钟）
                $date = $hour + number_format($minute / 60, 1);
                $sugar = [$date];
            } else {
                $sugar = [date('d', strtotime($val->date))];
            }

            if (isset($val->content))
                $content = \GuzzleHttp\json_decode($val->content);

            if (isset($content->sugar_val))
                array_push($sugar, $content->sugar_val);

            if(isset($content->status)){
                if ($content->status == '餐前'){
                    if($tag == 'today'){
                        return ['sugar_before' => $sugar, 'time'=>$day_used_time];
                    }
                    return ['sugar_before' => $sugar];
                } else{
                    if($tag == 'today'){
                        return ['sugar_after' => $sugar, 'time'=>$day_used_time];
                    }
                    return ['sugar_after' => $sugar];
                }
            }


        }

        return [];

    }

    /**
     * 获取今天的血氧数据
     * @desc [oxygen,时间,血氧值]
     * @author Eric Chow
     * @DateTime 2018/1/12 15:48
     * @param $val
     * @return array
     */
    public function get_oxygen_val($val, $tag)
    {
        if (isset($val->type) && $val->type == 3) {

            $content = [];


            if ($tag == 'today') {
                $hour = date('H', strtotime($val->date));
                if ($hour == '00') $hour = '24';
                $minute = date('i', strtotime($val->date));
                $day_used_time = date('H:i', strtotime($val->date));//今天数据需要的时间（小时:分钟）
                $date = $hour + number_format($minute / 60, 1);
                $oxygen = [$date];
            } else {
                $oxygen = [date('d', strtotime($val->date))];
            }

            if (isset($val->content)) $content = \GuzzleHttp\json_decode($val->content);

            if (isset($content->oxygen_val))
                array_push($oxygen, $content->oxygen_val);

            if($tag=='today'){
                return ['oxygen' => $oxygen, 'time' => $day_used_time];
            }
            return ['oxygen' => $oxygen];
        }
        return [];
    }

    /**
     * 获取今天的体温数据
     * @desc [temp,时间,体温值]
     * @author Eric Chow
     * @DateTime 2018/1/12 15:48
     * @param $val
     * @return array
     */
    public function get_temp_val($val, $tag)
    {
        if (isset($val->type) && $val->type == 4) {

            $content = [];


            if ($tag == 'today') {
                $hour = date('H', strtotime($val->date));
                if ($hour == '00') $hour = '24';
                $minute = date('i', strtotime($val->date));
                $day_used_time = date('H:i', strtotime($val->date));//今天数据需要的时间（小时:分钟）
                $date = $hour + number_format($minute / 60, 1);
                $temp = [$date];
            } else {
                $temp = [date('d', strtotime($val->date))];
            }

            if (isset($val->content)) $content = \GuzzleHttp\json_decode($val->content);

            if (isset($content->temp_val))
                array_push($temp, $content->temp_val);

            if($tag=='today'){
                return ['temp' => $temp, 'time' => $day_used_time];
            }
            return ['temp' => $temp];
        }
        return [];
    }

    /**
     * 获取今天的体质数据
     * @desc [physique，时间，数值]
     * @author Eric Chow
     * @DateTime 2018/1/12 15:55
     * @param $val
     */
    public function get_physique($val, $tag)
    {
        if (isset($val->type) && $val->type == 5) {

            $content = [];

            if ($tag == 'today') {
                $hour = date('H', strtotime($val->date));
                if ($hour == '00') $hour = '24';
                $minute = date('i', strtotime($val->date));
                $day_used_time = date('H:i', strtotime($val->date));//今天数据需要的时间（小时:分钟）
                $date = $hour + number_format($minute / 60, 1);
            } else {
                $date = date('d', strtotime($val->date));
            }

            $height = [$date];
            $weight = [$date];
            $BMI = [$date];

            if (isset($val->content)) $content = \GuzzleHttp\json_decode($val->content);

            if (isset($content->height))
                array_push($height, $content->height);
            if (isset($content->weight))
                array_push($weight, $content->weight);
            if (isset($content->BMI))
                array_push($BMI, $content->BMI);

            if($tag=='today'){
                return ['height' => $height, 'weight' => $weight, 'BMI' => $BMI, 'time'=>$day_used_time];
            }
            return ['height' => $height, 'weight' => $weight, 'BMI' => $BMI];
        }
        return [];
    }

    /**
     * 数据处理
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/18 19:07
     * @param $arr
     * @return array
     */
    public function translate_data($arr)
    {
        $array_first = [];
        $array_second = [];
        foreach ($arr as $k => $v) {
            array_push($array_first, intval($v[0]));
            array_push($array_second, $v[1]);
        }
        return [array_reverse($array_first), array_reverse($array_second)];
    }

    /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++健康数据公用处理部分end+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

    /*---------------------------------------------------------------获取健康数据end----------------------------------------------------------------------------------------------------------------------------------*/


    /**
     * @Auth: kingofzihua
     * @return UserHealth
     */
    public function model()
    {
        return new UserHealth();
    }

}