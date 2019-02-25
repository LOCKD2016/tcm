<?php
$config['server'] = array(
    //监听的HOST
    'host'   => '0.0.0.0',
    //监听的端口
    'port'   => '9503',
    //WebSocket的URL地址，供浏览器使用的
    'url'    => 'ws://localhost:9503/websocket?type=user&token=123456',
    //用于Comet跨域，必须设置为html所在的URL
    'origin' => 'http://localhost:8080',
);

$config['swoole'] = array(
    'log_file'        => ROOT_PATH . '/../storage/logs/swoole.log',
    'worker_num'      => 1,
    //不要修改这里
    'max_request'     => 0,
    'task_worker_num' => 1,
    //是否要作为守护进程
    'daemonize'       => 0,

    'heartbeat_idle_time'       => 600,
    'heartbeat_check_interval'       => 60,
);

$config['webim'] = array(
    //聊天记录存储的目录
    //'log_file' => ROOT_PATH . '/../storage/logs/webim.log',
    'send_interval_limit' => 1, //只允许1秒发送一次
);

$config['storage'] = array(
    'history_num' => 100,
);

return $config;