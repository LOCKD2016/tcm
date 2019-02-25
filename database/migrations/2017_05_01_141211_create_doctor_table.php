<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorTable extends Migration
{
    /**
     * 医生表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name', 50)->nullable()->comment('医师姓名');
            $table->string('mobile', 20)->unique()->comment('医师手机号 /APP登录账号');
            $table->date('birthday')->nullable()->comment('出生日期 如 "2013-03-21"');
            $table->string('salt', 255)->nullable()->comment('加密盐');
            $table->string('password', 255)->nullable()->comment('医师app端登录密码');
            $table->tinyInteger('sex')->default(0)->comment('性别,0男 1 女');
            $table->string('head_img', 250)->nullable()->comment('大头像地址');
            $table->string('address', 50)->nullable()->comment('家庭住址');

            $table->string('head_img_L', 250)->nullable()->comment('小头像地址');
            $table->text('intro')->nullable()->comment('医生介绍');

            $table->string('code', 50)->default(0)->comment('医师资格证编码');

            $table->text('desc')->nullable()->comment('门诊信息');
            $table->string('nation', 200)->nullable()->comment('国籍');
            $table->string('native', 200)->nullable()->comment('籍贯');
            $table->tinyInteger('idType')->nullable()->comment('证件类型; 0:身份证;1:军官证;2:护照;3:台胞证;4:其他;9:无证件');
            $table->string('idNo', 200)->nullable()->comment('证件号码');
            $table->tinyInteger('title')->nullable()->comment('职称');

            //证书认证
            $table->string('profession_auth', 255)->nullable()->comment('职业证书 正反面 拼接一起');
            $table->string('qualification_auth', 255)->nullable()->comment('资格证书 正反面 拼接一起');
            $table->string('major_qualification_auth', 100)->nullable()->comment('专业技术资格证书');

            $table->tinyInteger('web')->default(1)->comment('是否开通网诊 1:开通 0:关闭');
            $table->tinyInteger('clinic')->default(1)->comment('是否门诊 1:开通 0:关闭');
            $table->tinyInteger('rest')->default(0)->comment('是否休息 1:是 0:否');
            $table->tinyInteger('clinic_monopoly')->default(1)->comment('门诊排班的日期是否是独占 1:独占 0:非独占 如果是独占 同一个时间段 只能一个人预约');

            $table->string('im_token', 128)->nullable()->comment('im_token');
            $table->string('jpush_key', 200)->nullable()->comment('极光推送key');
            $table->string('qrCode')->nullable()->comment('医生二维码');
            $table->string('rand_code')->nullable()->comment('随机字符串 医生用来扫描编写问诊单');
            $table->integer('web_amount')->default(20000)->comment('网诊的价格');//以分为单位
            $table->integer('clinic_amount')->default(20000)->comment('门诊的价格');//以分为单位
            $table->integer('video_amount')->default(50000)->comment('视频问医的价格');//以分为单位

            $table->string('level')->default(0)->comment('患者推荐指数 数字 0-5');
            $table->string('diy_level')->default(5)->comment('患者推荐指数 后台自定义');

            $table->unsignedTinyInteger('use_diy')->default(0)->comment('是否使用后台自定义指数 0是 1不是');
            $table->unsignedTinyInteger('read_recipe')->default(0)->comment('是否可以查看门诊的药方 0 不可以 1 可以');

            $table->tinyInteger('source')->default(1)->comment('医生来源 1:我们创建 0:API创建');

            $table->tinyInteger('status')->default(0)->comment('医生状态 1审核通过 0待审核 2审核不通过');

            $table->integer('length')->default(15)->comment('医生坐诊时间间隔 默认15分钟');
            $table->integer('is_del')->default(0)->comment('假删除字段0：没有删除，1：已经删除');

            $table->softDeletes();
            $table->timestamps();
            $table->comment = '医生表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('doctors');
    }
}
