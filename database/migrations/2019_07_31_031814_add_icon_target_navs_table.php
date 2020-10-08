<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIconTargetNavsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('navs', function (Blueprint $table) {
            $table->string('icon', 150)->after('url')->default('')->comment('导航图标');
            $table->string('target', 50)->after('url')->default('_self')->comment('链接打开方式(_blank|_self|_parent|_top|framename)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('navs', function (Blueprint $table) {
            //
        });
    }
}
