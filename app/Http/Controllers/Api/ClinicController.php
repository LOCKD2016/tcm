<?php

namespace App\Http\Controllers\Api;

use App\Models\Clinic;
use Illuminate\Http\Request;
use App\Transformers\Api\ClinicTransformer;
use App\Transformers\Api\ClinicDetailTransformer;

class ClinicController extends ApiController
{
    /**
     * @var Clinic
     */
    protected $clinic;

    protected $page = 10;

    /**
     * ClinicController constructor.
     * @param Clinic $clinic
     */
    public function __construct(Clinic $clinic)
    {
        $this->clinic = $clinic;
    }

    /**
     * 诊疗列表
     * @param Request $request
     */
    public function index(Request $request)
    {
        $data = $request->all();

        $lists = $this->clinic
            ->clinicType($data['search']['type'] ?? '')
            ->clinicFirst($data['search']['first'] ?? '')
            ->clinicStatus($data['search']['status'] ?? '')
            ->clinicUser($data['search']['user'] ?? '')
            ->clinicDoctor($data['search']['doctor'] ?? '')
            ->clinicCreatedTime($data['search']['created_at'] ?? '')
            ->orderBy('id', 'desc')
            ->paginate($this->page);

        return $this->response()->paginator($lists, new ClinicTransformer());
    }

    /**
     * 诊疗详情
     * @param $id
     */
    public function show($id)
    {
        $clinic = $this->clinic->find($id);

        if (!$clinic) {
            return $this->error(100, '诊疗不存在');
        }

        return $this->response()->item($clinic, new ClinicDetailTransformer());
    }

}
