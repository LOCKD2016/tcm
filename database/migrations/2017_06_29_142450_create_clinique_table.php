<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCliniqueTable extends Migration
{
    /**
     * 诊所
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliniques', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 250)->comment('诊所名称');
            $table->string('telephone',50)->nullable()->comment('诊所电话');
            $table->string('address', 250)->nullable()->comment('诊所所在地');//北京
            $table->text('content')->nullable()->comment('诊所详情');

            $table->string('code')->nullable()->comment('诊所编码');//北京
            $table->timestamps();
            $table->comment = '诊所表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliniques');
    }
}
