<?php

namespace App\Console\Commands;

use App\Models\Config;
use Illuminate\Console\Command;

/**
 * Class ClearCancelRegisterCount
 * @package App\Console\Commands
 */
class ClearCancelRegisterCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:cancel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月清空用户的取消预约的次数';

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
        $conf = Config::where('type',1)->first();
        if(isset($conf->info['M']))
            \DB::select("update app_users set `cancel_count`=".$conf->info['M']);
    }
}
