<?php
$im_pwd=getenv('IM_PWD');
if($im_pwd=='/data/www/public/tcm/IMServer'){
    $db['master'] = array(
        'type'       => Swoole\Database::TYPE_MYSQLi,
        'host'       => "192.168.31.122",
        'port'       => 3306,
        'dbms'       => 'mysql',
        'engine'     => 'MyISAM',
        'user'       => "root",
        'passwd'     => "123456",
        'name'       => "tcm",
        'charset'    => "utf8",
        'setname'    => true,
        'persistent' => false, //MySQL长连接
    );
}else if($im_pwd=='/data/www/sina/tcm-pro/IMServer'){
    $db['master'] = array(
        'type'       => Swoole\Database::TYPE_MYSQLi,
        'host'       => "rm-2zeyuu5y5sls7g767.mysql.rds.aliyuncs.com",
        'port'       => 3306,
        'dbms'       => 'mysql',
        'engine'     => 'MyISAM',
        'user'       => "vmingdb",
        'passwd'     => "Ucas4pp)j",
        'name'       => "tcm-pro",
        'charset'    => "utf8",
        'setname'    => true,
        'persistent' => false, //MySQL长连接
    );
}else if($im_pwd=='/data/www/sina/tcm2/IMServer'){
    $db['master'] = array(
        'type'       => Swoole\Database::TYPE_MYSQLi,
        'host'       => "rm-2zeyuu5y5sls7g767.mysql.rds.aliyuncs.com",
        'port'       => 3306,
        'dbms'       => 'mysql',
        'engine'     => 'MyISAM',
        'user'       => "vmingdb",
        'passwd'     => "Ucas4pp)j",
        'name'       => "tcm2",
        'charset'    => "utf8",
        'setname'    => true,
        'persistent' => false, //MySQL长连接
    );
}else if($im_pwd == 'pro'){
    $db['master'] = array(
        'type'       => Swoole\Database::TYPE_MYSQLi,
        'host'       => "rm-2ze0cy58uoa36itm6.mysql.rds.aliyuncs.com",
        'port'       => 3306,
        'dbms'       => 'mysql',
        'engine'     => 'MyISAM',
        'user'       => "root",
        'passwd'     => "aliyuncsyiGUOhetai2018",
        'name'       => "tcm",
        'charset'    => "utf8mb4",
        'setname'    => true,
        'persistent' => false, //MySQL长连接
    );
}else {
    $db['master'] = array(
        'type'       => Swoole\Database::TYPE_MYSQLi,
        'host'       => "rm-2zeyuu5y5sls7g767.mysql.rds.aliyuncs.com",
        'port'       => 3306,
        'dbms'       => 'mysql',
        'engine'     => 'MyISAM',
        'user'       => "vmingdb",
        'passwd'     => "Ucas4pp)j",
        'name'       => "tcm",
        'charset'    => "utf8",
        'setname'    => true,
        'persistent' => false, //MySQL长连接
    );
}
return $db;