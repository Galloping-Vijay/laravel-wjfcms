<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('system_configs')) {
            Schema::create('system_configs', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('系统配置表');
                $table->string('title', 100)->default('')->comment('配置标题');
                $table->string('key', 100)->default('')->comment('字段名');
                $table->string('value', 255)->default('')->comment('字段值');
                $table->string('type', 100)->default('')->comment('类型(text,upload,radio,textarea)');
                $table->tinyInteger('status')->default('1')->comment('1:开启,0关闭');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_configs');
    }
}
