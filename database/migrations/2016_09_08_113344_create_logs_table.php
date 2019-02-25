<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * 管理员登录日志表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //登录日志表
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('ip',128);
            $table->string('address',50)->nullable();
            $table->string('useragent',200)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('user_id')->on('user')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->commnet = "管理员登录日志表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('logs');
    }
}
