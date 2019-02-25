<?php

namespace App\Console\Commands;

use App\Http\WxControllers\RecipeController;
use App\Repository\WX\RecipeRepository;
use Illuminate\Console\Command;

class OrderComment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:comment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单评论';

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
        return (new RecipeController(new RecipeRepository()))->comment();
    }
}
