<?php

namespace App\Listeners;

use App\Events\UserSync as UserSyncEvent;
use App\Services\SoapServices;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSync
{

    public $soapServices;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(SoapServices $soapServices)
    {
        $this->soapServices = $soapServices;
    }

    /**
     * Handle the event.
     *
     * @param  UserSync $event
     * @return void
     */
    public function handle(UserSyncEvent $event)
    {
        //同步到所有的诊所
        dump($event->user);
    }
}
