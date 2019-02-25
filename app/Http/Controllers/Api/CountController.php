<?php

namespace App\Http\Controllers\Api;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Orders;
use App\Models\Prescription;
use App\Models\Bespeak;
use Illuminate\Http\Request;
use DB;
use Excel;
use App\Http\Controllers\PaginatorController;

class CountController extends ApiController
{
    private $doctor;
    private $clinic;
    private $bespeak;
    private $prescription;

    public function __construct(Doctor $doctor, Clinic $clinic, Bespeak $bespeak, Prescription $prescription)
    {
        $this->clinic = $clinic;
        $this->doctor = $doctor;
        $this->bespeak = $bespeak;
        $this->prescription = $prescription;
    }

    public function manage(Request $request)
    {
        $start = $request->time['startTime'];
        $end = $request->time['endTime'];
        if (!empty($start) && !empty($end)) {
            if (strtotime($end) < strtotime($start))
                return $this->error(100, '结束时间大于等于开始时间');
            $num = (strtotime($end) - strtotime($start)) / (60 * 60 * 24) + 1;
            if ($num > 31) return $this->error(100, '时间不能超过31天');
            for ($i = 1; $i <= $num; $i++) {
                $arr[] = date('Y-m-d', strtotime($end) - ($i - 1) * 60 * 60 * 24); //格式化本周的时间
            }
        } elseif (!empty($start) && empty($end)) {
            $num = (strtotime(date('Y-m-d', time())) - strtotime($start)) / (60 * 60 * 24) + 1;
            if ($num > 31) return $this->error(100, '时间不能超过31天');
            for ($i = 1; $i <= $num; $i++) {
                $arr[] = date('Y-m-d', time() - ($i - 1) * 60 * 60 * 24); //格式化本周的时间
            }
        } elseif (empty($start) && !empty($end)) {
            return $this->error(100, '请选择开始时间');
        } else {
            for ($i = 1; $i <= 7; $i++) {
                $arr[] = date('Y-m-d', time() - $i * 60 * 60 * 24); //格式化本周的时间
            }
        }
        $sub = Bespeak::where(function ($query) use ($arr) {
            return $query->whereDate('created_at', '>=', $arr[count($arr) - 1])
                ->whereDate('created_at', '<=', $arr[0]);
        })
            ->select('bespeaks.*', DB::raw("date(created_at) as time"))->get();

        $rep = Prescription::whereDate('prescription.created_at', '>=', $arr[count($arr) - 1])
            ->whereDate('prescription.created_at', '<=', $arr[0])
            ->whereIn('prescription.is_price', [3, 4, 5, 7, 6])
//            ->whereIn('prescription.type', [0, 1])
            ->select('prescription.*', DB::raw("date(created_at) as time"))->get();
        $data = [];
        foreach ($arr as $k => $v) {
            $data[$k]['time'] = $v; //时间
            $data[$k]['sub'] = $sub->where('time', $v)->where('type', 0)->where('status', '!=', 35)->count() +
                $sub->where('stime', $v)->where('type', 1)->where('status', '!=', 35)->count(); // 预约人数
            $data[$k]['accept'] = $sub->where('deleted_at', null)->where('type', 0)
                ->where('time', $v)->whereIn('status', [10, 15, 20, 25])->count();//接诊数
            //$data[$k]['transfer'] = $sub->where('time', $v)->whereIn('status', [12, 13])->count();//转诊数
            $data[$k]['people'] = $sub->where('time', $v)->where('type', 1)->where('status', '!=', 35)->count();//门诊
            $data[$k]['net'] = $sub->where('time', $v)->where('type', 0)->where('status', '!=', 35)->count();//网诊
            $data[$k]['medicinal'] = $rep->where('time', $v)->count();//抓药
            $data[$k]['help'] = $rep->where('time', $v)->where('tisane', 1)->count();//代煎
            $data[$k]['express'] = $rep->where('time', $v)->where('express', 1)->count();//快递量
        }

        if (isset($request->get('search')['export'])) {
            return $data;
        } else {
            return $this->success($data);
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function exports(Request $request)
    {
        $cellData = $request->all();
        $title = $request->title . date('YmdHis', time());
        Excel::create($title, function ($excel) use ($cellData, $title) {
            $excel->sheet($title, function ($sheet) use ($cellData) {
                $sheet->prependRow(1, $cellData['head']);
                $sheet->setWidth($cellData['width']);
                if (isset($cellData['data']))
                    $sheet->rows($cellData['data']);
                $sheet->setFontSize(14);
            });
        })->store('xls');
        if (file_exists(storage_path() . '/exports/' . $title . '.xls')) {
            return $this->success($title . '.xls');
        }
        return $this->error(100, '导出失败,请稍后重试');

    }

}