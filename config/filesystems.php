<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => 'local',

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => 's3',

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => public_path('static'),
        ],

        'answerimg' => [
            'driver' => 'local',
            'root' => public_path('img/answer'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'visibility' => 'public',
        ],

        'audio' => [
            'driver' => 'local',
            'root' => storage_path('app/public/audio'),
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'your-region',
            'bucket' => 'your-bucket',
        ],

        'qiniu' => [
            'driver' => 'qiniu',
            'mime' => 'audio/amr',
            'domains' => [
                'default' => 'static.taiheguoyi.com', //你的七牛域名
                'https' => '',       //你的HTTPS域名
                'custom' => '',     //你的自定义域名
            ],
            'access_key' => 'lDtkP9__BBbXlzI2aZVe4pYtnbWMoXqaYME6hokd',  //AccessKey
            'secret_key' => 'm58tyZJbpiWoWcFwZbuDx7_N5VPsH5-rNqQs2y5l',  //SecretKey
            'bucket' => 'taiheguoyi',  //Bucket名字
            'notify_url' => '',  //持久化处理回调地址
            'pipeline' => 'tcm_audio' //转码队列名称
        ],

    ],

];
