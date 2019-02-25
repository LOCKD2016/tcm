<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamTable extends Migration
{
    /**、
     * 问诊单表
     * Run the migrations.1
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->default(0)->comment('医生ID 系统问诊单为0');
            $table->string('title', 50)->nullable()->comment("问诊单标题");
            $table->unsignedTinyInteger('type')->default(0)->comment('0:系统问诊单 1:男 2:女 3:儿童');
            $table->timestamps();
            $table->softDeletes();

            $table->comment = '医生问诊单表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam');
    }
}
