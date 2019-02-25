<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * 用户收货地址表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //收货人的信息列表
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id')->comment('地址id');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->string('user_name', 60)->comment('收货人的名字');
            $table->string('mobile', 15)->comment('收货人的手机号');
            $table->string('country', 100)->default("中国")->comment("收货人国家 默认值中国");
            $table->string('province', 100)->nullable()->comment("省份");
            $table->string('city', 100)->nullable()->comment("城市");
            $table->string('district', 100)->nullable()->comment("区县");
            $table->string('address', 120)->comment('收货人的详细地址');
            $table->tinyInteger('is_default')->default(0)->comment('是否默认');
            $table->foreign('user_id')->references('id')->on('app_users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->comment = "用户收货地址表";
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
        Schema::dropIfExists('address');
    }

}
