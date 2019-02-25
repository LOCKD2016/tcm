<?php
/**
 * App 端的路由
 * @auther kingofzihua
 * @path:App\Http\ApiControllers
 */
$router->post('/send/sms', '\App\Http\Controllers\SMSCodeController@send');//发送验证码
$router->post('app/upload/avatar', '\App\Http\Controllers\UploadController@avatar');//上传头像 适合小的
$router->post('app/upload/image', '\App\Http\Controllers\UploadController@image');//上传图片 适合大的
$router->post('app/upload/baseAvatar', '\App\Http\Controllers\UploadController@baseAvatar');//上传头像(base64) 适合小的
$router->post('app/upload/baseImage', '\App\Http\Controllers\UploadController@baseImage');//上传图片(base64) 适合大的
$router->post('app/upload/qiniu', '\App\Http\Controllers\UploadController@qiniu');//qiniu上传图片 适合大的


$router->group(['prefix' => 'app'], function ($router) {
    $router->post('send/sms', '\App\Http\Controllers\SMSCodeController@send');//发送验证码
    $router->post('login', 'AccessTokenController@issueToken')->name('login'); //登录
    $router->post('register', 'DoctorController@register')->name('register'); //注册
    $router->post('resetsection/listsPassword', 'DoctorController@resetPassword')->name('resetPassword'); //重置密码
    $router->post('forgetPassword', 'DoctorController@forgetPassword')->name('forgetPassword'); //忘记密码
    $router->get('agreement', 'ConfigController@agreement');//注册协议

    /**
     * 需要用户登录
     */
    $router->group(['middleware' => 'auths:api'], function ($router) {

        $router->post('upload/qiniuAuido', 'UploadController@qiniuAuido');//qiniu上传图片 适合大的

        /**
         * @desc 医生信息(登录用户 DOCTOR)
         * @auth kingofzihua
         */
        $router->get('doctor/detail', 'DoctorController@detail')->name('doctor.detail'); //医生详情
        $router->get('doctor/users', 'DoctorController@users')->name('doctor.users'); //医生的患者列表
        $router->get('doctor/restList', 'DoctorController@restList')->name('doctor.restList'); //医生的休息列表
        $router->get('doctor/remarkList', 'DoctorController@remarkList')->name('doctor.remarkList'); //医生的医嘱列表
        $router->post('doctor/edit', 'DoctorController@edit')->name('doctor.edit'); //医生数据修改
        $router->post('doctor/rest', 'DoctorController@rest')->name('doctor.rest'); //医生申请休息
        $router->post('doctor/saveRemark', 'DoctorController@saveRemark')->name('doctor.saveRemark'); //医生添加医嘱
        $router->put('doctor/toggleClinic/{status}', 'DoctorController@toggleClinic')->name('doctor.toggleClinic'); //医生切换诊疗状态  status: 0(关闭) || 1(开启)

        $router->get('doctor/datastatistics', 'DoctorController@dataStatistics')->name('doctor.dataStatistics'); ///医生疗效统计
        $router->get('doctor/comment', 'DoctorController@comment')->name('doctor.comment'); //医生评论
        $router->get('doctor/schedules', 'DoctorController@schedules')->name('doctor.schedules');//医生排班


        /**
         * @desc 患者信息(APP_USER)
         * @auth kingofzihua
         */
        $router->get('user/{user_id}', 'UserController@detail')->name('user.detail'); //用户数据 user_id:用户编号

        /**
         * @desc 预约信息
         * @auth kingofzihua
         */
        $router->get('bespeak/webList', 'BespeakController@webList')->name('bespeak.webList'); //网诊预约列表
        $router->get('bespeak/clinicList/{date}', 'BespeakController@clinicList')->name('bespeak.clinicList')->where('date', '^[1-2][0-9][0-9][0-9]-[0-1]{0,1}[0-9]-[0-3]{0,1}[0-9]$'); //门诊预约列表 date 日期
        $router->get('bespeak/detail/{bespeak_id}', 'BespeakController@detail')->name('bespeak.detail'); //预约详情
        $router->get('bespeak/accept/{bespeak_id}', 'BespeakController@accept')->name('bespeak.accept'); //接诊
        $router->get('bespeak/refuse/{bespeak_id}', 'BespeakController@refuse')->name('bespeak.refuse'); //拒绝接诊

        /**
         * @desc 诊疗信息
         * @auth kingofzihua
         */
        //?include=user,bespeak, inquiry, exam, comments
        // (user 用户信息,bespeak 预约信息, inquiry 普通问诊单(标准问诊单), exam个性化问诊单, comments评论 (反馈)) Prescription处方



        $router->get('clinic/detail/{clinic_id}', 'ClinicController@detail')->name('clinic.detail'); //诊疗详情

        $router->get('clinic/lists/{user_id}', 'ClinicController@lists')->name('clinic.lists'); //用户诊疗数据(全部的) user_id:用户编号
        $router->get('clinic/aboutMe/{user_id}', 'ClinicController@aboutMe')->name('clinic.aboutMe'); //用户的诊疗数据(登录医生的) user_id:用户编号

        /**
         * @desc 个性化问诊单
         * @auth kingofzihua
         */
        $router->get('exam/lists', 'ExamController@lists')->name('exam.lists'); //问诊单列表
        $router->get('exam/system', 'ExamController@system')->name('exam.system'); //系统问诊单
        $router->get('exam/type/{type?}', 'ExamController@type')->name('exam.type'); //问诊单列表
        $router->get('exam/detail/{exam_id}', 'ExamController@detail')->name('exam.detail'); //问诊单详情
        $router->post('exam/save', 'ExamController@save')->name('exam.save'); //创建问诊单
        $router->post('exam/edit/{exam_id}', 'ExamController@edit')->name('exam.edit'); //修改问诊单 exam_id:问诊单的编号

        /**
         * @desc 普通问诊单
         * @auth kingofzihua
         */
        $router->get('inquiry/detail/{inquiry_id}', 'InquiryController@detail')->name('inquiry.detail'); //问诊单详情

        /**
         * @desc 聊天信息
         * @auth kingofzihua
         */
        $router->get('message/lists', 'MessagesController@lists')->name('message.lists'); //聊天列表
        $router->get('message/statusBar/{lists_id}', 'MessagesController@statusBar')->name('message.statusBar'); //获取聊天页面需要的状态 信息 lists_id 列表编号
        $router->get('message/lists/detail/{list_id}', 'MessagesController@detail')->name('message.lists.detail'); //聊天列表详情 lists_id 列表编号
        $router->put('message/read/{lists_id}', 'MessagesController@read')->name('message.read'); //消息已读 lists_id 列表编号
        $router->put('message/closeClinic/{lists_id}', 'MessagesController@closeClinic')->name('message.closeClinic'); //通过聊天列表来关闭诊疗 lists_id 列表编号

        /**
         * @desc 分组信息
         * @auth kingofzihua
         */
        $router->get('group/lists', 'GroupController@lists')->name('group.lists'); //医生的分组
        $router->get('group/userLists/{group_id}', 'GroupController@userLists')->name('group.userLists'); //分组内的用户
        $router->get('group/user/{user_id}', 'GroupController@users')->name('group.users'); //用户的分组 user_id:用户编号
        $router->post('group/save', 'GroupController@save')->name('group.save'); //添加分组
        $router->post('group/addUser/{group_id}', 'GroupController@addUser')->name('group.addUser'); //分组添加成员
        $router->post('group/removeUser/{group_id}', 'GroupController@removeUser')->name('group.removeUser'); //分组删除成员
        $router->post('group/syncUser/{group_id}', 'GroupController@syncUser')->name('group.syncUser'); //分组同步成员
        $router->post('group/syncUserGroup/{user_id}', 'GroupController@syncUserGroup')->name('group.syncUserGroup'); //同步成员分组


        /**
         * @desc 健康数据
         * @auth kingofzihua
         */
        $router->get('health/lists/{user_id}/{type}', 'HealthController@lists')->name('health.lists'); //获取健康数据
        $router->get('health/data/{user_id}/{type}', 'HealthController@data')->name('health.data'); //获取今天一周一月健康数据
        $router->get('health/last/{user_id}/{type}', 'HealthController@last')->name('health.last'); //最近一条数据

        /**
         * @desc 科室列表
         * @auth kingofzihua
         */
        $router->get('section/lists', 'SectionController@lists')->name('section.lists'); //所有的科室

        //医生的处方
        $router->group(['prefix' => 'recipe'], function ($router) {
            $router->get('lists', 'RecipeController@lists')->name('recipe.lists'); //处方列表(type 0:系统处方 1:医生处方,title)
            $router->post('add', 'RecipeController@add')->name('recipe.add'); //添加处方
            $router->post('edit', 'RecipeController@edit')->name('recipe.edit'); //修改处方
            $router->get('details/{id}', 'RecipeController@details')->name('recipe.details'); //处方详情
            $router->delete('delete/{id}', 'RecipeController@del')->name('recipe.delete'); //删除处方
            // $router->post('medicine_all', 'RecipeController@medicine_all')->name('recipe.medicine'); //获取所有处方


        });


        //医生开处方
        $router->group(['prefix' => 'prescription'], function ($router) {
            $router->post('add', 'PrescriptionController@add')->name('prescription.add'); //开处方
            $router->post('edit/{id}', 'PrescriptionController@edit')->name('prescription.edit'); //修改处方
            $router->get('details/{id}', 'PrescriptionController@details')->name('prescription.details'); //详情


            $router->get('getallmedicine', 'PrescriptionController@get_all_medicine')->name('prescription.get_all_medicine'); //获取所有的药材1


            $router->get('searchdisease', 'PrescriptionController@search_disease')->name('prescription.get_all_medicine'); //获取所有的疾病名




        });

        //$router->get('test', 'PrescriptionController@test'); //测试





    });


});
