<?php

namespace App\Http\ApiControllers;

use App\Util\Tools;
use Illuminate\Http\Request;
use App\Repository\HealthRepository;
use App\Transformers\HealthTransformer;

/**
 * 健康数据
 * Class HealthController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class HealthController extends Controller
{

    /**
     * @Auth: kingofzihua
     * @var HealthRepository
     */
    protected $model;

    /**
     * @Auth: kingofzihua
     * HealthController constructor.
     * @param $model
     */
    public function __construct(HealthRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 健康数据列表
     * @Auth: kingofzihua
     * @param Request $request [
     *      'startDate',//开始日期
     *      'endDate',//结束日期
     * ]
     * @param $user_id 用户编号
     * @param $type 类型
     * @return \Dingo\Api\Http\Response
     */
    public function lists(Request $request, $user_id, $type)
    {
        $list = $this->model->get_list_data_by_user_and_type($user_id, $type, $request->all());

        return $this->response()->paginator($list, new HealthTransformer());
    }

    /**
     * 获取最后一条数据
     * @Auth: kingofzihua
     * @param $user_id 用户编号
     * @param $type 类型
     * @return \Dingo\Api\Http\Response
     */
    public function last($user_id, $type)
    {
        $data = $this->model->get_last_data_by_user_and_type($user_id, $type);

        return $this->response()->item($data, new HealthTransformer());
    }

    /**
     * 获取今天一周一月的健康数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/12 18:31
     * @param $user_id
     * @param $type
     * @return array
     */
    public function data(Request $request, $user_id, $type)
    {
        $data = [];
        $custom_data = [];
        $today = Tools::getDatesByType();//今天
        $week = Tools::getDatesByType(1);//获取今天之前七天的日期
        $month = Tools::getDatesByType(2);//获取今天之前七天的日期
        $today_data = $this->model->get_data($user_id, $type, ['startDate' => $today], 'today');
        $week_data = $this->model->get_days_data_list($user_id, $type, $week);
        $month_data = $this->model->get_days_data_list($user_id, $type, $month);

        //自定义时间
        if (isset($request['startDate']) && !empty($request['startDate'])){
            if (strtotime($request['startDate']) > strtotime($request['endDate'])) return $this->error(101, '开始日期不能大于截止日期！');
            $custom = Tools::getDateFromRange($request['startDate'], $request['endDate']);
            $custom_data = $this->model->get_days_data_list($user_id, $type, array_reverse($custom));
        }

        $data['day_data'] = $today_data;
        $data['week_data'] = $week_data;
        $data['month_data'] = $month_data;
        $data['custom_data'] = $custom_data;

        return $data;
    }
}