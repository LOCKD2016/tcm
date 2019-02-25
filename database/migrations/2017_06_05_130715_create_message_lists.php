<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageLists extends Migration
{
    /**
     * 会话分组 表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->comment('医生ID');
            $table->unsignedInteger('user_id')->comment('用户ID');
            $table->unsignedInteger('clinic_id')->comment('最后一次诊疗的编号ID');
            $table->string('title')->comment('标题');
            $table->string('last_message')->comment('最后消息，包括最后用户ID，用户名，摘要的序列化内容');
            $table->integer('user_new_num')->default(0)->comment('用户的新消息');
            $table->integer('doctor_new_num')->default(0)->comment('医生的新消息');
            $table->tinyInteger('status')->default(0)->comment('列表状态 0:不能聊天 1:能聊天');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');

            $table->comment = '@snowing 出于人道主义我补充一下表的注释：这个是聊天表，医生-患者诊疗聊天表，聊天详情表请移步message表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('message_lists');
    }
}
