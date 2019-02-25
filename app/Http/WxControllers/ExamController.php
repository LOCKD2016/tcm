<?php

namespace App\Http\WxControllers;

use App\Repository\JPushRepository;
use Illuminate\Http\Request;
use App\Repository\ExamRepository;
use App\Repository\MessageRepository;
use App\Transformers\ExamTransformer;
use Illuminate\Database\QueryException;
use App\Http\Requests\ExamAnswerRequest;
use App\Repository\ExamAnswerRepository;

/**
 * 试卷 个性化问诊单
 * Class ExamControllers
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class ExamController extends Controller
{
    /**
     * @Auth: kingofzihua
     * @var
     */
    protected $exam;

    /**
     * @Auth: kingofzihua
     * ExamControllers constructor.
     * @param $exam
     */
    public function __construct(ExamRepository $exam)
    {
        $this->exam = $exam;
    }

    /**
     * 详情
     * @Auth: kingofzihua
     * @param $exam_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($exam_id)
    {
        $detail = $this->exam->get_data_by_id($exam_id);

        return $this->response()->item($detail, new ExamTransformer());
    }

    /**
     * 答题
     * @Auth: kingofzihua
     * @param ExamAnswerRequest $request
     * @param ExamAnswerRepository $examAnswerRepository
     * @param MessageRepository $messageRepository
     * @return mixed
     */
    public function answer(ExamAnswerRequest $request, ExamAnswerRepository $examAnswerRepository, MessageRepository $messageRepository)
    {
        //通过 message_list_id 编号获取 诊疗编号
        $messageList = $messageRepository->get_message_lists_by_id($request->message_list_id);

        $examData = [
            'user_id' => \Auth::id(),
            'exam_id' => $request->exam_id,
            'clinic_id' => $messageList->clinic_id,
        ];


        /**
         * 这里注意下！！！！！！！！！！！！！！！！！！！！！
         * @auth kingofzihua
         * @desc 因为多次诊疗全部都在一个聊天里面所以有可能是这种情况:
         *          上次诊疗已经完成了个性化问诊单的问答(药方哪里也是的！！药方不归我管  找刘新)
         *      诊疗结束后，重新一次诊疗 (这个时候message_list里面的clinic_id 已经是新的了)
         *      如果我进行填写问诊单 这个时候是没问题的，但是如果我要是找到上次填写的问诊单进入
         *      详情后修改，那么修改后的问诊单 会替换最新的诊疗里面回答的问诊单。
         *      如果出现这种情况的话 那么在这里做处理
         * @step
         *    1、获取当前诊疗所回答的试题，如果没有，那就不会出现这种问题
         *    2、根据查询出来的最新的回答的答案，查询其中的诊疗编号是不是和当前一样的
         *    3、如果是一样的，那也没问题，如果不一样
         *    4、那么问题就会出现了，不一样的话就不能进行答题了。
         */

        \DB::beginTransaction(); //开启事务

        //删除之前回答的题目
        $examAnswerRepository->delete_answer_by_clinic($messageList->clinic_id);

        foreach ($request->option['data'] as $option) {
            try {
                //判断如果传过来的是数组 json_encode。
                $answer = isset($option['answers']) && is_array($option['answers']) ? json_encode($option['answers']) : ($option['answers'] ?? '');

                $optionData[] = $examAnswerRepository->create(array_merge([
                    'question_id' => $option['id'],
                    'question' => $option['title'],
                    'answer' => $answer ?? '',
                ], $examData));

            } catch (QueryException $exception) {
                \DB::rollBack(); //事务回滚

                return $this->error('500', 'SQL:' . $exception->getMessage());
            }
        }

        //答题完毕 发送发送消息
        $messageRepository->send_exam_complete_message($request->message_list_id, $request->exam_id, $messageList->clinic_id);
        //通知医生我已填写完问诊单
        (new JPushRepository)->remind_doctor_patient_complete_exam($messageList->doctor_id,$messageList->user_id,$request->message_list_id);
        \DB::commit();  //事务提交

        return $this->success($optionData, '您已填写完毕。');
    }

}