<?php
namespace App\Http\WxControllers;

use App\Repository\PrescriptionRepository;
use App\Transformers\PrescriptionTransformer;

/**
 * Class PrescriptionController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class PrescriptionController extends Controller
{
    /**
     * @Auth: kingofzihua
     * @var
     */
    protected $prescription;

    /**
     * @Auth: kingofzihua
     * PrescriptionController constructor.
     * @param $prescription
     */
    public function __construct(PrescriptionRepository $prescription)
    {
        $this->prescription = $prescription;
    }

    /**
     * 通过编号获取详情
     * @Auth: kingofzihua
     * @param $prescription_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($prescription_id)
    {
        $detail = $this->prescription->get_data_by_id($prescription_id);

        return $this->response()->item($detail, new PrescriptionTransformer());
    }

    /**
     * 通过订单编号获取详情
     * @Auth: kingofzihua
     * @param $order_id
     * @return \Dingo\Api\Http\Response
     */
    public function show($order_id)
    {
        $detail = $this->prescription->get_data_by_order_id($order_id);

        return $this->response()->item($detail, new PrescriptionTransformer());
    }

}