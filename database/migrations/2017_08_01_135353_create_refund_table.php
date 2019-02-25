<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->unsigned()->comment('订单id');
            $table->unsignedInteger('admin_id')->unsigned()->comment('后台操作人员id');
            $table->decimal('fee', 10, 2)->comment('实际退款金额退款');
            $table->tinyInteger('type')->default(0)->comment('退款方式 0:会员卡 2:微信');
            $table->tinyInteger('schedule')->default(0)->comment('退款进度  0 进行中 1已退款 2拒绝退款');
            $table->tinyInteger('source')->default(0)->comment('0用户发起 1后台发起');
            $table->string('error_note', 150)->nullable()->comment('错误内容备注');
            $table->timestamps();
            $table->comment = '退款记录表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund');
    }
}
