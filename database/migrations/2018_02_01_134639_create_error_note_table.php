<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorNoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_note', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type')->default(0)->comment('类型 0:普通错误 1: 刷新token 2: 就诊日短信提醒');
            $table->string('code')->nullable()->comment('错误码');
            $table->text('content')->nullable()->comment('错误信息');
            $table->timestamps();

            $table->comment = '错误信息表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('error_note');
    }
}
