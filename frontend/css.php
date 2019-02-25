<?php
/**
 * Created by PhpStorm.
 * User: lbbniu
 * Date: 2018/3/19
 * Time: 11:01
 */
$filePath = '../../../apicloud/tcm/html/static/css/app.css';
$css = file_get_contents($filePath);
$css = str_replace('/static','..',$css);
file_put_contents($filePath,$css);

$dir = opendir('../../../apicloud/tcm/html/static/js');
while ($file = readdir($dir)){
    if($file != '.' && $file != '..'){
        var_dump($file);
        $filePath = '../../../apicloud/tcm/html/static/js/'.$file;
        $css = file_get_contents($filePath);
        $css = str_replace('/static','static',$css);
        file_put_contents($filePath,$css);
    }
}


var_dump("static 替换 .. 成功");