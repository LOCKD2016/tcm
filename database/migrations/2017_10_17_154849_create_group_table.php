<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupTable extends Migration
{
    /**
     * 分组表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->comment('医生编号');
            $table->string('name', 50)->comment('组名');
            $table->integer('num')->comment('组内成员数量');
            $table->text('extend')->nullable()->comment('扩展字段');

            $table->foreign('doctor_id')->references('id')->on('doctors');

            $table->comment = '医生的 分组表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group');
    }
}
