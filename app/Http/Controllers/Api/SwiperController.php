<?php

namespace App\Http\Controllers\Api;


use App\Models\Swiper;
use Illuminate\Http\Request;
use App\Transformers\Api\SwiperTransformer;

class SwiperController extends ApiController
{
    /**
     * @var $slider
     */
    protected $swiper;

    protected $page = 10;

    /**
     * SliderController constructor.
     * @param Slider $slider
     */
    public function __construct(Swiper $swiper)
    {
        $this->swiper = $swiper;
    }

    /**
     * 轮播图列表
     */
    public function index()
    {
        $swiper = $this->swiper->paginate($this->page);

        if (!$swiper) {
            return $this->error(100, '没有数据');
        }

        return $this->response()->paginator($swiper, new SwiperTransformer());
    }

    /**
     * 修改轮播图
     * @param Request $request
     */
    public function sliderUpdate(Request $request)
    {
        $data = $request->all();

        $validator = $this->swiperValidator($data);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $slider = $this->swiper->find($data['id']);

        $slider->fill($data);

        if ($slider->save()) {
            return $this->success(200, '修改成功');
        };

        return $this->error(100, '修改失败');

    }

    /**
     * 添加轮播图
     * @param Request $request
     * @return mixed
     */
    public function sliderAdd(Request $request)
    {
        $data = $request->all();

        $validator = $this->swiperValidator($data);

        if ($validator->fails()) {
            return $this->error(100, $validator->errors()->first());
        }

        $swiper = $this->swiper->create($data);

        if ($swiper) {
            return $this->success(200, '添加成功');
        }

        return $this->error(100, '添加失败');
    }

    /**
     * 删除轮播图
     * @param $id
     */
    public function sliderDelete($id)
    {
        $slider = $this->swiper->find($id);

        if ($slider) {
            return $this->success($slider->delete());
        }

        return $this->error(100, '操作失败');
    }


    /**
     * 验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function swiperValidator($data)
    {
        $validator = \Validator::make($data, [
            //'url' => 'required',
            'image' => 'required'
        ], [
            //'url.required' => '链接地址不能为空',
            'image.required' => '图片地址不能为空',
        ]);
        return $validator;
    }
}