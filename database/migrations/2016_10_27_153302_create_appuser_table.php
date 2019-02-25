<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppuserTable extends Migration
{
    /**
     * APP用户表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //app用户表
        Schema::create('app_users', function (Blueprint $table) {
            $table->increments('id')->unsigned()->comment('用户id');
            $table->string('mobile', 15)->unique()->comment('手机号');
            $table->string('password', 100)->nullable()->comment('用户密码');
            $table->string('salt', 8)->nullable()->comment('密码加盐');
            $table->string('nickname', 100)->nullable()->comment('昵称');
            $table->string('realname', 100)->nullable()->comment('真实姓名');
            $table->string('headimgurl', 200)->nullable()->comment('头像地址');
            $table->date('birthday')->nullable()->comment('生日');
            $table->integer('sex')->default(0)->unsigned()->comment('性别 0未知 1男 2女');//不获取微信sex,填一次不让修改
            $table->integer('height')->default(0)->unsigned()->comment('身高');
            $table->integer('weight')->default(0)->unsigned()->comment('体重');
            $table->tinyInteger('idType')->nullable()->comment('证件类型; 0:身份证;1:军官证;2:护照;3:台胞证;4:其他;9:无证件');
            $table->string('idNo', 200)->nullable()->comment('证件号码');
            $table->string('country', 100)->default('中国')->comment('国家 默认值中国  保留字段');
            $table->string('province', 100)->nullable()->comment('省份');
            $table->string('city', 100)->nullable()->comment('城市');
            $table->string('area', 100)->nullable()->comment('区县');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('账号状态 0 正常 1 待定 保留字段');
            $table->tinyInteger('notice_status')->unsigned()->default(0)->comment('通知状态 0 通知 1 不通知');
            $table->string('remember_token')->nullable();
            $table->string('im_token', 128)->nullable()->comment('im_token');
            $table->timestamps();
            $table->softDeletes();

            $table->string('customer_code')->comment('客户代码 其他系统需要');
            $table->tinyInteger('source')->default(1)->comment('来源 1app注册 2同步');

            $table->commnet = 'app用户表';
        });

        //用户附加信息
        //Schema::create();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_users');
    }
}
