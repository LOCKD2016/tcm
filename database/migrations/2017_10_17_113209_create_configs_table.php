<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsTable extends Migration
{
    /**
     * @title 项目配置表
     * @desc 主要用于那些简单的数据 不需要再建立一张表了 例如 用户协议等
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable()->comment('配置名称');
            $table->string('key', 250)->nullable()->comment('配置项');
            $table->text('value')->comment('配置值');
            $table->string('desc', 250)->comment('配置简介');
            $table->tinyInteger('status')->default(0)->comment('状态 0:关闭 1:开启');
            $table->timestamps();
            $table->comment = '项目配置表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
