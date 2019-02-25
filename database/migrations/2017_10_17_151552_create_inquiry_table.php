<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryTable extends Migration
{
    /**
     * 普通问诊单
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('诊疗人编号');
            $table->unsignedInteger('bespeak_id')->comment('预约编号');
            $table->unsignedInteger('clinic_id')->comment('诊疗编号');
            $table->string('disease',500)->nullable()->comment('疾病名称');
            $table->string('desc')->nullable()->comment('疾病详情描述');
            $table->tinyInteger('type')->default(0)->comment('是否是初诊 1是 0否');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('app_users');
            $table->foreign('bespeak_id')->references('id')->on('bespeaks');

            $table->comment = "普通问诊单id";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry');
    }
}
