<?php

namespace App\Console\Commands;

use App\Http\Controllers\Api\SyncController;
use Illuminate\Console\Command;


class SyncScheduling extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:scheduling';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步医生排班';

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
        $sync = app('App\Http\Controllers\Api\SyncController');
        return $sync->scheduling();
        //return  redirect(app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('scheduling'));

    }
}
