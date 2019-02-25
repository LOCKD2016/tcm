<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\User::class, function ($faker) {
    $faker = Faker\Factory::create('zh_CN');//中文
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/**
 * @desc 生成诊所的测试数据
 */
$factory->define(\App\Models\Clinique::class, function ($faker) {
    $faker = Faker\Factory::create('zh_CN');//中文
    static $password;

    return [
        'name' => $faker->address,
        'content' => [
            'address' => $faker->address,
        ],
        'code' => 'GS_' . $faker->numberBetween(10, 99),
    ];
});


/**
 * @desc 生成APP用户的测试数据
 */
$factory->define(\App\Models\AppUser::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文
    static $password = '111111';

    $salt = str_random(6);

    return [
        'mobile' => $faker->phoneNumber,
        'nickname' => $faker->name,
        'realname' => $faker->name,
        'headimgurl' => $faker->imageUrl,
        'birthday' => $faker->DateTime,
        'sex' => $faker->boolean,
        'salt' => $salt,
        'password' => bcrypt($password . $salt),
        'im_token'=>str_random(25)
    ];
});

/**
 * @desc 生成医生的测试数据
 */
$factory->define(\App\Models\Doctor::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文
    static $password = '111111';

    $salt = str_random(6);

    return [
        'mobile' => $faker->phoneNumber,
        'name' => $faker->name,
        'head_img' => $faker->imageUrl,
        'head_img_L' => $faker->imageUrl(320, 240),
        'birthday' => $faker->DateTime,
        'title' => $faker->randomElement([1, 2, 3, 4, 5]),
        'desc' => $faker->text(),
        'sex' => $faker->boolean,
        'salt' => $salt,
        'web_amount' => 0, //网诊的费用
        'clinic' => $faker->boolean,//是否开通网诊 1:开通 0:关闭
        'web' => $faker->boolean,//是否门诊 1:开通 0:关闭
        'status' => $faker->randomElement([0, 1, 2]),//医生状态 1审核通过 0待审核 2审核不通过
        'password' => bcrypt($password . $salt),
        'im_token'=>str_random(25)
    ];
});

/**
 * @desc 轮播图
 */
$factory->define(\App\Models\Swiper::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文

    return [
        'title' => $faker->text(5),
        'desc' => $faker->text(50),
        'image' => $faker->imageUrl,
        'url' => $faker->url,
        'status' => $faker->boolean,
    ];
});

/**
 * @desc 疾病
 */
$factory->define(\App\Models\Disease::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文

    $section = \App\Models\Section::orderBy(\DB::raw('RAND()'))->first();

    return [
        'name' => $faker->text(5),
        'section_id' => $section->id,
        'sort' => $faker->numberBetween(0, 100),
    ];
});

/**
 * @desc 生成医生的分组数据
 */
$factory->define(\App\Models\Group::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文

    $doctor = \App\Models\Doctor::orderBy(\DB::raw('RAND()'))->first();

    return [
        'name' => $faker->text(5),
        'doctor_id' => $doctor->id,
        'num' => 0,
    ];
});

/**
 * @desc 生成预约数据
 */
$factory->define(\App\Models\Bespeak::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文

    //获取用户
    $user_ids = \App\Models\AppUser::pluck('id')->toArray();
    $user_id = $faker->randomElement($user_ids);


    //获取医生
    $doctor_ids = \App\Models\Doctor::pluck('id')->toArray();

    $type = $faker->boolean;

    if ($type) {//网聊
        $bespeak = [
            'disease' => $faker->name,
        ];

    } else {//门诊预约
        $bespeak = [
            'start_time' => $faker->dateTime,
            'end_time' => $faker->dateTime,
        ];
    }

    return array_merge([
        'user_id' => $user_id,
        'type' => $type,
        'doctor_id' => $faker->randomElement($doctor_ids),
        'first' => $faker->boolean, //初诊 复诊
    ], $bespeak);
});

/**
 * @desc 生成订单数据
 */
$factory->define(\App\Models\Orders::class, function () {
    $faker = Faker\Factory::create('zh_CN');//中文

    //获取用户
    $user_ids = \App\Models\AppUser::pluck('id')->toArray();
    $user_id = $faker->randomElement($user_ids);

    //订单状态
    $order_type = $faker->randomElement([1, 2, 3, 4]);

    //获取订单编号
    $order_sn = \App\Util\Tools::getOrderSn();

    $pay_status = $faker->randomElement([0, 1]);//用户是否支付 0:未付款 1:已付款

    $status = 0;

    $pay_type = 0;

    if ($pay_status == 1) {
        $status = $faker->randomElement([1, 2, 3]);//订单的状态 0 未支付 1已支付 2退款中 3已退款

        $pay_type = 1;//支付方式 0未付款 1微信
    }

    return [
        'user_id' => $user_id,
        'order_sn' => $order_sn,
        'order_type' => $order_type,
        'status' => $status,
        'pay_type' => $pay_type,
        'amount' => mt_rand(1, 200),
        'payable_amount' => mt_rand(1, 200),
    ];
});
