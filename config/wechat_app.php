<?php

return [
    /*
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,

    /*
     * 使用 Laravel 的缓存系统
     */
    'use_laravel_cache' => true,

    /*
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id'  => 'wx3d50c5e521b75718',         // AppID
    'secret'  => '3986bcaf206c94bb19eefab2401c0961',     // AppSecret
    'token'   => env('WECHAT_TOKEN', 'your-token'),          // Token
    'aes_key' => env('WECHAT_AES_KEY', ''),                    // EncodingAESKey

    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('WECHAT_LOG_LEVEL', 'debug'),
        'file'  => env('WECHAT_LOG_FILE', storage_path('logs/wechat.log')),
    ],

    /*
     * 微信支付
     */
     'payment' => [
         'merchant_id'        => env('APP_PAYMENT_MERCHANT_ID', '1508630871'),
         'key'                => env('APP_PAYMENT_KEY', 'API201806281139taiheguoyiTCM3232'),
         'cert_path'          => env('APP_PAYMENT_CERT_PATH', 'path/to/your/cert.pem'), // XXX: 绝对路径！！！！
         'key_path'           => env('APP_PAYMENT_KEY_PATH', 'path/to/your/key'),      // XXX: 绝对路径！！！！
         'notify_url'         => env('APP_URL').'/payment/notfiy/wx.app'
         // 'device_info'     => env('WECHAT_PAYMENT_DEVICE_INFO', ''),
         // 'sub_app_id'      => env('WECHAT_PAYMENT_SUB_APP_ID', ''),
         // 'sub_merchant_id' => env('WECHAT_PAYMENT_SUB_MERCHANT_ID', ''),
         // ...
     ],
];
