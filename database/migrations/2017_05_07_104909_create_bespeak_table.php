<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBespeakTable extends Migration
{
    /**
     * 用户预约记录表
     * Run the migrations.1
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bespeaks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->unsignedInteger('doctor_id')->comment('医生编号');
            $table->unsignedInteger('clinic_id')->comment('诊疗编号');
            $table->unsignedInteger('scheduling_id')->default(0)->comment('门诊对应的排班id  这个暂时不用 因为发现 可以不需要，如果需要后期再加');
            $table->unsignedInteger('clinique_id')->default(0)->comment('预约门店');
            $table->string('disease', 500)->nullable()->comment('疾病名称');
            $table->dateTime('start_time')->nullable()->comment('预约开始时间(门诊) 网诊的话表示 预约时间');

            $table->dateTime('end_time')->nullable()->comment('预约结束时间(门诊)');
            $table->tinyInteger('type')->default(0)->comment('0:网聊 1:门诊');
            $table->tinyInteger('first')->default(0)->comment('1:初诊 0:复诊');
            $table->tinyInteger('redundant_first')->default(0)->comment('是否是初诊 1:初诊 0:复诊 页面中需要记录 虽然没什么卵用');
            $table->tinyInteger('redundant_in')->default(0)->comment('是否是泰和国医的用户 1:是 0:不是 虽然也没啥卵用 但是页面需要');
            $table->integer('order_id')->default(0)->comment('生成订单ID');
            $table->tinyInteger('status')->default(0)->comment('预约状态 5待接诊 10待支付 15已支付 20诊疗中 25诊疗结束 30医生拒绝接诊 35诊疗已取消 38已过期');
            $table->string('remark', 255)->nullable()->comment('客服备注');

            $table->dateTime('take_time')->nullable()->comment('接诊时间');

            $table->integer('admin_id')->default(0)->comment('后台操作人员');
            $table->timestamps();
            $table->softDeletes();

            $table->string('bespeak_code')->comment('其他系统需要');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');
//            $table->foreign('clinique_id')->references('id')->on('cliniques');

            $table->comment = '预约表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bespeaks');
    }
}
