<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * 商品表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //产品表
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id')->comment('商品id');
            $table->string('name')->comment('商品名称');
            $table->string('image', 200)->nullable()->comment('商品图片');
            $table->string('share_image', 200)->nullable()->comment('商品分享图片');
            $table->integer('amount')->comment('商品价格'); //分
            $table->string('desc')->nullable()->comment('商品介绍');
            $table->tinyInteger('real')->default(0)->comment('是否是实物，0:否 1:是');
            $table->tinyInteger('type')->default(1)->comment('商品类型 1:普通商品 11:门诊预约 12:网诊预约 13:抓药');
            $table->tinyInteger('status')->default(1)->comment('状态 0:禁用 1:启用');
            $table->timestamps();
            $table->comment = '商品表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('goods');
    }
}
