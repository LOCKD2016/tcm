<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * 管理员表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户表
        Schema::create('user', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('user_name',50)->unique();
            $table->string('user_password',100);
            $table->string('user_salt',100)->nullable();
            $table->string('user_realname',50);
            $table->string('user_phone',64)->nullable();
            $table->string('user_address',50)->nullable();
            $table->string('user_email',50)->nullable();
            $table->timestamp('user_last_login_time')->nullable();
            $table->integer('user_status')->default(1);
            $table->timestamp('user_create_time')->nullable();
            $table->integer('fulljob')->default(0);
            $table->integer('sort_num')->default(0);
            $table->string('invest_field',1024)->nullable();
            $table->string('other_field',1024)->nullable();
            $table->integer('mon_sort')->default(0);
            $table->string("remember_token")->nullable();
            $table->comment = "管理员表";
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
