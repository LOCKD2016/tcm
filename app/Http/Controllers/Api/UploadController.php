<?php

namespace App\Http\Controllers\Api;

use App\Models\ActivePro;
use App\Models\Upload;
use App\Models\MobilePro;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Jobs\SendReminderMobile;
use Illuminate\Support\Facades\DB;

class UploadController extends ApiController
{
    public function create(Request $request, Upload $upload)
    {
        //return $request->all();
        $row = $upload->add($request);
        if ($row == false) {
            return $this->error(0, "上传图片失败");
        } else {
            return $this->success($row);
        }
    }

    public function addresize(Request $request, Upload $upload)
    {
        $row = $upload->add_and_resize($request);
        if ($row == false) {
            return $this->error(0, "上传图片失败");
        } else {
            return $this->success($row);
        }
    }


    public function downLoadFile($id)
    {
        $filePath = storage_path() . '/exports/' . $id;
        return response()->download($filePath);
    }

    public function addTo(Request $request)
    {
        if (!$request->hasFile('file')) {
            return false;
        }
        $file = $request->file("file");
        if (!$file->isValid()) {
            return false;
        }
        $entension = $file->getClientOriginalExtension(); //上传文件的后缀.
        $newName = date('ymdhis') . "." . $entension;
        $file->move(storage_path() . '/imports', $newName);
        return '/imports/' . $newName;
    }

    public function ExcelToArray($path)
    {
        $res = array();
        Excel::load($path, function ($reader) use (&$res) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
        });
        return $res;
    }

    //上传导入文件
    public function addfile($id, Request $request, Upload $upload)
    {
        $path = $this->addTo($request);
        $path = storage_path() . $path;
        $res = $this->ExcelToArray($path);
        $mobile = array();
        foreach ($res as $k => $v) {
            if ($k != 0) {
                $mobile[] = trim($v[1]);
            }
        }
        //去除空值
        $array = array_values(array_filter($mobile));
        $insert = array();
        $newinsert = array();
        $ids = array();
        DB::beginTransaction();
        $count = count($array);
        $data = DB::table('active_promocodes')->where('id', $id)->select('total', 'num')->first();
        $surnum = $data->total - $data->num;
        if ($surnum < $count) {
            return array('msg' => '您最多可发放' . $surnum . '个手机号', 'status' => 0);
        }
        $code = DB::table('promocodes')->where([['active_id', $id], ['status', 0]])->limit($count)->get()->toArray();
        $discount = ActivePro::where('id', $id)->value('discount');
        foreach ($code as $k => $v) {
            $ids[] = $v->id;
        }

        foreach ($array as $k => $v) {
            $insert[$k]['mobile'] = $v;
            $insert[$k]['code_id'] = $ids[$k];
            $insert[$k]['discount'] = $discount;

            $newinsert[$k]['mobile'] = $v;
            $newinsert[$k]['active_id'] = $id;
            $newinsert[$k]['code_id'] = $ids[$k];
            $newinsert[$k]['created_at'] = date('Y-m-d H:i:s');

        }
//        echo json_encode($insert);
//        die;

        $row = MobilePro::insert($newinsert);
        $update = DB::table('promocodes')->whereIn('id', $ids)->update(['status' => 1]);
        $num = DB::table('active_promocodes')->where('id', $id)->increment('num', $count);
        foreach ($insert as $v) {
            $this->dispatch(new SendReminderMobile($v));
        }
        if ($row && $update && $num) {
            DB::commit();
            return array('msg' => '成功发放' . $count . '个手机号', 'status' => 1);
        } else {
            DB::rollBack();
            return array('msg' => '导入失败', 'status' => 0);
        }
    }





}
