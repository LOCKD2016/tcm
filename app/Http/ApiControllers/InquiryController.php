<?php

namespace App\Http\ApiControllers;

use App\Repository\InquiryRepository;
use App\Transformers\InquiryTransformer;

/**
 * 标准问诊单
 * Class InquiryController
 * @package App\Http\ApiControllers
 */
class InquiryController extends Controller
{
    /**
     * @var
     */
    public $model;

    /**
     * InquiryController constructor.
     * @param InquiryRepository $inquiryRepository
     */
    public function __construct(InquiryRepository $inquiryRepository)
    {
        $this->model = $inquiryRepository;
    }

    /**
     * 标准问诊单
     * @param $inquiry_id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($inquiry_id)
    {
        $inquiry = $this->model->get_data_by_id($inquiry_id);

        if (empty($inquiry))
            return $this->error(404, '问诊单不存在');

        return $this->response()->item($inquiry, new InquiryTransformer());
    }
}