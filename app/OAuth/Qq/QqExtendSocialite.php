<?php

namespace App\OAuth\Qq;

use SocialiteProviders\Manager\SocialiteWasCalled;

class QqExtendSocialite
{
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('qq', __NAMESPACE__.'\Provider');
    }
}
