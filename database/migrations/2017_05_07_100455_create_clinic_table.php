<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClinicTable extends Migration
{
    /**
     * 诊疗表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('bespeak_id')->comment('预约编号');
            $table->unsignedInteger('doctor_id')->comment('医生id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('disease', 50)->nullable()->comment('医生给的疾病名称');
            $table->text('content')->nullable()->comment('诊疗内容');
            $table->tinyInteger('type')->default(0)->comment('0:网聊 1:门诊');
            $table->tinyInteger('first')->default(0)->comment('1:初诊 0:复诊');
            $table->timestamp('end_time')->nullable()->comment('结束问诊时间');
            $table->timestamp('ask_time')->nullable()->comment('追问开始时间');
            $table->tinyInteger('status')->default(0)->comment('诊疗状态 0:诊疗未开始[比如门诊预约未到时间] 5:诊疗中 9:追问中 10:诊疗结束');
            $table->enum('comment', ['0', '1'])->default('0')->default()->comment('评论 0:不能评论 1:能评论'); //当前诊疗能否评价
            $table->timestamps();
            $table->tinyInteger('ask_status')->default(0)->comment('是否显示追问，0：不显示，1：显示');
            $table->tinyInteger('end_status')->default(1)->comment('结束追问：1：不显示，2：显示');

            $table->foreign('user_id')->references('id')->on('app_users');
            $table->foreign('doctor_id')->references('id')->on('doctors');
//            $table->foreign('bespeak_id')->references('id')->on('bespeaks');

            $table->index(['doctor_id', 'user_id', 'bespeak_id']);
            $table->comment = '诊疗表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinics');
    }
}
