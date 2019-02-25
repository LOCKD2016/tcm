<?php
namespace App\Http\WxControllers;

use App\Repository\SwiperRepository;
use App\Transformers\SwiperTransformer;
use App\Util\Tools;

/**
 * Class SwiperController
 * @Auth: kingofzihua
 * @package App\Http\WxControllers
 */
class SwiperController extends Controller
{

    /**
     * @Auth: kingofzihua
     * @var SwiperRepository
     */
    protected $swiper;

    /**
     * @Auth: kingofzihua
     * SwiperController constructor.
     * @param $swiper
     */
    public function __construct(SwiperRepository $swiper)
    {
        $this->swiper = $swiper;
    }

    /**
     * 获取轮播图
     * @return mixed
     */
    public function lists()
    {
        $lists = $this->swiper->get_show_data();
        Tools::zp_log('swiper',$lists);
        return $this->response()->collection($lists, new SwiperTransformer());
    }
}
