<?php
namespace App\Http\WxControllers;


use App\Models\Disease;

/**
 * Class SectionController
 * @package App\Http\WxControllers
 */
class DiseaseController extends Controller
{
    /**
     * @var
     */
    protected $disease;

    /**
     * SectionController constructor.
     * @param $section
     */
    public function __construct(Disease $disease)
    {
        $this->disease = $disease;
    }

}
