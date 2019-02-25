<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * 诊疗评论表
     * Run the migrations.1
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('患者的id');
            $table->unsignedInteger('doctor_id')->comment('医生id');
            $table->unsignedInteger('clinic_id')->comment('诊疗表id');
            $table->string('disease', 50)->nullable()->comment('医生给的疾病名称');
            $table->integer('condition')->unsigned()->comment('病情  1:痊愈: 2:有效 3：无效 4：恶化 等等');
            $table->tinyInteger('manner')->default(0)->comment('态度 对应的几颗星');
            $table->tinyInteger('effect')->default(0)->comment('疗效');
            $table->text('content')->nullable()->comment('评论内容');
            $table->tinyInteger('status')->default(0)->comment('暂存类型 0 未审核 1审核通过 2审核不通过 ');
            $table->timestamps();

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');
            $table->foreign('clinic_id')->references('id')->on('clinics');

            $table->index(['user_id', 'clinic_id', 'doctor_id']);
            $table->comment = '评论表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment');
    }
}
