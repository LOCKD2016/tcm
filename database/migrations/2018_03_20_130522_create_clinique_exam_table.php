<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCliniqueExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinique_exam', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('Bespeak_CODE_NO')->nullable()->comment('预约显示编号');
            $table->string('CUSTOMER_NAME')->nullable()->comment('客户名称');
            $table->string('ChiefComplete')->nullable()->comment('主诉');
            $table->string('HisIllness')->nullable()->comment('现病史');
            $table->string('Casehistory')->nullable()->comment('既往史');
            $table->string('Familyhistory')->nullable()->comment('家族史');
            $table->decimal('Allergy',18,2)->nullable()->comment('过敏史');
            $table->string('PersonageHistory')->nullable()->comment('个人史');
            $table->string('Bearinghistory')->nullable()->comment('婚育史');
            $table->string('RCP_DOC_NAME')->nullable()->comment('医生名称');
            $table->string('DEPARTMENT_NAME')->nullable()->comment('部门名称');
            $table->dateTime('CreateDateTime')->nullable()->comment('创建时间');
            $table->timestamps();
            $table->softDeletes();

            $table->comment = '门诊处方信息表';
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
        Schema::dropIfExists('clinique_exam');
    }
}
