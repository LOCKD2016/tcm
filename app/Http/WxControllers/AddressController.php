<?php

namespace App\Http\WxControllers;

use App\Repository\AddressRepository;
use App\Transformers\AddressTransformer;
use App\Http\Requests\AddressSaveRequest;

/**
 * Class AddressController
 * @package App\Http\WxControllers
 */
class AddressController extends Controller
{
    /**
     * @var
     */
    public $model;

    /**
     * AddressController constructor.
     * @param AddressRepository $addressRepository
     */
    public function __construct(AddressRepository $addressRepository)
    {
        $this->model = $addressRepository;
    }

    /**
     * 用户地址列表
     * @return \Dingo\Api\Http\Response
     */
    public function lists()
    {
        $list = $this->model->get_user_data_list(\Auth::id());

        return $this->response()->collection($list, new AddressTransformer());
    }

    /**
     * 地址详情
     * @param $address_id
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function detail($address_id)
    {
        $data = $this->model->get_data_by_id($address_id);

        if (empty($data) || $data->user_id != \Auth::id())
            return $this->error(403, '拒绝访问');

        return $this->response()->item($data, new AddressTransformer());
    }

    /**
     * 添加
     * @param AddressSaveRequest $request
     * @return mixed
     */
    public function save(AddressSaveRequest $request)
    {
        $address = $this->model->create(array_merge($request->all(), ['user_id' => \Auth::id()]));

        if ($address) {
            if ($request->is_default) {//当前为默认
                //设为默认值
                $this->model->set_user_default_address(\Auth::id(), $address->id);
            }

            return $this->success([], '添加成功');
        }

        return $this->error(500, '添加失败，清稍后重试');
    }

    /**
     * 修改地址
     * @param AddressSaveRequest $request
     * @param $address_id
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function edit(AddressSaveRequest $request, $address_id)
    {
        $data = $this->model->get_data_by_id($address_id);

        if (empty($data) || $data->user_id != \Auth::id())
            return $this->error(403, '拒绝访问');

        //更新数据
        $data->update($request->all());

        return $this->response()->item($data, new AddressTransformer());
    }


    /**
     * 设为默认地址
     * @param $address_id
     * @return mixed
     */
    public function setDefault($address_id)
    {
        $update = $this->model->set_user_default_address(\Auth::id(), $address_id);

        return $update ? $this->success([], '设置成功') : $this->error(500, '设置失败');
    }

    /**
     * 删除地址
     * @param $address_id
     * @return mixed
     */
    public function delete($address_id)
    {
        $address = $this->model->get_data_by_id($address_id);

        if ($address) {

            $address->delete();

            return $address->trashed() ? $this->success([], '删除成功') : $this->error(500, '删除失败');
        }

        return $this->error(404, '地址不存在');
    }
}