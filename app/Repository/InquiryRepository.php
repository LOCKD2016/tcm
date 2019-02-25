<?php
namespace App\Repository;

use App\Models\Inquiry;

/**
 * Class InquiryRepository
 * @Auth: kingofzihua
 * @package App\Repository
 */
class InquiryRepository extends Repository
{
    /**
     * @Auth: kingofzihua
     * @return Inquiry
     */
    public function model()
    {
        return new Inquiry();
    }

    /**获取标准问诊单详情
     *  author zhoupeng
     * @param $inquiry_id
     */
    public function detail($inquiry_id)
    {
        return $this->model()->find($inquiry_id);
    }

    public function get_detail_by_bespeak_id($bespeak_id)
    {
        return $this->model()->where('bespeak_id',$bespeak_id)->first();
    }

}