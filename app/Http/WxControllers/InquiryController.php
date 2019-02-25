<?php
/**
 * Created by PhpStorm.
 * User: Godlike
 * Date: 2018/1/8
 * Time: 11:56
 */
namespace App\Http\WxControllers;

use App\Repository\InquiryRepository;
use App\Transformers\InquiryTransformer;
/**
 * 标准问诊单
 * author zhoupeng
 * Class InquiryController
 * @package App\Http\WxControllers
 */
class InquiryController extends Controller
{
    /**
     * @var InquiryRepository
     */
    protected $inquiry;

    /**
     * InquiryController constructor.
     * @param InquiryRepository $inquiry
     */
    public function __construct(InquiryRepository $inquiry)
    {
        $this->inquiry = $inquiry;
    }

    /**获取标准问诊单详情
     *
     * @param $inquiry_id
     */
    public function detail($inquiry_id)
    {
        $detail = $this->inquiry->detail($inquiry_id);
        return $this->response()->item($detail, new InquiryTransformer());
    }
}