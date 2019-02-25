<?php

if(getenv('IM_PWD')=='/data/www/public/tcm/IMServer'){
    $redis['master'] = array(
        'host' => 'redis',
    );
}else if(getenv('IM_PWD')=='pro'){
    $redis['master'] = array(
        'host' => 'redis',
    );
}
else {
    $redis['master'] = array(
        'host' => '10.27.217.70',
    );
}
return $redis;