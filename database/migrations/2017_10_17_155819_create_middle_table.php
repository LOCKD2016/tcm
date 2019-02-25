<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiddleTable extends Migration
{
    /**
     * 中间表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 医生的分组和用户关联表
         */
        Schema::create('group_user', function (Blueprint $table) {
            $table->unsignedInteger('group_id')->comment('分组编号');
            $table->unsignedInteger('user_id')->comment('用户编号');

            $table->foreign('group_id')->references('id')->on('group');
            $table->foreign('user_id')->references('id')->on('app_users');

            $table->comment = '医生的分组和用户关联表';
        });

        /**
         * 用户和医生关联表
         */
        Schema::create('doctor_users', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id')->comment('医生编号');
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->text('extend')->comment('扩展字段');
            $table->dateTime('time')->comment('最后一次看诊时间');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('user_id')->references('id')->on('app_users');

            $table->primary(['doctor_id', 'user_id']);
            $table->comment = '用户和医生关联表';
        });

        /**
         * 医生和科室关联表
         */
        Schema::create('doctor_section', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id')->comment('医生编号');
            $table->unsignedInteger('section_id')->comment('科室编号');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('section_id')->references('id')->on('sections');

            $table->comment = '医生和科室关联表';
        });

        /**
         * 医生和疾病关联表
         */
        Schema::create('doctor_disease', function (Blueprint $table) {
            $table->unsignedInteger('doctor_id')->comment('医生编号');
            $table->unsignedInteger('disease_id')->comment('疾病编号');

            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('disease_id')->references('id')->on('disease');

            $table->comment = '医生和疾病关联表';
        });

        /**
         * 医生和诊所的关联表
         */
        Schema::create('doctor_clinique', function (Blueprint $table) {
            $table->unsignedInteger('clinique_id');
            $table->unsignedInteger('doctor_id');
            $table->string('code')->default(0)->comment('医生所在诊所的编号');

            $table->foreign('doctor_id')->references('id')->on('doctors')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('clinique_id')->references('id')->on('cliniques')->onUpdate('cascade')->onDelete('cascade');
            $table->index(['doctor_id']);
            $table->softDeletes();
            $table->comment = '医生诊所关联表';
        });

        /**
         * 用户和诊所的关联表
         */
        Schema::create('user_clinique', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('用户编号');
            $table->unsignedInteger('clinique_id')->comment('诊所编号');
            $table->string('code')->comment('用户所在诊所的编号');

            $table->foreign('user_id')->references('id')->on('app_users');
            $table->foreign('clinique_id')->references('id')->on('cliniques');

            $table->index(['user_id']);
            $table->comment = '用户和诊所的关联表';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('user_doctor');
        Schema::dropIfExists('doctor_section');
        Schema::dropIfExists('doctor_disease');
        Schema::dropIfExists('doctor_clinique');
        Schema::dropIfExists('user_clinique');
    }
}
