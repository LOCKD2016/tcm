<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Clinic;
use App\Models\Recipe;
use App\Util\YunZhongYi;


/**
 * 同步诊疗详情
 * Class SyncClinicDetail
 * @package App\Console\Commands
 */
class SyncClinicDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:clinic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每日同步门诊的诊疗详情';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //获取已经导预约时间并且没有被同步过的诊疗详情的门诊数据
        $data = Clinic::where(['recipe_status' => 1, 'is_sync' => 0])->get();
        logger('同步诊疗----------------------' .date('Y-m-d'));
        foreach ($data as $v) {
            $res = collect((new YunZhongYi())->getClinicByRegistNum($v->registNum));
            if ($res->count()) {
                //如果存在就诊记录  则回去第一条数据
                $first = $res->first();

                $v->reply_content = $first->diagText; //诊断详情
                $v->status = 2; //状态改为结束
                $v->is_sync = 1; //标记已同步
                //判断是否开药方
                if (count($first->treatment)) {
                    $v->is_prescription = 1;
                    $v->is_take_prescription = 1;
                    $this->insertRecipe($v, $first->treatment[0]);
                }
                $v->save();
                //更改预约的诊疗状态 改为问诊结束
                \DB::table('subscribe')->where('id', $v->subscribe_id)->update(['status' => 7]);
            }
        }
    }

    /**
     * 同步药方数据
     * @param $clinic
     * @param $content
     */
    public function insertRecipe($clinic, $content)
    {
        /**
         * 定义药方容器
         */
        $ret = array();
        /**
         * 把药方拆分为数组
         * 0 => "红花(先煎 自备 10.0g)"
         * 1 => "合欢皮(煎汤代水饮 自备 1.0g)"
         * 2 => "水红花子( 10.0g)"
         * 3 => "合欢花(后下 10.0g)"
         * 4 => ""
         */

        $recipeContent = explode(',', $content->content);

        foreach ($recipeContent as $v) {
            /**
             * 拆分
             *  0 => "红花"
             * 1 => "先煎 自备 10.0g)"
             */
            $recipeOne = explode('(', $v);
            if (count($recipeOne) < 2) continue;
            $nowRet = array();
            $nowRet['name'] = $recipeOne[0];
            /**
             *   0 => "先煎"
             * 1 => "自备"
             * 2 => "10.0g)"
             */
            $recipeOneDetail = (explode(' ', $recipeOne[1]));
            $nowRet['other'] = $recipeOneDetail[0];
            $str = str_replace(')', '', array_pop($recipeOneDetail));
            $nowRet['unit'] = mb_substr($str, -1, 1, 'utf-8');
            $nowRet['dosage'] = str_replace($nowRet['unit'], '', $str);
            $ret[] = $nowRet;
        }

        $recipeInsert = array();
        $recipeInsert['recipe'] = \GuzzleHttp\json_encode($ret);
        $recipeInsert['recipe_head'] = \GuzzleHttp\json_encode(['sum' => $content->amount]);
        $recipeInsert['user_id'] = $clinic->user_id;
        $recipeInsert['family_id'] = $clinic->family_id;
        $recipeInsert['clinic_id'] = $clinic->id;
        $recipeInsert['recipe_remark'] = $content->advice;//医嘱
        $recipeInsert['type'] = 2;
        Recipe::insert($recipeInsert);
    }
}
