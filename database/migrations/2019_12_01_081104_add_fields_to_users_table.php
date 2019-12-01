<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tel', 50)->default('')->after('email')->comment('手机号');
            $table->tinyInteger('sex')->after('email')->default('0')->comment('性别,0:保密,1:男,2:女');
            $table->string('intro', 255)->default('')->after('password')->comment('介绍');
            $table->string('city', 255)->default('')->after('password')->comment('城市');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
