<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('后台管理员表');
                $table->string('account',100)->default('')->unique()->comment('账号');
                $table->string('username',255)->default('')->comment('昵称');
                $table->string('password')->default('')->comment('密码');
                $table->string('tel',50)->default('')->comment('手机');
                $table->string('email',50)->default('')->comment('邮箱');
                $table->tinyInteger('sex')->default('-1')->comment('性别-1:保密,0:男,1:女');
				$table->tinyInteger('status')->default('1')->comment('状态0:禁用,1:启用');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists('admins');
    }
}
