<?php

namespace App\Http\ApiControllers;

use Illuminate\Http\Request;
use App\Repository\ExamRepository;
use App\Transformers\ExamTransformer;
use App\Http\Requests\ExamSaveRequest;

/**
 * 问诊单
 * Class CardController
 * @package App\Http\ApiControllers
 */
class ExamController extends Controller
{
    /**
     * @var ExamRepository
     */
    protected $model;

    /**
     * ExamController constructor.
     * @param $exam
     */
    public function __construct(ExamRepository $examRepository)
    {
        $this->model = $examRepository;
    }

    /**
     * 查看问诊单列表
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->model->get_data_lists_by_doctor_id(\Auth::id());

        return $this->response()->paginator($lists, new ExamTransformer());
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function system()
    {
        $system = $this->model->get_data_by_type(0);

        if (!$system) {
            return $this->error(404, '没有数据');
        }

        return $this->response()->collection($system, new ExamTransformer());
    }

    /**
     * 通过类型获取问诊单
     * @return \Dingo\Api\Http\Response
     */
    public function type($type = 0)
    {
        $system = $this->model->get_auth_data_by_type($type);

        if (!$system) {
            return $this->error(404, '没有数据');
        }

        return $this->response()->item($system, new ExamTransformer());
    }

    /**
     * 问诊单详情
     * @param $exam_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($exam_id)
    {
        $detail = $this->model->get_data_by_id($exam_id);

        //不能查看别的医生的问诊单详情
        if ($detail && $detail->doctor_id && $detail->doctor_id != \Auth::id()) {

            return $this->error(403, '没有查看权限');
        }

        return $this->response()->item($detail, new ExamTransformer());
    }

    /**
     * 创建问诊单
     * @param ExamSaveRequest $request [
     *      'title', //问诊单标题
     *      'type', //问诊单类型
     *      'option' //问诊单的题目 [{"title":"测试问诊单的题目"},{"title":"测试问诊单的题目"}]
     * ]
     * @return \Dingo\Api\Http\Response
     */
    public function save(ExamSaveRequest $request)
    {
        //查询下 不可重复添加
        $exam = $this->model->get_auth_data_by_type($request->type);

        if ($exam) {
            return $this->error(403, '您已经有当前类型的问诊单了');
        }

        //判断下类型是否正确
        $option_arr = \GuzzleHttp\json_decode($request->option, true);

        if (!is_array($option_arr) || !count($option_arr)) {
            return $this->error(422, '问诊单的题目类型不正确。');
        }

        \DB::beginTransaction(); //开始事务

        //首先创建问诊单
        $exam = $this->model->create(array_merge(
            $request->only(['title', 'type']), ['doctor_id' => \Auth::id()]
        ));

        if ($exam) {
            //构建 题目的saveMany对象
            $option_builder = array_map(function ($option) use ($exam) {

                return $this->model->builderOption(array_merge($option, ['exam_id' => $exam->id]));

            }, $option_arr);

            //然后插入题目
            $option_create = $exam->options()->saveMany($option_builder);

            if (count($option_create)) {
                \DB::commit(); //提交事务

                return $this->success([], '添加成功');
            }
        }

        \DB::rollBack();//有错误 回滚事务

        return $this->error(502, '添加失败，请稍后重试');
    }

    /**
     * 修改问诊单
     * @desc 修改问诊单的题目(内容)
     * @selp 1、删除之前问诊单的关联
     *       2、添加新的问诊单
     * @param Request $request [
     *      'option' //选项
     * ]
     * @param $exam_id 问诊单编号
     * @return \Dingo\Api\Http\Response
     */
    public function edit(Request $request, $exam_id)
    {
        //判断下类型是否正确
        $option_arr = \GuzzleHttp\json_decode($request->option, true);

        if (!is_array($option_arr) || !count($option_arr)) {
            return $this->error(422, '问诊单的题目类型不正确。');
        }

        //获取问诊单
        $exam = $this->model->get_data_by_id($exam_id);

        //判断下问诊单是否是自己的
        if (empty($exam) || $exam->doctor_id != \Auth::id()) {
            return $this->error(403, '问诊单不存在');
        }

        \DB::beginTransaction(); //开始事务

        //然后构建新的题目
        $option_builder = array_map(function ($option) use ($exam) {

            return $this->model->builderOption(array_merge($option, ['exam_id' => $exam->id]));

        }, $option_arr);

        //删除之前的题目 是的 没错 直接删除了 答题的时候 要把题目 放到表中, 是不是很傻逼？ 仔细想想整个的流程 貌似没有更好的方法 ，如果有 15020866740 知道
        $exam->options()->delete();

        //添加题目
        $option_create = $exam->options()->saveMany($option_builder);

        if (count($option_create)) {
            \DB::commit(); //提交事务

            return $this->success([], '修改成功');
        }

        \DB::rollBack();//有错误 回滚事务

        return $this->error(502, '修改失败，请稍后重试');

    }
}
