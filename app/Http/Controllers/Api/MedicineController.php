<?php

namespace App\Http\Controllers\Api;

use App\Models\Medicine;
use Illuminate\Http\Request;
use App\Transformers\Api\MedicineTransformer;

class MedicineController extends ApiController
{
    /**
     * @var Medicine
     */
    protected $medicine;

    protected $page = 10;

    /**
     * CommentController constructor.
     * @param Comment $comment
     */
    public function __construct(Medicine $medicine)
    {
        $this->medicine = $medicine;
    }

    /**
     * 评论列表
     * @param Request $request
     */
    public function index(Request $request)
    {
        $lists = $this->medicine
            ->medicineName($request->name ?? '')
            ->paginate($this->page);

        return $this->response()->paginator($lists, new MedicineTransformer());
    }

    /**
     * 药品信息修改
     * @param Request $request
     * @return mixed
     */
    public function save(Request $request)
    {
        $medicine = $this->medicine->find($request->id);

        if (!$medicine) {
            return $this->error(100, '药品不存在，请刷新');
        }

        $medicine->name = $request->name;
        $medicine->unit = $request->unit;
        $medicine->amount = $request->amount * 100;

        if ($medicine->save()) {
            return $this->success();
        }

        return $this->error(100, '修改失败');
    }

    /**
     * 药品删除
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function del($id)
    {
        $medicine = $this->medicine->find($id);

        if (!$medicine) {
            return $this->error(100, '药品不存在，请刷新');
        }

        $del = $this->medicine->where('id', $id)->delete();

        if ($del) {
            return $this->success();
        }

        return $this->error(101, '删除失败');
    }

}
