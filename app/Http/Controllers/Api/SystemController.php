<?php

namespace App\Http\Controllers\Api;

use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamOption;
use Illuminate\Http\Request;

class SystemController extends ApiController
{

    /**
     * 系统问诊单列表
     * @return mixed
     */
    public function exam()
    {
        $data = Exam::where('doctor_id', 0)->get();
        return $this->success($data);
    }

    public function exam_show($id)
    {
        $data = Exam::where('id', $id)->withTrashed()->first();
        if ($data->deleted_at) {
            $data = Exam::where(['doctor_id' => $data->doctor_id, 'type' => $data->type])->orderBy('id', 'desc')->first();
        }
        $data['options'] = $data->options()->orderBy('sort', 'asc')->get();
        return $this->success($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function exam_store(Request $request)
    {
        //\DB::beginTransaction();
        $params = $request->all();
        if (ExamAnswer::where('exam_id', $request->exam_id)->first()) {

            $options = ExamOption::where('exam_id', $request->exam_id)->get()->toArray();

            $exam = $this->softDelete_exam(Exam::find($request->exam_id));

            foreach ($options as &$v) {
                $v['exam_id'] = $exam->id;
                ExamOption::create($v);
            }
            //dd($options);

            $params['exam_id'] = $exam->id;

        }
        if ($params['type'] == 'photo') {
            if (isset($params['option']) && count($params['option'])) {
                foreach ($params['option'] as $k => &$v) {
                    $v = config('app.url') . $v;
                }
                //$params['option'] = \GuzzleHttp\json_encode($params['option']);
            }
        } else {
            if (isset($params['option']) && count($params['option'])) {
                foreach ($params['option'] as $k => &$v) {
                    $v = ['val' => $v];
                }
                //$params['option'] = \GuzzleHttp\json_encode($params['option']);
            }
        }
        //获取当前最大的sort排序
        $sort = Exam::find($params['exam_id'])->options()->orderBy('sort', 'desc')->value('sort');

        $params['sort'] = $sort ? $sort + 1 : 1;

        $data = ExamOption::create($params);


        if ($data) {
            return $this->success($data->exam_id);
        }
    }

    public function exam_sort($exam_id)
    {
        $options = Exam::find($exam_id)->options;
        $i = 1;
        foreach ($options as $k => $v) {
            $v->sort = $i++;
            $v->save();
        }
    }

    public function exam_delete($id)
    {
        $options = ExamOption::find($id);
        if (ExamAnswer::where('exam_id', $options->exam_id)->first()) {
            //删除旧的问诊单 返回新的问诊单
            $data = ExamOption::where('exam_id', $options->exam_id)->where('id', '<>', $id)->get()->toArray();

            $old = Exam::find($options->exam_id);
            $exam = $this->softDelete_exam($old);

            //$exam = Exam::find($options->exam_id);
            foreach ($data as &$v) {
                $v['exam_id'] = $exam->id;
                ExamOption::create($v);
            }

            $this->exam_sort($exam->id);
        } else {
            $options->forceDelete();
            $this->exam_sort($options->exam_id);
        }

        return $this->success();
//
//        $this->exam_sort($exam->id);
//        if ($row) {
//            return $this->success();
//        }
    }

    /**
     * 软删除原先的试卷并返回新的试卷
     * @param $exam
     * @return ExamRepository
     */
    public function softDelete_exam($exam)
    {
        $data['title'] = $exam->title;
        $data['type'] = $exam->type;
        $data['doctor_id'] = $exam->doctor_id;
        $exam->delete();
        return Exam::create($data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function exam_save(Request $request)
    {
        $params = $request->all();
        $option = ExamOption::find($params['id']);

//        if(ExamAnswer::where('exam_id',$request->exam_id)->first()){
//
//            $options = ExamOption::where('exam_id',$request->exam_id)->get()->toArray();
//
//            $exam = $this->softDelete_exam(Exam::find($request->exam_id));
//
//            foreach ($options as &$v){
//                $v['exam_id'] = $exam->id;
//                ExamOption::create($v);
//            }
//            //dd($options);
//
//            $params['exam_id'] = $exam->id;
//
//        }

        //dd($params);
        if ($params['type'] == 'photo') {
            if (isset($params['option']) && count($params['option'])) {
//                foreach ($params['option'] as $k => &$v) {
//                    $v = config('app.url') . $v;
//                }
            } else {
                $params['option'] = '';
            }
        } else {
            if (isset($params['option']) && count($params['option'])) {
                if ($params['type'] != 'text') {
                    foreach ($params['option'] as $k => &$v) {
                        $v = ['val' => $v];
                    }
                }
            } else {
                $params['option'] = '';
            }
        }
        $option->option = $params['option'];
        $option->title = $params['title'];
        $option->must = $params['must'];
        $option->sort = $params['sort'];
        //$option->delete();
        $data = $option->save();
        //$data = ExamOption::where('id',$params['id'])->update($params);
        if ($data)
            return $this->success();
    }

    public function update(Request $request, $id)
    {
        $info = Answer::find($id);
        if (!$info) return $this->error(0, '没有找到该答案');
        $data = $request->all();
        if (!intval($data['order'])) return $this->error(0, '系统错误,请按F5刷新重试');
        $info['order'] = $info['order'] + 1;
        $res = $info->save();
        if ($res) return $this->success(null, '修改成功');
        return $this->error(0, '修改失败');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $info = Answer::find($id);
        if (!$info) return $this->error(0, '没有找到该答案');
        $res = $info->delete();
        if ($res) return $this->success(null, '操作成功');
        return $this->error(0, '操作失败');
    }
}
