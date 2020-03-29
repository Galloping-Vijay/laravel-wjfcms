<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSystemConfigsAddConfigType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_configs', function (Blueprint $table) {
            $table->tinyInteger('config_type')->after('type')->default('0')->comment('设置类型,0:基础配置,1:微信配置,2:小程序配置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_configs', function (Blueprint $table) {
            //
        });
    }
}
