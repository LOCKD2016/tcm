<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorLeaveTable extends Migration
{
    /**
     * 医生请假记录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_leaves', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->comment('医生id');
            $table->integer('day')->default(0)->comment('休息天数');
            $table->timestamp('start_time')->comment('开始时间');
            $table->timestamp('end_time')->comment('结束时间');
            $table->tinyInteger('status')->default(0)->comment('状态 0:未审核 1:审核通过 2拒绝');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->comment = "医生请假记录表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_leaves');
    }
}
