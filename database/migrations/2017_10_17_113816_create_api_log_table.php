<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApiLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method', 200)->nullable()->comment('请求名称 方法名等');
            $table->enum('type', ['send', 'return'])->nullable()->comment('请求类型 send:发送 return:返回的');
            $table->text('send')->comment('发送的数据');
            $table->text('return')->comment('返回的数据');
            $table->tinyInteger('status')->default(0)->comment('状态 0:失败 1:成功');
            $table->timestamps();
            $table->comment = 'API 请求日志';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_logs');
    }
}
