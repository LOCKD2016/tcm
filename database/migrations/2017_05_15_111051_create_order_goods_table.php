<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderGoodsTable extends Migration
{
    /**
     * 订单的商品表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单的商品信息表1
        Schema::create('order_goods', function (Blueprint $table) {

            $table->increments('id')->comment('订单商品信息自增id');
            $table->unsignedInteger('order_id')->default(0)->comment('订单商品信息对应的详细信息id，取值order的order_id');
            $table->unsignedInteger('goods_id')->default(0)->comment('商品的的id，取值表goods 的goods_id');
            $table->string('name', 120)->comment('商品的名称，取值表goods');
            $table->smallInteger('number')->default(1)->comment('商品的购买数量');
            $table->integer('amount')->comment('商品价格');
            $table->tinyInteger('attr')->default(0)->comment('商品属性');
            $table->text('extend')->comment('扩展字段');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('goods_id')->references('id')->on('goods');

            $table->comment = '订单的商品信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_goods');
    }
}
