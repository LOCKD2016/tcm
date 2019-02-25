<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFollowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_follow', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id')->unsigned()->comment('医生ID');
            $table->unsignedInteger('user_id')->unsigned()->comment('用户ID');
            $table->tinyInteger('status')->unsigned()->default(0)->comment('状态 0:仅关注 1:看病的医生');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');

            $table->primary(['doctor_id', 'user_id']);
            $table->comment = '用户对医生的关注表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_follow');
    }
}
