<?php

namespace App\Console;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\OrderClose::class,
        \App\Console\Commands\RemindClinic::class,
        \App\Console\Commands\InitSeed::class,
//        \App\Console\Commands\SyncScheduling::class,
//        \App\Console\Commands\RefreshToken::class,
//        \App\Console\Commands\FamilyRegister::class, //每天定时执行的挂号
//        \App\Console\Commands\RegisterQueue::class, //每天定时执行的预约排队
//        \App\Console\Commands\CancelRegister::class, //排队预约15分钟内未付款的 取消预约?
//        \App\Console\Commands\RecipeOverdue::class, //排队预约15分钟内未付款的 取消预约
//        \App\Console\Commands\OrderComment::class, //订单评价
//        \App\Console\Commands\CommentDoctor::class, //定时同步医生的推荐指数几颗星 取平均值 //这个现在没有用，现在是在后台有一个评论审核，审核通过的时候执行计算平均星星的功能
//        \App\Console\Commands\ClearCancelRegisterCount::class, //每月清空用户的取消预约的次数
//        \App\Console\Commands\SyncClinicDetail::class, //每天同步门诊的诊疗记录
//        \App\Console\Commands\RemindLnquiry::class,
//        \App\Console\Commands\RemindLnquiry::class,
//        \App\Console\Commands\CompleteLnquiry::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('order:close')->everyMinute();
        $schedule->command('remind:clinic')->dailyAt('20:00');
//        $schedule->command('sync:scheduling')->dailyAt('00:00');
//        $schedule->command('refresh:token')->hourly();
//        $schedule->command('family:register')->dailyAt('06:00'); //每天早上6点开始执行
//        $schedule->command('register:queue')->everyFiveMinutes(); //每天定时执行的预约排队 每5分钟执行一次
//        $schedule->command('cancel:register')->everyMinute(); //每天定时执行的取消预约 每分钟执行一次
//        $schedule->command('recipe:overdue')->everyMinute(); //每天定时执行的药方过期 每分钟执行一次
//        $schedule->command('comment:doctor')->daily(); //每天同步一次医生的推荐指数 几颗星
//        $schedule->command('order:comment')->everyMinute(); //每天早上6点开始执行，判断订单是否可以评价  测试下 现改成每分钟 后面改回来
//        $schedule->command('clear:cancel')->monthlyOn(1, '00:00'); //每月清空用户的取消预约的次数
//        $schedule->command('sync:clinic')->everyMinute(); //每分钟同步当天门诊的诊疗记录
//        $schedule->command('remind:lnquiry')->monthlyOn(5,'12:00');
//        $schedule->command('complete:lnquiry')->dailyAt('11:30');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
