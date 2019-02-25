<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmTelephone extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm_telephone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kname')->nullable()->comment('客服姓名');
            $table->integer('clinique_id')->default(0)->comment('诊所id');
            $table->string('telephone')->nullable()->comment('手机号');
            $table->unsignedTinyInteger('status')->comment('状态 0不在线 1在线');
            $table->timestamps();

            $table->comment = '客服手机号';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
