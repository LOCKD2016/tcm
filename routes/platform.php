<?php
/**
 * 对接平台的接口
 * @Auth: kingofzihua
 * @param $router
 */
$router->get('title', 'DoctorController@title'); //医生列表

$router->group(['prefix' => 'platform', 'middleware' => 'platform'], function ($router) {

    /**
     * 医生
     * @Auth: kingofzihua
     */
    $router->group(['prefix' => 'doctor'], function ($router) {
        $router->get('index', 'DoctorController@index'); //医生列表
        $router->post('save', 'DoctorController@save')->name('doctorSave'); //新增医生
        $router->get('destroy', 'DoctorController@destroy'); //医生删除
        $router->post('edit', 'DoctorController@edit'); //医生编辑
    });

    /**
     * 患者
     * @Auth: Nnn
     */
    $router->group(['prefix' => 'user'], function ($router) {
        $router->get('index', 'UserController@index'); //患者信息（包含患者体征信息）
        $router->get('detail/{id}', 'UserController@detail'); //患者信息（包含患者体征信息）
        $router->post('save', 'UserController@save'); //患者新增
        $router->post('edit', 'UserController@edit'); //患者修改
        $router->get('destroy', 'UserController@destroy'); //患者删除
    });

    /**
     * 药方
     * @Auth:Nnn
     */
    $router->group(['prefix' => 'prescription'], function ($router) {
        $router->get('index', 'PrescriptionController@index'); //同步门诊药方
    });

    /**
     * 项目信息
     * @Auth:Nnn
     */
    $router->group(['prefix' => 'project'], function ($router) {
        $router->post('save', 'MedicineController@save'); //新增项目（药材，套餐等）
        $router->post('edit', 'MedicineController@edit'); //项目信息修改
        $router->get('destroy', 'MedicineController@destroy'); //项目信息删除
    });

    /**
     * 医生排班信息
     * @Auth:Nnn
     */
    $router->group(['prefix' => 'schedules'], function ($router) {
        $router->post('save', 'SchedulesController@save'); //新增医生排班
        $router->post('destroy', 'SchedulesController@destroy'); //删除医生排班
    });

});