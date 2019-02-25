<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', 'ViewController@index');
Route::get('/git', function(){
    $www_folder = "/data/www/sina/tcm-pro";
    return shell_exec("cd $www_folder && git pull 2>&1");
});
Route::get('/test', function(){
    $obj = new \App\Services\SoapServices();
    $obj->dictionaryInfo();
});
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::get('ceshi', 'TestController@test');
Route::get('push', 'TestController@push');
Route::get('yang', 'TestController@yang');
Route::get('jpush', 'ViewController@dotest');
Route::get('health/{type}', '\App\Http\WxControllers\HealthController@data');


Route::get('/inquiry', 'ViewController@inquiry')->name('二维码输入页面');
Route::get('/inquiry/detail', 'ViewController@getInquiryDetail')->name('问诊单详情页');
Route::post('/inquiry/upload', 'Api\UploadController@create')->name('文件上传');
Route::get('/inquiry/{code}', 'ViewController@retInquiry')->name('验证输出问诊单列表页');
Route::get('/inquiry/{code}/type/{type}', 'ViewController@inquiryDetail')->name('问诊单详情页');
Route::post('/inquiry', 'ViewController@polling')->name('二维码页面轮询接口');
Route::post('/save_exam', 'ViewController@save_exam')->name('');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/{vue_capture?}', 'ViewController@admin')->where('vue_capture', '[\/\w\.-]*');
});
// 获取用户微信信息
Route::group(['prefix' => "wechat", 'middleware' => 'wechat'], function () {
    Route::get('/server', 'ViewController@server');
    Route::get('/sign', 'ViewController@sign');
    Route::get('/exam', 'ViewController@wechat');
    Route::get('/payment/{id}', 'ViewController@wechat')->where(['id' => '[0-9]+']);
    Route::get('/{vue_capture?}', 'ViewController@wechat')->where('vue_capture', '[\/\w\.-]*');//->middleware('auth:wx_user');
});

//API 文档 @auth kingofzihua
Route::group(['prefix' => 'docs'], function () {
    Route::get('wechat', 'SwaggerController@wechat'); //微信端
});

Route::group(['prefix'=>'exports'],function ()use($router){
    $router->post('exports','ExportsController@exports')->name('导出管理');
});

//Route::get('/jiami', 'TryController@jiami')->name('给医生手动添加密码');