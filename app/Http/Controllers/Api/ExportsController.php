<?php

namespace App\Http\Controllers\Api;

use App\Models\AppUser;
use App\Models\Orders;
use App\Transformers\Api\Export\AppUserTransformer;
use App\Transformers\Api\Export\OrderTransformer;
use App\Transformers\Api\Export\PrescriptionOrderTransformer;
use App\Transformers\Api\Export\SendPrescriptionTransformer;
use Excel;
use Illuminate\Http\Request;

class ExportsController extends ApiController
{
    public function exports(Request $request)
    {
        $cellData = $request->all();

        $newRequest = (new Request());

        $params = $request->search;

        $params['export'] = true;

        $newRequest->offsetSet('search', $params);

        switch ($cellData['type']) {
            case 'app_user':
                $data = (new AppUserController(new AppUser()))->index($newRequest);

                foreach ($data as $model) {
                    $cellData['data'][] = (new AppUserTransformer())->transformData($model);
                }
                break;
            case 'drug_manage':
                $data = (new OrderController(new Orders()))->beaspeakOrderList($newRequest);

                foreach ($data as $model) {
                    $cellData['data'][] = (new OrderTransformer())->transformData($model);
                }
                break;
            case 'drug_medicinal':
                $data = (new OrderController(new Orders()))->prescriptionOrderList($newRequest);

                foreach ($data as $model) {
                    $cellData['data'][] = (new PrescriptionOrderTransformer())->transformData($model);
                }
                break;
            case 'send_recipe':
                $data = (new OrderController(new Orders()))->preSendList($newRequest);

                foreach ($data as $model) {
                    $cellData['data'][] = (new SendPrescriptionTransformer())->transformData($model);
                }
                break;

        }

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
