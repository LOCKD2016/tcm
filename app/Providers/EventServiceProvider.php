<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobExceptionOccurred;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Event;
use App\Listeners\JobExceptionListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'App\OAuth\Qq\QqExtendSocialite@handle',
            'App\OAuth\Weixin\WeixinExtendSocialite@handle',
            'App\OAuth\Weibo\WeiboExtendSocialite@handle',
        ],

        //JobFailed::class=>JobExceptionListener::class,
        //JobExceptionOccurred::class=>JobExceptionListener::class,

        'App\Events\SaveUser' => [//添加用户
            'App\Listeners\SaveUser',
        ],
        'App\Events\UserSync' => [//用户信息同步
            'App\Listeners\UserSync',
        ],
        'App\Events\OrderPayment' => [//订单支付
            'App\Listeners\OrderPay', //订单支付
            'App\Listeners\WebBespeak', //网诊预约的订单
            'App\Listeners\ClinicBespeak', //门诊预约的订单
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Event::listen(JobFailed::class, JobExceptionListene::class);
        Event::listen(JobExceptionOccurred::class, JobExceptionListener::class);
        //
    }
}
