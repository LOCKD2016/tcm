<?php

namespace App\Http\Controllers\Api;


use App\Models\Disease;
use App\Transformers\Api\DiseaseTransformer;
use Illuminate\Http\Request;

class DiseaseController extends ApiController
{
    /**
     * @var $slider
     */
    protected $disease;

    protected $page = 10;

    /**
     * DiseaseController constructor.
     * @param Disease $disease
     */
    public function __construct(Disease $disease)
    {
        $this->disease = $disease;
    }

    /**
     * 疾病列表
     * @param Request $request
     * [
     *      'nopage' 是否不分页 true不分页 false或者不传为分页
     * ]
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->noPage) {
            $list = $this->disease->get();

            return $this->response()->item($list, new DiseaseTransformer());

        }

        $lists = $this->disease->paginate($this->page);

        return $this->response()->paginator($lists, new DiseaseTransformer());
    }

    /**
     * 添加疾病
     * @param Request $request
     */
    public function diseaseCreate(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|unique:disease'
        ], [
            'name.required' => '疾病名称不能为空',
            'name.unique' => '疾病名称不能重复'
        ]);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $create = $this->disease->create($data);

        if ($create) {
            return $this->success($create->id, '添加成功');
        };

        return $this->error(100, '添加失败');

    }

    /**
     * 通过科室id获取疾病信息
     * @param $id
     */
    public function disease($id)
    {
        $disease = $this->disease->get_disease_by_section_id($id);

        if ($disease) {
            return $this->success($disease);
        }

        return $this->error(100, '没有数据');
    }

    /**
     * 通过科室id获取疾病信息
     * @param $id
     */
    public function diseaseDel($id)
    {
        $disease = $this->disease->delete_disease_by_id($id);

        if ($disease) {
            return $this->success();
        }

        return $this->error(100, '操作失败');
    }


}
