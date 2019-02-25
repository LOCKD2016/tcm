<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiseaseTable extends Migration
{
    /**
     * 疾病表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('section_id')->default(0)->comment('科室的ID');
            $table->string('name', 100)->comment('疾病名称');
            $table->integer('sort')->default(0)->comment('排序');
            $table->timestamps();
//            $table->foreign('section_id')->references('id')->on('sections');

            $table->index(['section_id']);
            $table->comment = '疾病表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease');
    }
}
