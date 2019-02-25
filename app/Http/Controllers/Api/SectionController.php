<?php

namespace App\Http\Controllers\Api;

use App\Models\Disease;
use App\Models\Section;
use App\Transformers\Api\SectionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SectionController extends ApiController
{
    /**
     * @var $slider
     */
    protected $section;

    protected $page = 10;

    /**
     * SectionController constructor.
     * @param Section $section
     */
    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    /**
     * 获取科室列表
     */
    public function index(Request $request)
    {
        if ($request->noPage) {
            $list = $this->section->get();

            return $this->response()->item($list, new SectionTransformer());
        }

        $list = $this->section->paginate($this->page);

        return $this->response()->paginator($list, new SectionTransformer());
    }

    /**
     * 添加科室
     * @param Request $request
     */
    public function sectionAdd(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => 'required|unique:sections'
        ], [
            'name.required' => '科室名称不能为空',
            'name.unique' => '科室名称不能重复'
        ]);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        if ($this->section->create($data)) {
            return $this->success(200, '添加成功');
        };

        return $this->error(100, '添加失败');

    }

    /**
     * 科室修改
     * @param Request $request
     * @return mixed
     */
    public function sectionUpdate(Request $request)
    {
        $data = $request->all();

        if ($data['type'] == 'add') {
            $validator = $this->sectionValidator($data);

            if ($validator->fails()) {
                return $this->error(100, $validator->errors()->first());
            }
        }

        $section = $this->section->find($data['id']);

        $section->fill($data);

        if ($section->save()) {
            return $this->success(200, '修改成功');
        }

        return $this->error(100, '修改失败');
    }

    /**
     * 科室删除
     * @param $id
     * @return mixed
     */
    public function sectionDel($id)
    {
        $section = $this->section->find($id);

        if ($section) {
            if ($section->delete()) {
                return $this->success(200, '删除成功');
            }
        }

        return $this->error(100, '删除失败');
    }

    /**
     * 验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function sectionValidator($data)
    {
        $validator = \Validator::make($data, [
            'name' => 'required|unique:sections,name,' . $data['id']
        ], [
            'name.required' => '科室名称不能为空',
            'name.unique' => '科室名称不能重复',
        ]);
        return $validator;
    }
}