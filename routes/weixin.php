<?php

$router->post('weixin/send/sms', '\App\Http\Controllers\SMSCodeController@send');//发送验证码
$router->post('weixin/upload/avatar', '\App\Http\Controllers\UploadController@avatar');//上传头像 适合小的
$router->post('weixin/upload/image', '\App\Http\Controllers\UploadController@image');//上传图片 适合大的
$router->post('weixin/upload/qiniu', '\App\Http\Controllers\UploadController@qiniu');//qiniu上传图片 适合大的
$router->post('weixin/upload/baseAvatar', '\App\Http\Controllers\UploadController@baseAvatar');//上传头像(base64) 适合小的
$router->post('weixin/upload/baseImage', '\App\Http\Controllers\UploadController@baseImage');//上传图片(base64) 适合大的

$router->group(['prefix' => 'weixin'], function () use ($router) {

    $router->get('nav', 'ConfigController@nav');//导航

    /**
     * @desc
     */
    $router->get('check', 'UsersController@check');//登录 @auth kingofzihua
    $router->post('login', 'UsersController@login');//登录 @auth kingofzihua
    $router->get('wxlogin', 'UsersController@wxLogin');//微信登录
    $router->get('logout', 'UsersController@logout');//退出
    $router->post('register', 'UsersController@register');//注册 @auth kingofzihua
    $router->get('wxconfig', 'WechatController@wxconfig');//发送验证码
    $router->get('agreement', 'ConfigController@agreement');//注册协议
    $router->post('wxupload', 'UsersController@wxupload')->name('wx文件上传');

    /**
     * @desc 医生
     * @auth kingofzihua
     */
    $router->get('doctor/lists', 'DoctorController@lists')->name('doctor.lists'); //医生列表
    $router->get('doctor/search', 'DoctorController@search')->name('doctor.search'); //全局搜索医生
//        $router->get('doctor/recommend', 'DoctorController@recommend')->name('doctor.recommend'); //推荐医生
    $router->get('doctor/recommend', function () {
        return null;
    })->name('doctor.recommend'); //推荐医生

    $router->get('doctor/title', 'DoctorController@title')->name('doctor.title'); //医生职称
    $router->get('doctor/detail/{doctor_id}', 'DoctorController@detail')->name('doctor.detail'); //医生详情 doctor_id:医生编号

    /**
     * @desc 医生排班
     * @auth kingofzihua
     */
    $router->get('schedule/lists/{doctor_id}/{clinique_id}', 'ScheduleController@lists')->name('schedule.lists'); //医生排班列表 doctor_id:医生编号 clinique_id:诊所编号
    $router->get('schedule/detail/{doctor_id}/{clinique_id}/{date}', 'ScheduleController@detail')->name('schedule.detail'); //医生排班详情 doctor_id:医生编号 clinique_id:诊所编号 date:日期

    /**
     * @desc 科室
     * @auth kingofzihua
     */
    $router->get('section/lists', 'SectionController@lists')->name('section.lists'); //获取科室的列表

    /**
     * @desc 诊所
     * @auth kingofzihua
     */
    $router->get('clinique/lists', 'CliniqueController@lists')->name('section.lists'); //获取诊所
    /**
     * @desc 轮播图
     * @auth kingofzihua
     */
    $router->get('swiper/lists', 'SwiperController@lists')->name('swiper.lists'); //获取轮播图列表
    /**
     * @desc 获取商品信息
     * @auth zhoupeng
     */
    $router->get('goods/{name}', 'ConfigController@goods')->name('goods.info');

    $router->group(['middleware' => ['auth:wx_user']], function () use ($router) {

        /**
         * @desc 用户
         * @auth kingofzihua
         */
        $router->get('user/detail/{user_id?}', 'UsersController@detail')->name('user.detail');//用户信息 user_id:用户编号 默认为登录用户
        $router->get('user/complete', 'UsersController@complete')->name('user.complete');//判断用户信息是否完善
        $router->get('user/doctor', 'UsersController@doctor')->name('user.doctor');//用户的医生
        $router->get('user/getLastClinicDoctor', 'UsersController@getLastClinicDoctor')->name('user.getLastClinicDoctor');//获取用户最后一次诊疗的医生
        $router->post('user/edit', 'UsersController@edit')->name('user.edit'); //修改用户信息

        /**
         * @desc 预约
         * @auth kingofzihua
         */
        $router->get('bespeak/can/{doctor_id}', 'BespeakController@canWeb')->name('bespeak.canWeb'); //判断网诊是否可预约
        $router->get('bespeak/lists', 'BespeakController@lists')->name('bespeak.lists'); //预约列表
        $router->get('bespeak/detail/{bespeak_id}', 'BespeakController@detail')->name('bespeak.detail'); //预约详情
        $router->get('bespeak/close/{bespeak_id}', 'BespeakController@close')->name('bespeak.close'); //取消预约 bespeak_id:预约编号
        $router->post('bespeak/web', 'BespeakController@webBespeak')->name('bespeak.webBespeak'); //网诊预约
        $router->post('bespeak/clinic', 'BespeakController@clinicBespeak')->name('bespeak.clinicBespeak'); //门诊预约
        $router->post('bespeak/{id}', 'BespeakController@')->name('bespeak.status');//获得某个预约，主要是要这个预约的状态

        /**
         * @desc 评价
         * @auth kingofzihua
         */
        $router->post('comment/save/{clinic_id}', 'CommentController@save')->name('bespeak.save'); //诊疗评价 clinic_id 诊疗编号

        /**
         * @desc 订单
         * @auth kingofzihua
         */
        $router->get('order/lists', 'OrdersController@lists')->name('order.lists'); //订单列表
        $router->get('order/detail/{order_id}', 'OrdersController@detail')->name('order.detail'); //订单详情 order_id:订单编号
        $router->get('order/bespeak/{bespeak_id}', 'OrdersController@bespeak')->name('order.bespeak');//获取预约的订单 用于支付bespeak_id:预约

        /**
         * @desc 支付
         * @auth kingofzihua
         */
        $router->get('pay/wechat/{order_id}', 'PayController@wechat')->where(['order_id' => '[0-9]+'])->name('pay.wechat');

        /**
         * @desc 消息
         * @auth kingofzihua
         */
        $router->get('message/lists', 'MessagesController@lists')->name('message.lists'); //获取消息的列表
        $router->get('message/statusBar/{lists_id}', 'MessagesController@statusBar')->name('message.statusBar'); //获取聊天页面需要的状态 信息 lists_id 列表编号
        $router->get('message/getMessageListData/{type}/{id}', 'MessagesController@getMessageListData')->where(['id' => '[0-9]+'])->name('message.getMessageListData'); //获取消息的列表信息 lists_id 列表编号
        $router->put('message/read/{lists_id}', 'MessagesController@read')->name('message.read'); //消息已读 lists_id 列表编号
        $router->put('message/closeClinic/{lists_id}', 'MessagesController@closeClinic')->name('message.closeClinic'); //通过聊天列表来关闭诊疗 lists_id 列表编号
        $router->put('message/ask/{lists_id}', 'MessagesController@ask')->name('message.ask'); //用户追问 lists_id 列表编号
        $router->post('message/endAsk', 'MessagesController@endAsk')->name('message.endAsk');

        /**
         * @desc 试题 || 个性化问诊单
         * @auth kingofzihua
         */
        $router->get('exam/detail/{exam_id}', 'ExamController@detail')->name('exam.detail'); //获取试题详情 exam_id:试题编号
        $router->post('exam/answer', 'ExamController@answer')->name('exam.answer'); //回答试题

        /**
         * @desc 标准问诊单
         * @author zhoupeng
         */
        $router->get('inquiry/detail/{inquiry_id}', 'InquiryController@detail')->name('inquiry.detail'); //获取标准问诊单详情 inquiry_id:标准问诊单编号

        /**
         * @desc 诊疗信息
         * @author zhoupeng
         */
        $router->get('clinic/lists', 'ClinicController@getUncommentedLists')->name('clinic.lists'); // 获取未评论的诊疗列表信息
        $router->get('clinic/detail/{clinic_id}', 'ClinicController@detail')->name('clinic.detail'); // 获取诊疗详情

        /**
         * @desc 药方
         * @auth kingofzihua
         */
        $router->get('prescription/detail/{prescription_id}', 'PrescriptionController@detail')->name('prescription.detail'); //通过药方编号获取详情  prescription_id:药方编号
        $router->get('prescription/show/{order_id}', 'PrescriptionController@show')->name('prescription.detail'); //获通过订单编号获取详情 order_id：订单编号
        $router->get('prescription/order/{prescription_id}', 'OrdersController@prescription')->name('prescription.order'); // 药方生成订单



        /**
         * @desc 健康数据
         * @auth kingofzihua
         */
        $router->get('health/lists/{type}', 'HealthController@lists')->name('health.lists'); //获取健康数据
        $router->get('health/data/{type}', 'HealthController@data')->name('health.data'); //获取今天,今天前一周,今天前一月的健康数据
        $router->get('health/condition/{type}', 'HealthController@condition')->name('health.condition'); //获取今天,今天前一周,今天前一月的健康数据
        $router->get('health/last/{type}', 'HealthController@last')->name('health.last'); //最近一条数据
        $router->post('health/save', 'HealthController@save')->name('health.last'); //添加数据


        /**
         * @desc 收货地址
         * @auth kingofzihua
         */
        $router->get('address/lists', 'AddressController@lists')->name('address.lists'); //获取地址列表
        $router->get('address/detail/{address_id}', 'AddressController@detail')->name('address.detail'); //获取地址详情
        $router->put('address/setDefault/{address_id}', 'AddressController@setDefault')->name('address.setDefault'); //地址设置为默认
        $router->put('address/edit/{address_id}', 'AddressController@edit')->name('address.edit'); //修改地址
        $router->post('address/save', 'AddressController@save')->name('address.save'); //添加地址
        $router->delete('address/delete/{address_id}', 'AddressController@delete')->name('address.delete'); //删除地址

        /**
         * @desc 获取快递信息
         * @author zhoupeng
         */
        $router->get('exress/{order_id}', 'OrdersController@express')->name('express.info');

    });
});

// 不需获取用户微信信息
Route::group(['prefix' => 'payment', 'namespace' => 'App\Http\WxControllers'], function () {
    Route::post('/notfiy/{type}', 'PayController@notify');
});
Route::group(['prefix' => 'upload', 'namespace' => 'App\Http\WxControllers'], function () {
    Route::post('/wechat', 'UsersController@uploadadd');
});

