<?php

namespace App\Http\Controllers\Api;


use App\Models\Clinique;
use Illuminate\Http\Request;
use App\Transformers\Api\SwiperTransformer;

class CliniqueController extends ApiController
{
    /**
     * @var $slider
     */
    protected $clinique;

    protected $page = 10;

    /**
     * SliderController constructor.
     * @param Slider $slider
     */
    public function __construct(Clinique $clinique)
    {
        $this->clinique = $clinique;
    }

    /**
     * 诊所列表
     */
    public function index()
    {
        $clinique = $this->clinique->get();

        if (!$clinique) {
            return $this->error(100, '没有数据');
        }

        return $this->success($clinique);
    }

    /**
     * 修改诊所信息
     * @param Request $request
     */
    public function update(Request $request)
    {
        $data = $request->all();

        $clinique = $this->clinique->find($data['id']);

        $clinique->fill($data);

        if ($clinique->save()) {
            return $this->success(200, '修改成功');
        };

        return $this->error(100, '修改失败');

    }

}