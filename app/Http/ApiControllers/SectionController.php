<?php

namespace App\Http\ApiControllers;

use App\Repository\SectionRepository;
use App\Transformers\SectionTransformer;

/**
 * Class SectionController
 * @package App\Http\ApiControllers
 */
class SectionController extends Controller
{
    /**
     * @var
     */
    public $model;

    /**
     * SectionController constructor.
     * @param SectionRepository $sectionRepository
     */
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->model = $sectionRepository;
    }

    /**
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->model->get_all_data();

        return $this->response()->collection($lists, new SectionTransformer());
    }
}