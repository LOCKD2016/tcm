<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\SyncController;
use Illuminate\Console\Command;


class RefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '刷新token';

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
        //调用同步医生排班路由
        $sync = app('App\Util\YunZhongYi');
        return $sync->requestToken();
        //return  redirect(app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('scheduling'));

    }
}
