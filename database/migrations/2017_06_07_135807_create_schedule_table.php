<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScheduleTable extends Migration
{
    /**
     * 排班表 只保留日期 不保留时间
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clinique_id')->comment('医馆编号');
            $table->integer('doctor_id')->comment('医生编号');
            $table->date('date')->comment('出诊日期');
            $table->string('start_time')->comment('出诊开始时间');
            $table->string('end_time')->comment('出诊结束时间');
            $table->string('code')->comment('标识');
            $table->timestamps();
            $table->comment = "医生排班表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
}
