<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmscodeTable extends Migration
{
    /**
     * 短信验证码 记录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //短信验证码
        Schema::create('smscodes', function (Blueprint $table) {
            $table->increments("id")->comment("用户id");
            $table->string('uid',15)->default(0)->comment("用户id,注册的时候为0");
            $table->string('mobile',15)->comment("手机号");
            $table->string('code',10)->comment("验证码");
            $table->tinyInteger('type')->default(1)->comment("验证码类型 1注册 2 忘记密码找回密码 3登录 4 重置密码");
            $table->string('ip',128)->nullable()->comment("ip地址");
            $table->string('device_id',150)->nullable()->comment("设备id");
            $table->string('device_token',200)->nullable()->comment("设备token");
            $table->string('system_type',100)->nullable()->comment("系统类型");
            $table->string('useragent',300)->comment("浏览器头");
            $table->tinyInteger('status')->default(0)->comment("状态");
            $table->timestamps();
            $table->commnet = "短信验证码记录表";
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //删除表
        Schema::dropIfExists('smscodes');
    }
}
