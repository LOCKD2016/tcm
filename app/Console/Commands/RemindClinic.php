<?php

namespace App\Console\Commands;

use App\Repository\SMSCodeRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Auth\LBWechat;
use App\Util\Tools;

class RemindClinic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:clinic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '前一天晚上八点短信提醒患者明天门诊';

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

        $result = DB::table('bespeaks')
            ->where('type',1)
            ->where('status',15)
            ->whereNull('deleted_at')
            ->whereDate('start_time', Carbon::parse('tomorrow')->toDateTimeString())
            ->chunk(100,function ($list){
                foreach($list as $k=>$v){
                    $return_msg = (new SMSCodeRepository())->remind_user_tomorrow_is_clinic_day($v);
                    if($return_msg != 'ok')
                        DB::table('error_note')->insert(['type'=>2, 'code'=>$v->id, 'content'=>$return_msg.'Bespeak_id============================================'.$v->id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }
            });
    }
}
