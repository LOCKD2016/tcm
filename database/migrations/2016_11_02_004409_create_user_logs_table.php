<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLogsTable extends Migration
{
    /**
     * 用户登录记录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger("user_id")->nullable()->commnet("用户id");
            $table->string("login_ip",15)->comment("登录ip");
            $table->string("system_type",15)->comment("系统类型");
            $table->string("system_version",15)->comment("系统版本");
            $table->string("device_id",15)->comment("设备id");
            $table->string("device_model",15)->comment("手机型号");
            $table->timestamps();
            $table->commnet = "用户登录记录表";
            $table->foreign('user_id')->references('id')->on('app_users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_logs');
    }
}
