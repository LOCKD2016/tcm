<?php

namespace App\Http\Controllers\Api;

use DB;
use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\AppUser;
use App\Transformers\Api\AppUserTransformer;
use App\Transformers\Api\AppUserDetailTransformer;
use Maatwebsite\Excel\Facades\Excel;


class AppUserController extends ApiController
{
    /**
     * @var Doctor
     */
    protected $appuser;
    protected $page = 10;

    /**
     * AppUserController constructor.
     * @param AppUser $appuser
     */
    public function __construct(AppUser $appuser)
    {
        $this->appuser = $appuser;
    }


    /**
     * 获取患者列表
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->all()['search'];

        $sql = $this->appuser
            ->name($search['name'] ?? '')//患者姓名/昵称
            ->mobile($search['mobile'] ?? '')//患者手机号
            ->sex($search['sex'] ?? '')//患者性别
            ->orderByDesc();

        //是否是导出
        if (isset($search['export']) && $search['export']) {
            return $lists = $sql->get();
        }

        $lists = $sql->paginate($this->page);

        return $this->response()->paginator($lists, new AppUserTransformer());
    }

    /**
     * 获取患者详情
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function detail($id)
    {
        $data = AppUser::find($id);

        return $this->response()->item($data, new AppUserDetailTransformer());
    }

    /**
     * 数据管理--患者统计
     * @param Request $request
     * @return mixed
     */
    public function userCount(Request $request)
    {
        $params = $request->get('search');
        $man = [
            'gtSixty' => 0,
            'gtForty' => 0,
            'gtThirty' => 0,
            'gtNineteen' => 0,
            'gtThirteen' => 0,
            'gtZero' => 0,
            //'other'=>0,
            'all' => 0,
        ];
        $woman = [
            'gtSixty' => 0,
            'gtForty' => 0,
            'gtThirty' => 0,
            'gtNineteen' => 0,
            'gtThirteen' => 0,
            'gtZero' => 0,
        ];
        $age[] = date('Y', strtotime('-61 year', time()));
        $age[] = [date('Y', strtotime('-46 year', time())), date('Y', strtotime('-60 year', time()))];
        $age[] = [date('Y', strtotime('-31 year', time())), date('Y', strtotime('-45 year', time()))];
        $age[] = [date('Y', strtotime('-19 year', time())), date('Y', strtotime('-30 year', time()))];
        $age[] = [date('Y', strtotime('-13 year', time())), date('Y', strtotime('-18 year', time()))];
        $age[] = [date('Y', time()), date('Y', strtotime('-12 year', time()))];

        AppUser::when($params, function ($query) use ($params) {
            return $query->where($params);
        })->select(DB::raw('DATE_FORMAT(birthday,"%Y") as _age'), 'sex')
            ->chunk(100, function ($data) use (&$man, &$woman, $age) {
                $data = collect($data->toArray());
                $man['gtSixty'] += $data->where('_age', '<=', $age[0])->where('_age', '<>', null)->where('sex', 1)->count();
                $woman['gtSixty'] += $data->where('_age', '<=', $age[0])->where('_age', '<>', null)->where('sex', 2)->count();
                $man['gtForty'] += $data->where('_age', '>=', $age[1][1])->where('_age', '<=', $age[1][0])->where('sex', 1)->where('_age', '<>', null)->count();
                $woman['gtForty'] += $data->where('_age', '>=', $age[1][1])->where('_age', '<=', $age[1][0])->where('sex', 2)->where('_age', '<>', null)->count();
                $man['gtThirty'] += $data->where('_age', '>=', $age[2][1])->where('_age', '<=', $age[2][0])->where('sex', 1)->where('_age', '<>', null)->count();
                $woman['gtThirty'] += $data->where('_age', '>=', $age[2][1])->where('_age', '<=', $age[2][0])->where('sex', 2)->where('_age', '<>', null)->count();
                $man['gtNineteen'] += $data->where('_age', '>=', $age[3][1])->where('_age', '<=', $age[3][0])->where('sex', 1)->where('_age', '<>', null)->count();
                $woman['gtNineteen'] += $data->where('_age', '>=', $age[3][1])->where('_age', '<=', $age[3][0])->where('sex', 2)->where('_age', '<>', null)->count();
                $man['gtThirteen'] += $data->where('_age', '>=', $age[4][1])->where('_age', '<=', $age[4][0])->where('sex', 1)->where('_age', '<>', null)->count();
                $woman['gtThirteen'] += $data->where('_age', '>=', $age[4][1])->where('_age', '<=', $age[4][0])->where('sex', 2)->where('_age', '<>', null)->count();
                $man['gtZero'] += $data->where('_age', '>=', $age[5][1])->where('_age', '<=', $age[5][0])->where('sex', 1)->where('_age', '<>', null)->count();
                $woman['gtZero'] += $data->where('_age', '>=', $age[5][1])->where('_age', '<=', $age[5][0])->where('sex', 2)->where('_age', '<>', null)->count();
                $man['all'] += $data->count();
            });
        //$man['other'] = $man['all']*2 - collect($man)->sum()-collect($woman)->sum();
        unset($man['all']);
        $max = (collect($man)->max() - collect($woman)->max()) > 0 ? collect($man)->max() : collect($woman)->max();
        $max = ceil($max / 100) * 100;
        $sumMan = collect($man)->sum();
        $sumWoman = collect($woman)->sum();
        $info = [array_values($man), array_values($woman), $max, $sumMan, $sumWoman];
        return $this->success($info);
    }

}

?>