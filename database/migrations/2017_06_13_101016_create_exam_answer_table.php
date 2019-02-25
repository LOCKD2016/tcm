<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamAnswerTable extends Migration
{
    /**
     * 用户填写问诊答案表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('填写问诊单用户id');
            $table->unsignedInteger('clinic_id')->comment('诊疗ID');
            $table->unsignedInteger('exam_id')->comment('问诊单ID');
            $table->unsignedInteger('question_id')->comment('问诊单题目ID'); //如果要修改 肯定要对应用的
            $table->text('question')->comment('问诊单题目');//缓存下问诊单的题目
            $table->text('answer')->nullable()->comment("问诊单答案");//存最终答案拼接好的的字符
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('app_users')->onDelete('cascade');
            $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('exam_options')->onDelete('cascade');
            $table->foreign('clinic_id')->references('id')->on('clinics')->onDelete('cascade');

            $table->comment = '用户填写问诊答案表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_answer');
    }
}
