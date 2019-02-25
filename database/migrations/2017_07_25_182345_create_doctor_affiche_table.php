<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorAfficheTable extends Migration
{
    /**
     * 医生公告记录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_affiche', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('doctor_id')->unsigned()->comment('医生ID');
            $table->string('title')->nullable()->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->tinyInteger('status')->nullable(1)->comment('状态 1:应用 0:不应用');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');

            $table->comment = "医生公告记录表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctor_affiche');
    }
}
