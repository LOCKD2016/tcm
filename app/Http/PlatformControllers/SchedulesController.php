<?php
namespace App\Http\PlatformControllers;

use App\Repository\CliniqueRepository;
use App\Repository\ScheduleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class SchedulesController
 * @Auth: Nnn
 * @package App\Http\PlatformControllers
 */
class SchedulesController extends Controller
{
    /**
     * @Auth: Nnn
     * @var
     */
    protected $model;

    /**
     * @Auth: Nnn
     * SchedulesController constructor.
     * @param $model
     */
    public function __construct(ScheduleRepository $model)
    {
        $this->model = $model;
    }

    /**
     * 新增医生排班日期
     * @param Request $request
     * {
            customer_code	String		Y	医生编号
            clinique	    String		Y	诊所编号
           schedules       Array       Y
           [
             code	        String		Y	标识（对方）
             start_time	    String		Y	开始时间
             end_time	    String		Y	结束时间
           ]
     * }
     */
    public function save(Request $request, CliniqueRepository $cliniqueRepository)
    {
        $data = $request->all();

        $validator = $this->SchedulesValidator($data);

        if ($validator->fails())
        {
            return $this->error(100, $validator->errors()->first());
        }

        //查询医生id
        $doctor = DB::table('doctor_clinique')->where('code', $data['customer_code'])->first();

        if(!$doctor)
        {
            return $this->error(200000, '医生不存在，请先添加医生数据');
        }

        //查询诊所信息
        $clinique = $cliniqueRepository->get_data_by_code_with_create($data['clinique']);

        $scheduleData = [];

        foreach ($data['schedules'] as $key => $schedule) {
            try{
                $scheduleData[$key]['doctor_id'] = $doctor->doctor_id;
                $scheduleData[$key]['clinique_id'] = $clinique->id;
                $scheduleData[$key]['code'] = $schedule['code'];
                $scheduleData[$key]['start_time'] = $schedule['start_time'];
                $scheduleData[$key]['end_time'] = $schedule['end_time'];
                $scheduleData[$key]['date'] = date('Y-m-d', strtotime($schedule['start_time']));
                $scheduleData[$key]['created_at'] = date('Y-m-d H:i:s');
                $scheduleData[$key]['updated_at'] = date('Y-m-d H:i:s');
            }catch (\Exception $exception){
                return $this->error(200015, '参数缺失');
            }

        }

        if($this->model->get_data_by_insert_schedules($scheduleData))
        {
            return $this->success();
        }

        return $this->error(200010, '添加失败');
    }

    /**
     * 删除医生排班信息
     * @param $code
     */
    public function destroy(Request $request)
    {
        $delete = $this->model->delete_data_by_code($request->code);

        if($delete)
        {
            return $this->success();
        }

        return $this->error(200040, '删除失败');
    }

    /**
     * 排班信息新增验证
     * @param $data
     * @return \Illuminate\Validation\Validator
     */
    public function SchedulesValidator($data)
    {
        $validator = \Validator::make($data, [
//            'code' => 'bail|required|unique:schedules',
            'customer_code' => 'bail|required',
            'clinique' => 'bail|required',
            'schedules' => 'bail|required',
//            'start_time' => 'bail|required',
//            'end_time' => 'bail|required',
        ]);

        return $validator;
    }

}