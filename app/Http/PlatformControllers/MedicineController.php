<?php
namespace App\Http\PlatformControllers;

use Illuminate\Http\Request;
use App\Repository\MedicineRepository;

/**
 * Class PrescriptionController
 * @Auth: Nnn
 * @package App\Http\PlatformControllers
 */
class MedicineController extends Controller
{
    /**
     * @Auth: Nnn
     * @var
     */
    protected $model;

    /**
     * @Auth: Nnn
     * PrescriptionController constructor.
     * @param $prescription
     */
    public function __construct(MedicineRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 项目信息新增
     * @param Request $request
     * {
            'code' 项目编号
            'name' 项目名称
            'spell' 项目拼音快捷码
            'amount' 项目价格（单位：分）
            'unit' 单位
            'type' 项目类型 单品:1;套餐:2;其他:3
            'desc' 项目描述;如果项目是套餐时，所包含的单品项目
     * }
     * @return \Dingo\Api\Http\Response
     */
    public function save(Request $request)
    {
        $data = $request->all();

        $validator = $this->MedicineValidator($data);

        if ($validator->fails())
        {
            return $this->error(100, $validator->errors()->first());
        }

        $create = $this->model->create($data);

        if($create)
        {
            return $this->success();
        }

        return $this->error(100000, '操作失败');
    }

    /**
     * 项目信息编辑
     * @param Request $request
     * {
            'code' 项目编号
            'name' 项目名称
            'spell' 项目拼音快捷码
            'amount' 项目价格（单位：分）
            'unit' 单位
            'type' 项目类型 单品:1;套餐:2;其他:3
            'desc' 项目描述;如果项目是套餐时，所包含的单品项目
     * }
     * @return \Dingo\Api\Http\Response
     */
    public function edit(Request $request)
    {
        $data = $request->all();

        $validator = $this->MedicineEditValidator($data);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $medcine = $this->model->get_medicine_by_code($data['code']);

        if(!$medcine)
        {
            return $this->error(100008, '项目编号数据不存在');
        }

        $update = $this->model->update_data($data);

        if($update)
        {
            return $this->success();
        }

        return $this->error(100000, '操作失败');
    }

    /**
     * 项目信息删除
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $medcine = $this->model->get_medicine_by_code($request->code);

        if(!$medcine)
        {
            return $this->error(100008, '项目编号数据不存在');
        }

        $del = $this->model->delete_data($request->code);

        if(!$del)
        {
            return $this->error(10000, '操作失败');
        }

        return $this->success();
    }

    /**
     * 项目信息验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function MedicineValidator($data)
    {
        $validator = \Validator::make($data, [
            'code' => 'bail|required|unique:medicine',
            'name' => 'bail|required',
            'spell' => 'bail|required',
//            'amount' => 'bail|required',
//            'unit' => 'bail|required',
            'type' => 'bail|required',
        ], [
            'code.required' => '项目编号不能为空',
            'code.unique' => '项目编号已存在',
            'name.required' => '项目名称不能为空',
            'spell.required' => '项目拼音快捷码不能为空',
            'amount.required' => '项目价格不能为空',
            'unit.required' => '项目单位不能为空',
            'type.required' => '项目类型不能为空',
        ]);

        return $validator;
    }


    /**
     * 项目信息修改验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function MedicineEditValidator($data)
    {
        $validator = \Validator::make($data, [
            'code' => 'bail|required',
        ], [
            'code.required' => '项目编号不能为空',
        ]);

        return $validator;
    }
}