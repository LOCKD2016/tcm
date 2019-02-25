<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单操作日志表
        Schema::create('order_log', function (Blueprint $table) {
            $table->increments('id')->comment('编号');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->tinyInteger('type')->nullable()->comment('操作类型 1:订单生成 2:订单支付 3:订单取消 4:订单超时 ...');
            $table->string('desc', 255)->comment('操作内容备注');
            $table->text('extend')->comment('操作内容备注');
            $table->unsignedInteger('admin_id')->comment('后台操作人员名称');
            $table->timestamps();
            $table->comment = "订单操作日志表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_log');
    }
}
