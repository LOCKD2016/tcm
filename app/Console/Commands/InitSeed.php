<?php

namespace App\Console\Commands;

use App\Events\Event;
use App\Models\Activity;
use App\Models\Doctor;
use App\Models\Lottery;
use App\Models\LotteryLog;
use App\Models\Usertreausure;
use App\Models\WinningInfo;
use App\Models\YiCheUser;
use App\Repositories\LotteryRepository;
use App\Services\YiCheService;
use App\Util\LuckyDraw;
use App\Util\Tools;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Rap2hpoutre\FastExcel\FastExcel;
use WebIM\Models\AppUser;

class  InitSeed extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:seed {type?} {--param=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'init some table of database when the environment is pro';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $connect = 'mysql';
    protected $schema = '';
    protected $activity = '';
    const RKEY_PRE = 'LuckyDrawCode';
    /**
     * @var LotteryRepository
     */
    protected $lotteryRepository;

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
        $type = $this->argument('type');
        $param = $this->option('param');

        $url= env('APP_URL');
        $url2= env('STATIC_URL');
        dd($url,$url2);
        $app_user = new  AppUser();
        $data = $app_user->get();
        foreach ($data as $item) {
            //dump('id:' . $item->id . '--name:' . $item->name . '--img:' . $item->head_img);

            $item->head_img_L=$str=str_replace('www.taiheguoyi.com', 'app.taiheguoyi.com', $item->head_img_L);
            $is_ok=$item->save();
                  dump('id:' . $item->id . '--name:' . $item->name . '--img:' . $item->head_img.'str:'.$str,$is_ok);
         }
    }


}



