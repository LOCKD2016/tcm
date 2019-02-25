<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageTable extends Migration
{
    /**
     * 消息表 聊天记录
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('list_id')->comment('会话分组编号');
            $table->string('msg_type', 12)->default('text')->comment('消息类型 text image audio card');
            $table->text('content')->comment('回复内容');//cType[1:个性化问诊单|2:普通问诊单|3:处方|4:已回答个性化问诊单]
            $table->unsignedTinyInteger('type')->comment('谁发送的 1患者 2医生 3系统');
            $table->string('send_ip', 20)->nullable()->comment('发送人ip');
            $table->unsignedTinyInteger('status')->default(0)->comment('类型 0:未读 1：已读');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('list_id')->references('id')->on('message_lists');
            $table->comment = '医生聊天诊疗记录表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
