<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamOptionsTable extends Migration
{
    /**
     * 问诊单的题目表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_options', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('exam_id')->comment('问诊单编号');
            $table->string('title', 250)->nullable()->comment('问题的标题');
            $table->enum('type', ['text', 'radio', 'checkbox', 'photo'])->default('text')->comment('试题类型 text:填空 radio:单选 checkbox:复选 photo:照片'); //判断>3的存json格式
            $table->text('option')->comment('选项 json 格式储存');
            $table->unsignedTinyInteger('must')->default(0)->comment('是否必填  1为不用必填');
            $table->integer('sort')->default(1)->comment('试题排序');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exam')->onDelete('cascade');

            $table->comment = '问诊单题目表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_options');
    }
}
