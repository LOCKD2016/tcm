<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreasTable extends Migration
{
    /**
     * 地区表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //地区表(循环城市json数据得到)
        Schema::create('areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->unsigned()->nullable()->comment('区域ID');
            $table->string('name')->comment('区域名称');
            $table->integer('amount')->default(0)->comment('起步价');//单位 分
            $table->integer('weight')->default(500)->comment('首重 g');
            $table->integer('price')->default(0)->comment('续重 分/kg');
            $table->commnet = '地区表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}
