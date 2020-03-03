<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('wx_keywords')) {
            Schema::create('wx_keywords', function (Blueprint $table) {
                $table->increments('id')->comment('微信关键词表');
                $table->string('key_name', 255)->default('')->comment('关键词名称');
                $table->string('key_value', 255)->default('')->comment('关键词内容');
                $table->boolean('status')->default(1)->comment('状态，1开0关');
                $table->boolean('sort')->nullable()->default(1)->comment('排序');
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
        Schema::dropIfExists('wx_keywords');
    }
}
