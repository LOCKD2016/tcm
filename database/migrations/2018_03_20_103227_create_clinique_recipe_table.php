<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCliniqueRecipeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinique_recipe', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->string('Bespeak_CODE_NO')->nullable()->comment('预约显示编号');
            $table->string('DiagnosisName')->nullable()->comment('诊断名称');
            $table->string('CUSTOMER_NAME')->nullable()->comment('客户名称');
            $table->string('RCPCLASS_CODE_NO')->nullable()->comment('处方显示编号');
            $table->string('SERVICE_CODE_NO')->nullable()->comment('服务项目编号');
            $table->string('SERVICE_NAME')->nullable()->comment('服务项目名称');
            $table->string('DrugUsageName')->nullable()->comment('药品用法');
            $table->string('Dose')->nullable()->comment('用量');
            $table->string('DoseUnitName')->nullable()->comment('用量单位');
            $table->string('FrequencyName')->nullable()->comment('频次');
            $table->string('Quantity')->nullable()->comment('数量');
            $table->string('QuantityUnitName')->nullable()->comment('数量单位');
            $table->string('AGENT_NUM')->nullable()->comment('付数');
            $table->string('TISANES_WAY_NAME')->nullable()->comment('煎药方式');
            $table->string('TISANES_DOSE_NAME')->nullable()->comment('煎药剂量');
            $table->string('RCP_DOC_NAME')->nullable()->comment('医生姓名');
            $table->string('DEPARTMENT_NAME')->nullable()->comment('部门名称');
            $table->dateTime('RCP_DATE')->nullable()->comment('开方时间');

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
        Schema::dropIfExists('clinique_recipe');
    }
}
