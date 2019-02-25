<?php
namespace App\Http\PlatformControllers;

use Illuminate\Http\Request;
use App\Repository\PrescriptionRepository;
use App\Transformers\Platform\PrescriptionTransformer;

/**
 * Class PrescriptionController
 * @Auth: Nnn
 * @package App\Http\PlatformControllers
 */
class PrescriptionController extends Controller
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
    public function __construct(PrescriptionRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 药方列表
     * @Auth: Nnn
     * @param Request $request
     * {
            'page'  页数
     *      'doctorName' 医生姓名模糊搜索
     * }
     *
     */
    public function index(Request $request)
    {
        if(!$request->get('doctorCode') && !$request->get('userCode'))
        {
            return $this->error(100010, '参数缺失');
        }

        $data = [];

        $lists = $this->model->get_platform_recription_list($request->all());

        if($lists) {
            foreach($lists as $val) {
                $data[] = (new PrescriptionTransformer())->transformData($val);
            }
        }
        return $this->success($data);

    }

}