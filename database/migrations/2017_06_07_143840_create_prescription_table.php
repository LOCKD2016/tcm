<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionTable extends Migration
{
    /**
     * 医生给用户开的药方表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescription', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->unsigned()->comment('用户的编号');
            $table->unsignedInteger('clinic_id')->default(0)->comment('诊疗编号');
            $table->unsignedInteger('doctor_id')->default(0)->comment('医生编号');
            $table->unsignedInteger('order_id')->comment('生成订单 药方的订单id');

            //传方抓药
            $table->string('medicinal_type_name')->nullable()->comment('中成药的类型');
            $table->string('recipe_self')->nullable()->comment('自备json');

            $table->string('disease_zh', 50)->nullable()->comment('中医诊断');
            $table->string('disease_en', 50)->nullable()->comment('西医诊断');
            $table->string('disease', 50)->nullable()->comment('中医辨证');


            $table->string('recipe_head')->nullable()->comment('处方头部 {sun:"总帧数",dayNum:"每日服用剂数",takingNum:"每剂服用次数",sumWeight:"每剂重量"}');
            $table->text('recipe')->nullable()->comment('处方药方 json格式');

            //费用
            $table->integer('medicine_price')->default(0)->comment('药方报价 单位:分');
            $table->integer('tisane_price')->default(0)->comment('代煎费用 不打折 单位:分');
            $table->integer('dispensing_price')->default(0)->comment('调剂费 单位:分');
            $table->integer('recipe_self_price')->default(0)->comment('自备减去费用 单位:分');

            //状态
            $table->tinyInteger('is_price')->default(0)->comment("是否划价 0:未划价 1:已划价 3:已支付 4:已发货 5:已经到货 6退款中 7已退款 8:药方过期 9:拒绝开方");
            $table->dateTime('price_time')->nullable()->comment('后台操作时间 对应 is_price操作');
            $table->tinyInteger('send')->default(0)->comment('是否发送 1为发送  不发送 前端用户看不到已经划价的价格');
            $table->tinyInteger('tisane')->default(0)->comment('是否代煎 1为需要');
            $table->tinyInteger('express')->default(0)->comment(' 是否快递 0为自取 1 快递');
            $table->tinyInteger('remind')->default(0)->comment('是否提醒用户 0 未提醒 1 提醒 2 确认');
            $table->dateTime('remind_time')->nullable()->comment('提醒时间');

            $table->text('recipe_remark')->nullable()->comment('处方医嘱');

            $table->string('admin_id')->nullable()->comment('操作人员编号');
            $table->string('admin_remark')->nullable()->comment('后台备注');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');
            $table->foreign('clinic_id')->references('id')->on('clinics');

            $table->comment = '医生给用户开的药方表';

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescription');
    }
}
