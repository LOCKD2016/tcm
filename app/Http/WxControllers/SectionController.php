<?php
namespace App\Http\WxControllers;

use App\Repository\SectionRepository;
use App\Transformers\SectionTransformer;

/**
 * Class SectionController
 * @package App\Http\WxControllers
 */
class SectionController extends Controller
{
    /**
     * @var
     */
    protected $section;

    /**
     * SectionController constructor.
     * @param $section
     */
    public function __construct(SectionRepository $section)
    {
        $this->section = $section;
    }

    /**
     * 获取科室列表
     * @return mixed
     */
    public function lists()
    {
        $sections = $this->section->get_show_data();

        return $this->response()->collection($sections, new SectionTransformer());
    }

}
