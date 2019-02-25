<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'qq' => [
        'client_id' => '101363394',
        'client_secret' => '7de196551a95fa61456a8d39d47c7616',
        'redirect' => 'http://daishu.vliang.com/auth/qq'
    ],
    'weixin' => [
        'client_id' => 'wx960a23fd562f3305',
        'client_secret' => '54ca021b719267a25be149c9d8ca5d88',
        'redirect' => 'http://daishu.vliang.com/auth/weixin'
    ],
    'weibo' => [
        'client_id' => '944737071',
        'client_secret' => '7632267ab3157bec8edefef7d05e03d1',
        'redirect' => 'http://daishu.vliang.com/auth/weibo'
    ],
    "ajpush"=>[
        'appKey'=>env('PUSHER_KEY','81017a0a86c426fe31c6a5ea'),
        'secret'=>env('PUSHER_SECRET','c68749b78bdb90b362ae3e9b'),
    ],
    "yunzhongyi" => [
        'appId'=>env('YZY_APPID'),
        'appCode'=>env('YZY_APPCODE'),
        'inquiry'=>env('YZY_TEST',true),
    ]
];
