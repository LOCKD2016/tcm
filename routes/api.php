<?php

//use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->post('upload/img', "UploadController@create")->name("图片上传 七牛");
$router->post('upload/qiniu', "\\App\\Http\\Controllers\\UploadController@qiniu")->name("图片上传 七牛");

$router->group(['middleware'=>['auth','daishu']],function() use($router){
    /**
     * App用户模块路由
     */
    $router->group(['prefix' => 'appuser'],function () use($router) {
        $router->get('list', 'AppUserController@index')->name('用户列表');
        $router->post('edit', 'AppUserController@edit')->name('修改用户信息');
        $router->get('detail/{id}', 'AppUserController@detail')->where(['id' => '[0-9]+'])->name('获取APP用户详情');
        $router->get('clinic/{user_id}/{family_id}', 'AppUserController@clinic')->name('获取APP用户诊疗记录');
    });

    /**
     * 医生管理
     */
    $router->group(['prefix'=>'doctor'],function ()use($router){
        $router->get('list','DoctorController@index')->name('医师列表');
        $router->get('show/{id}','DoctorController@show')->name('医师详情');
        $router->put('update/{id}','DoctorController@update')->name('医师修改');

        $router->put('addisease/{id}','DoctorController@adDisease')->name('原医师擅长疾病/科室修改');
        $router->put('addisease2/{id}','DoctorController@adDisease2')->name('新医师擅长疾病/科室修改');
        $router->put('deldisease/{doctor_id}/{disease_id}','DoctorController@delDisease')->name('医生擅长删除');
        $router->put('leave/{id}','DoctorController@doctorLeave')->name('医生休息状态审核');
//        $router->post('delete/', 'DoctorController@deleteDoctor')->name('删除医生');
    });

    /**
     * 预约管理
     */
    $router->group(['prefix'=>'bespeaks'],function ()use($router){
        $router->get('index','BespeaksController@index')->name('预约列表');
        $router->get('show/{id}','BespeaksController@show')->name('预约详情');
        $router->put('update/{id}','BespeaksController@update')->name('修改预约');
    });

    /**
     * 财务管理模块路由
     */
    $router->group(['prefix' => 'order'],function () use($router) {
        $router->get('bespeak', 'OrderController@beaspeakOrderList')->name('预约订单列表');
        $router->get('prescription', 'OrderController@prescriptionOrderList')->name('药方订单列表');
        $router->get('presendlist', 'OrderController@preSendList')->name('药方发货列表');
        $router->put('update/{id}', 'OrderController@update')->name('药方发货添加物流');

        //$router->get('refund', 'OrderController@refund')->name('申请退款');
    });

    /**
     * 数据管理
     */
    $router->group(['prefix' => 'count'],function () use($router) {
        $router->get('user','AppUserController@userCount')->name('患者统计');
        $router->get('doctor','DoctorController@doctorCount')->name('医师统计');
        $router->get('comment','DoctorController@doctorComment')->name('疗效统计');
        $router->get('income','DoctorController@doctorIncome')->name('医生收入统计');

        $router->get('deal','OrderController@deal')->name('经营统计总体收入');
        $router->get('manage','CountController@manage')->name('经营统计');

    });

    /**
     * 轮播图
     */
    $router->group(['prefix' => 'slider'],function () use($router) {
        $router->get('index','SwiperController@index')->name('轮播图列表');
        $router->post('add','SwiperController@sliderAdd')->name('轮播图添加');
        $router->put('update','SwiperController@sliderUpdate')->name('轮播图修改');
        $router->delete('del/{id}','SwiperController@sliderDelete')->name('轮播图删除');
    });

    /**
     * 门店
     */
    $router->group(['prefix' => 'clinique'],function () use($router) {
        $router->get('index', 'CliniqueController@index')->name('门店列表');
        $router->post('update', 'CliniqueController@update')->name('门店修改');
    });

    /**
     * 科室管理
     */
    $router->group(['prefix' => 'section'],function () use($router) {
        $router->get('index', 'SectionController@index')->name('获取科室列表');
        $router->post('add', 'SectionController@sectionAdd')->name('添加科室');
        $router->put('update', 'SectionController@sectionUpdate')->name('修改科室');
        $router->delete('del/{id}', 'SectionController@sectionDel')->name('删除科室');
    });

    /**
     * 疾病管理
     */
    $router->group(['prefix' => 'disease'],function () use($router) {
        $router->get('index', 'DiseaseController@index')->name('疾病列表');
        $router->post('create', 'DiseaseController@diseaseCreate')->name('添加疾病');
        $router->get('disease/{id}', 'DiseaseController@disease')->name('获取科室对应的疾病');
        $router->delete('diseasedel/{id}', 'DiseaseController@diseaseDel')->name('删除科室对应疾病');

    });

    /**
     *药方管理
     */
    $router->group(['prefix' => 'prescription'],function () use($router) {
        $router->get('pricelist','PrescriptionController@priceList')->name('医生划价列表');
        $router->put('setprice/{id}','PrescriptionController@setPrice')->name('医生划价');
    });

    /**
     * 诊疗管理
     */
    $router->group(['prefix'=>'clinic'],function ()use($router){
        $router->get('list','ClinicController@index')->name('诊疗列表');
        $router->get('show/{id}','ClinicController@show')->name('诊疗详情');
        $router->put('update/{id}','ClinicController@update')->name('操作诊疗');

    });

    /**
     * 评价管理
     */
    $router->group(['prefix'=>'comment'],function ()use($router){
        $router->get('comment','CommentController@index')->name('评论列表');
        $router->put('save/{id}','CommentController@save')->name('评论审核');
    });

    /**
     * 药品管理
     */
    $router->group(['prefix'=>'medicine'],function ()use($router){
        $router->get('index','MedicineController@index')->name('药品列表');
        $router->post('save','MedicineController@save')->name('药品修改');
        $router->delete('del/{id}','MedicineController@del')->name('药品删除');
    });

    /**
     * 配置内容
     */
    $router->group(['prefix'=>'configs'],function ()use($router){
        $router->get('agreement','ConfigsController@agreementIndex')->name('用户协议列表');
        $router->post('agreementedit','ConfigsController@agreementEdit')->name('用户协议修改');
    });

    /**
     * 导出管理
     */
    $router->group(['prefix'=>'exports'],function ()use($router){
        $router->post('exports','ExportsController@exports')->name('导出管理');
    });

    /**
     *系统问诊单
     */
    $router->get('exam','SystemController@exam')->name('系统问诊单');
    $router->get('exam/{id}','SystemController@exam_show')->name('系统问诊单详情');
    $router->post('exam','SystemController@exam_store')->name('系统问诊单添加');
    $router->delete('exam/{id}','SystemController@exam_delete')->name('系统问诊单删除');
    $router->put('exam','SystemController@exam_save')->name('系统问诊单修改');

    //文件上传路由
    $router->group(['prefix' => 'upload'],function () use($router){
        $router->post('add','UploadController@create')->name('文件上传');//专家头像
        $router->get('download/{id}', "UploadController@downLoadFile")->name("文件下载");
    });

    //聊天信息管理
    $router->group(['prefix' => 'message'], function () use($router){
        $router->get('getMessage', 'MessagesController@getMessagesList')->name('获取医生和用户的聊天消息列表');
        $router->get('getMessageDetail/{id}', 'MessagesController@getMessagesDetail')->name('根据list_id获取医生和用户的具体聊天内容');
    });

    //后台用户
    $router->group(['prefix' => 'user'],function () use($router){
        $router->get('index','UserController@index')->name('获取系统用户列表');
        $router->get('group','UserController@group')->name('获取用户组列表');
        $router->post('adduser','UserController@store')->name('添加系统用户');
        $router->post('resetpwd/{pid}','UserController@updatePwd')->name('修改自己的密码');
        $router->delete('/{id}','UserController@delete')->name('删除用户');
        $router->get('/{id}','UserController@show')->name('获取指定用户');
        $router->put('/{id}','UserController@update')->name('修改用户信息');
        $router->get('pwd/{id}','UserController@resetPwd')->name('重置密码');
        $router->get('forbidden/{id}','UserController@forbidden')->name('冻结用户');
        $router->get('role/{id}','UserController@role')->name("获取组ID");//获取自己所属操作组ID
        $router->put('saverole/{id}','UserController@saverole')->name("修改组ID");//修改自己所属操作组ID
    });

    $router->get('getJson','AuthController@getJson');//获取所有权限json展示
    $router->resource('auth','AuthController',['except' =>
        ['create','edit']
    ]);
    //权限管理组管理
    $router->resource('role','RoleController',['except' =>
        ['create','edit']
    ]);

    $router->group(['prefix' => 'tel'],function () use($router){
        $router->get('getTelephone','TelController@index')->name('客服手机列表');
        $router->post('addTelephone','TelController@addtelephone')->name('添加客服手机号');
        $router->delete('delTelephone/{id}','TelController@delTelephone')->name('删除客服手机号');
        $router->get('updatestatus/{id}','TelController@updatestatus')->name('修改客服状态');
        $router->post('updateTelephone/{id}','TelController@updateTelephone')->name('修改客服手机号');
    });


});

/**
* 不需要权限控制的接口
*/
//系统登录日志
$router->resource('logs','LogsController',['except' =>
    ['create','edit']
]);
//操作日志
$router->group(['prefix'=>'operation'],function() use($router){
    $router->get('list','OperationLogController@index')->name('操作日志列表');
    $router->get('read/{id}','OperationLogController@read')->name('标记操作日志已读');
    $router->get('count','OperationLogController@count')->name('获取操作日志未读数量');
    $router->delete('del/{id}','OperationLogController@destroy')->name('操作日志删除');
    $router->post('indexSearch/','OperationLogController@doSearch')->name('主页搜索');

});

$router->get('getLogs','LogsController@getLogs')->name('获取自己登录日志');
$router->get('express/see/{order_id}','OrderController@getexpress')->name('获取快递进度');
$router->get('express/company','OrderController@getexpresscompany')->name('获取快递公司列表');
