<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFriendLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('friend_links')) {
            Schema::create('friend_links', function (Blueprint $table) {
                $table->increments('id')->comment('友情链接表');
                $table->string('name', 50)->default('')->comment('链接名');
                $table->string('url')->default('')->comment('链接地址');
                $table->boolean('sort')->nullable()->default(1)->comment('排序');
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
        Schema::dropIfExists('friend_links');
    }
}
