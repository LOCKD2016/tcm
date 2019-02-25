<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repository\WX\SubscribeRepository;
use App\Http\WxControllers\SubscribeController;


/**
 * 每天定时执行的挂号
 * Class FamilyRegister
 * @Auth: kingofzihua
 * @package App\Console\Commands
 */
class FamilyRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'family:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天定时执行的挂号任务';

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
        return (new SubscribeController(new SubscribeRepository()))->register();
    }
}
