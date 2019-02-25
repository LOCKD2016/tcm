<?php

namespace App\Console\Commands;

use App\Models\Comment;
use Illuminate\Console\Command;
use App\Models\Doctor;
use App\Models\Config;
use PhpParser\Comment\Doc;

class CommentDoctor extends Command
{
    /**
     * The name and signature of the console command1.
     *
     * @var string
     */
    protected $signature = 'comment:doctor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步医生的评论指数';

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


    }
}
