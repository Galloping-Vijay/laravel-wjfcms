<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToFriendLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('friend_links', function (Blueprint $table) {
            $table->tinyInteger('status')->after('sort')->default('0')->comment('0:禁用,1:启用');
            $table->string('email',150)->after('sort')->default('')->comment('联系邮箱');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('friend_links', function (Blueprint $table) {
            //
        });
    }
}
