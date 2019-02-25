<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationLogsTable extends Migration
{
    /**
     * 后台操作日志表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment("操作人员id");
            $table->string('send_people', 50)->nullable()->comment("发送人姓名");
            $table->string('receive_people', 50)->nullable()->comment("接收人姓名");
            $table->string('operation_detail')->nullable()->comment("操作内容");
            $table->integer('read_flag')->default(0)->comment("已读标记,0未读，1已读");
            $table->foreign('user_id')->references('user_id')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('send_people')->references('user_name')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receive_people')->references('user_name')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->commnet = "操作日志表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operation_logs');
    }
}
