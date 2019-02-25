<?php

namespace App\Http\WxControllers;

use Carbon\Carbon;
use App\Util\Tools;
use Illuminate\Http\Request;
use App\Repository\HealthRepository;
use App\Transformers\HealthTransformer;
use App\Http\Requests\HealthSaveRequest;

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
     * @param $type
     * @return \Dingo\Api\Http\Response
     */
    public function lists(Request $request, $type)
    {
        $list = $this->model->get_list_data_by_user_and_type(\Auth::id(), $type, $request->search);

        return $this->response()->paginator($list, new HealthTransformer());
    }

    /**
     * 通过类型获取今天，本周，本月的健康数据
     * @desc
     * @author Eric Chow
     * @DateTime 2018/1/11 15:00
     * @param Request $request
     * @param $type
     * @return mixed
     */
    public function data(Request $request, $type)
    {
        $data = [];
        $custom_data = [];
        $today = Tools::getDatesByType();//今天
        $week = Tools::getDatesByType(1);//获取今天之前七天的日期
        $month = Tools::getDatesByType(2);//获取今天之前七天的日期

        $today_data = $this->model->get_data(\Auth::id(), $type, ['startDate' => $today], 'today');
        $week_data = $this->model->get_days_data_list(\Auth::id(), $type, $week);
        $month_data = $this->model->get_days_data_list(\Auth::id(), $type, $month);

        //自定义时间
        if (isset($request['startDate']) && !empty($request['startDate'])) {
            if (strtotime($request['startDate']) > strtotime($request['endDate'])) return $this->error(101, '开始日期不能大于截止日期！');
            $custom = Tools::getDateFromRange($request['startDate'], $request['endDate']);
            $custom_data = $this->model->get_days_data_list(\Auth::id(), $type, array_reverse($custom));
        }

        $data['day_data'] = $today_data;
        $data['week_data'] = $week_data;
        $data['month_data'] = $month_data;
        $data['custom_data'] = $custom_data;

        return $data;

    }

    /**
     * 获取最后一条数据
     * @Auth: kingofzihua
     * @param $type
     * @return \Dingo\Api\Http\Response
     */
    public function last($type)
    {
        $data = $this->model->get_last_data_by_user_and_type(\Auth::id(), $type);

        return $this->response()->item($data, new HealthTransformer());
    }

    /**
     * 添加数据
     * @Auth: kingofzihua
     * @param HealthSaveRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function save(HealthSaveRequest $request)
    {
        $date = $request->date ?: Carbon::now();

        $data = array_merge($request->only(['content', 'type']), [
            'date' => $date, 'user_id' => \Auth::id()
        ]);

        $res = $this->model->create($data);

        return $this->response()->item($res, new HealthTransformer());
    }

}