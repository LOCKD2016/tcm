<?php
$cache['master'] = array(
    'type' => 'Redis',
);
$cache['session'] = [
    'type' => 'FileCache',
    'cache_dir'=> ROOT_PATH . '/../storage/filecache/',
];
return $cache;