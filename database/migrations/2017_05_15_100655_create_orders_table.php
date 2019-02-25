<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * 订单表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //订单信息表
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id')->comment('订单信息自增id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父订单id');

            $table->string('order_sn', 20)->unique()->comment('订单号,唯一');
            $table->string('wx_order_no', 32)->nullable()->comment('微信返回的订单号,退款用');
            $table->string('body', 255)->nullable()->comment('商品描述');

            $table->string('user_name', 60)->nullable()->comment('收货人的姓名,用户页面填写,默认取值表address');
            $table->string('country', 30)->nullable()->comment('收货人的国家');
            $table->string('province', 100)->nullable()->comment("省份");
            $table->string('city', 100)->nullable()->comment("城市");
            $table->string('district', 100)->nullable()->comment("区县");
            $table->string('address', 255)->nullable()->comment('收货人的详细地址');
            $table->string('mobile', 60)->nullable()->comment('收货人的手机');
            $table->timestamp('pay_time')->nullable()->comment('订单支付时间');
            $table->timestamp('refund_time')->nullable()->comment('订单退款时间');

            $table->integer('address_id')->default(0)->comment('用户的地址id');

            $table->timestamp('shipping_time')->nullable()->comment('订单配送时间');
            $table->string('express_company', 30)->nullable()->comment('物流公司');
            $table->string('express_number', 30)->unique()->nullable()->comment('快递单号');

            $table->integer('amount')->comment('商品的总金额');//以分为单位
            $table->integer('payable_amount')->nullable()->comment('应付金额'); //以分为单位
            $table->integer('pay_amount')->nullable()->comment('实际支付金额'); //以分为单位
            $table->integer('refund_amount')->nullable()->comment('已经退款金额');//以分为单位
            $table->tinyInteger('order_type')->default(0)->comment('订单类型 1门诊 2网诊 3药方 4商品');
            $table->tinyInteger('pay_type')->default(0)->comment('支付方式 0:未付款 1:微信 5:免费订单');
            $table->tinyInteger('pay_status')->default(0)->comment('用户是否支付 0:未付款 1:已付款');


            $table->tinyInteger('status')->default(0)->comment('订单的状态 0 未支付 2正在支付 5已支付 7已过期 9退款中 10已退款');
            $table->string('extend', 255)->nullable()->comment('扩展字段 json格式');
            $table->string('desc')->nullable()->comment('订单备注');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('app_users');

            $table->comment = "订单信息表";
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
        Schema::dropIfExists('orders');
    }
}
