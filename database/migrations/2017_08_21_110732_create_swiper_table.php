<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSwiperTable extends Migration
{
    /**
     * 轮播图表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('swiper', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->nullable()->comment('名称');
            $table->string('desc', 250)->nullable()->comment('简介');
            $table->string('image', 250)->nullable()->comment('图片地址');
            $table->string('url', 250)->nullable()->comment('跳转链接');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->timestamps();
            $table->comment = "轮播图表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slider');
    }
}
