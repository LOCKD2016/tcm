<?php

namespace App\Http\Controllers\Api;

use App\Models\Admtelephones;
use App\Util\Tools;
use Illuminate\Http\Request;

class TelController extends ApiController
{
    public function index()
    {
        /*if (empty($page)){
            $page = 1;
        }*/
        $admtelephone = new Admtelephones();
//        $telephone = $admtelephone->getTelephoneList();
//        return $telephone;
        $list = $admtelephone->get_list();
        if(count($list))
            return $this->success($list);
        return $this->error(101,'没有数据');
    }

    public function addtelephone(Request $request)
    {
        $input  = $request->all();
        if (!Tools::isMobile($input['telephone']))
            return $this->error(101,'请输入正确的手机号');
        $params = [
            'kname'=>$input['kname'],
            'telephone'=>$input['telephone'],
            'clinique_id'=>$input['clinique_id'],
        ];
        $detail = Admtelephones::where($params)->first();
        if(count($detail))
            return $this->error(101,'该客服已存在！');
        $telephone = Admtelephones::create($params);
        if(count($telephone))
            return $this->success();
        return $this->error(101);
    }

    /**
     *  删除客服信息
     * @author 海明
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function delTelephone($id)
    {
        $telephone = Admtelephones::find($id);
        if (!count($telephone))
            return $this->error(101, '没有找到该客服信息');
        if ($telephone->delete())
            return $this->success();
        return $this->error(101, '操作失败');
    }

    public function updateTelephone($id, Request $request)
    {
        $input = $request->all();
        $telephone = Admtelephones::where('id', '=', $id)
                    ->update($input);
    }

    public function updatestatus($id)
    {
        $detail = Admtelephones::find($id);
        if (count($detail)) {
            if ($detail->status == 1) {
                $detail->status = 0;
                if ($detail->save()) {
                    return $this->success();
                } else {
                    return $this->error(101, '修改失败');
                }
            } else {
                $detail->status = 1;
                if ($detail->save()) {
                    return $this->success();
                } else {
                    return $this->error(101, '修改失败');
                }
            }
        }
    }


}
