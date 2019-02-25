<?php

namespace App\Console\Commands;

use App\Models\Orders;
use Illuminate\Console\Command;
use App\Repository\WX\OrdersRepository;
use App\Http\WxControllers\OrdersController;


/**
 * 排队预约15分钟内未付款的 取消预约
 * Class CancelRegister
 * @Auth: kingofzihua
 * @package App\Console\Commands
 */
class CancelRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '排队预约15分钟内未付款的 取消预约';

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
        return (new OrdersController(new Orders(), new OrdersRepository()))->cancelQueue();
    }
}
