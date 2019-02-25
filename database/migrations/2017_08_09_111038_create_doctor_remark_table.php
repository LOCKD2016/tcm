<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorRemarkTable extends Migration
{
    /**
     * 医生常用医嘱
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_remark', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->comment('医生ID');
            $table->string('title')->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');

            $table->comment = '医生常用医嘱';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_remark');
    }
}
