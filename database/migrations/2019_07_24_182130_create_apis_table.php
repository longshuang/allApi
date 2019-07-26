<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', '50')->comment('名称');
            $table->string('code', '10')->comment('唯一编码');
            $table->string('url', '200')->comment('api地址');
            $table->integer('type')->comment('类型');
            $table->integer('source_id')->comment('来源');
            $table->string('request_method')->nullable()->comment('请求方式');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apis');
    }
}
