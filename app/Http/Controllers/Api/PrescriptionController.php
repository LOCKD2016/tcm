<?php

namespace App\Http\Controllers\Api;

use Auth;
use DB;
use App\Auth\LBWechat;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Transformers\Api\PrescriptionTransformer;

class PrescriptionController extends ApiController
{

    /**
     * @var Prescription
     */
    protected $prescription;

    protected $page = 10;

    /**
     * PrescriptionController constructor.
     * @param Prescription $prescription
     */
    public function __construct(Prescription $prescription)
    {
        $this->prescription = $prescription;
    }

    /**
     * 划价收费列表
     * @param Request $request
     */
    public function priceList(Request $request)
    {
        $data = $request->all();

        $lists = $this->prescription
            ->queryUserName($data['userName'] ?? '')
            ->queryDoctorName($data['doctorName'] ?? '')
            ->paginate($this->page);

        return $this->response()->paginator($lists, new PrescriptionTransformer());
    }

    /**
     * 药方操作
     * @param $id
     * @param Request $request
     * @return mixed
     */
    public function setPrice($id, Request $request)
    {
        $data = $request->data;

        $prescription = $this->prescription->find($id);

        if (!$prescription) {
            return $this->error(100, '药方不存在');
        }

        if (isset($data['recipe_head']) && !empty($data['recipe_head'])) {
            $data['recipe_head'] = $data['recipe_head'] ?: json_encode($data['recipe_head']);
        }

        if (isset($data['is_price']) && $data['is_price'] = 1) {
            $data['price_time'] = date('Y-m-d H:i:s');
        }

        $data['admin_id'] = Auth::id();

        $update = $this->prescription->update_prescription_data($id, $data);

        if (!$update) {
            return $this->error(101, '操作失败');
        }

        return $this->success();
    }

}

