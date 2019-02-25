<?php

namespace App\Console\Commands;

use App\Http\WxControllers\QueueController;
use App\Repository\WX\QueueRepository;
use Illuminate\Console\Command;

/**
 * 预约排队
 * Class RegisterQueue
 * @auth: kingofzihua
 * @package App\Console\Commands
 */
class RegisterQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每天定时执行的预约排队';

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
        return (new QueueController(new QueueRepository()))->make();
    }
}
