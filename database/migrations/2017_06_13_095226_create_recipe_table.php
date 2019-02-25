<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipeTable extends Migration
{
    /**
     * 医生的常用处方表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->default(0)->comment('医生id');
            $table->string('title', 20)->comment('处方名称');
            $table->text('content')->comment('处方药材 json {[药材名称,重量,煎法,单位]}');
            $table->unsignedTinyInteger('type')->default(0)->comment('0:系统处方 1:医生处方');
            $table->tinyInteger('status')->default(1)->comment('状态 1:正常 0:禁用');
            $table->timestamps();

            $table->string('dictionary_code')->comment('字典编号');
            $table->string('type_code')->comment('字典分类编码');

            $table->foreign('doctor_id')->references('id')->on('doctors');

            $table->comment = '处方表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipe');
    }
}
