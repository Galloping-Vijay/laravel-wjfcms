<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->bigIncrements('id')->comment('用户表');
                $table->string('name', 100)->default('')->comment('昵称');
                $table->string('email',100)->default('')->comment('邮箱');
                $table->tinyInteger('sex')->default('0')->comment('性别,0:保密,1:男,2:女');
                $table->string('tel', 50)->default('')->comment('手机号');
                $table->string('city', 255)->default('')->comment('城市');
                $table->string('intro', 255)->default('')->comment('介绍');
                $table->text('avatar')->nullable()->comment('用户头像');
                $table->string('provider_id')->default('')->comment('用户唯一ID');
                $table->string('provider', 255)->default('')->comment(' OAuth 服务提供方');
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password', 100)->default('')->comment('密码');
                $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
