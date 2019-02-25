<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHealthTable extends Migration
{
    /**
     * 分组表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_health', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->text('content')->comment('内容');
            $table->text('day_avg')->comment('每天的平均数据');
            $table->dateTime('date')->comment('日期时间');
            $table->tinyInteger('type')->comment('数据类型 1:血压 2:血糖 3:血氧 4:体温 5:体质');
            $table->text('extend')->nullable()->comment('扩展字段');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('app_users');

            $table->comment = '用户的健康数据表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_health');
    }
}
