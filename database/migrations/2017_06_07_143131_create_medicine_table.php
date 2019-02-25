<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicineTable extends Migration
{
    /**
     * 药材表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medicine', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->comment('药材名称');
            $table->string('spell', 20)->comment('拼音快捷码');
            $table->integer('amount')->nullable()->comment('价格 单位:分');
            $table->string('unit', 20)->nullable()->comment('单位');
            $table->text('desc')->nullable()->comment('套餐描述');
            $table->tinyInteger('type')->comment('类型 1单品 2套餐 3其他');
            $table->string('code')->comment('编号');
            $table->timestamps();
            $table->comment = '药材表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medicine');
    }
}
