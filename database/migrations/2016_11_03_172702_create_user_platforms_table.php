<?php

use App\SchemaExtend\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPlatformsTable extends Migration
{
    /**
     * 第三方登录表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //QQ
        Schema::create('user_qqs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id")->nullable()->commnet("用户id");
            $table->string("openid",64)->unique()->commnet("第三方平台id");
            $table->string("nickname")->nullable()->commnet("昵称");
            $table->string("avatar")->nullable()->commnet("第三方的头像");
            $table->string("province",100)->nullable()->commnet("省份");
            $table->string("city",100)->nullable()->commnet("城市");
            $table->string("country",100)->nullable()->commnet("国家");
            $table->tinyInteger("sex")->default(0)->nullable()->commnet("性别 1为男性，2为女性");
            $table->string("access_token")->nullable()->commnet("accesstoken");
            $table->unsignedTinyInteger("first_login")->default(0)->comment("是否第一次 0 是 1 不是");
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('app_users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->commnet = "QQ登录表";
        });

        //微信
        Schema::create('user_weixins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id")->nullable()->commnet("用户id");
            $table->string("unionid",64)->nullable()->unique()->commnet("第三方平台unionid");
            $table->string("openid",64)->unique()->commnet("第三方平台id");
            $table->string("nickname")->nullable()->commnet("昵称");
            $table->string("avatar")->nullable()->commnet("第三方的头像");
            $table->string("province",100)->nullable()->commnet("省份");
            $table->string("city",100)->nullable()->commnet("城市");
            $table->string("country",100)->nullable()->commnet("国家");
            $table->tinyInteger("sex")->default(0)->nullable()->commnet("性别 1为男性，2为女性");
            $table->string("access_token")->nullable()->commnet("accesstoken");
            $table->unsignedTinyInteger("first_login")->default(0)->comment("是否第一次 0 是 1 不是");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('app_users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->commnet = "微信登录表";
        });
        //微博
        Schema::create('user_weibos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id")->nullable()->commnet("用户id");
            $table->string("openid",64)->unique()->commnet("第三方平台id");
            $table->string("nickname")->nullable()->commnet("昵称");
            $table->string("avatar")->nullable()->commnet("第三方的头像");
            $table->string("province",100)->nullable()->commnet("省份");
            $table->string("city",100)->nullable()->commnet("城市");
            $table->string("country",100)->nullable()->commnet("国家");
            $table->tinyInteger("sex")->default(0)->nullable()->commnet("性别 1为男性，2为女性");
            $table->string("access_token")->nullable()->commnet("accesstoken");
            $table->unsignedTinyInteger("first_login")->default(0)->comment("是否第一次 0 是 1 不是");
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('app_users')
                ->onUpdate('cascade')->onDelete('set null');
            $table->commnet = "微博登录表";
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
        Schema::dropIfExists('user_qqs');
        Schema::dropIfExists('user_weixins');
        Schema::dropIfExists('user_weibos');
    }
}
