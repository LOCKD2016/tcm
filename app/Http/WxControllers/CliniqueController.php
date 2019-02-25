<?php
namespace App\Http\WxControllers;

use App\Repository\CliniqueRepository;
use App\Transformers\CliniqueTransformer;

/**
 * 诊所
 * Class CliniqueController
 * @package App\Http\WxControllers
 */
class CliniqueController extends Controller
{
    /**
     * @var CliniqueRepository
     */
    protected $clinique;

    /**
     * CliniqueController constructor.
     * @param $clinique
     */
    public function __construct(CliniqueRepository $clinique)
    {
        $this->clinique = $clinique;
    }

    /**
     * @Auth: kingofzihua
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $lists = $this->clinique->get_all_data();

        return $this->response()->collection($lists, new CliniqueTransformer());
    }
}
