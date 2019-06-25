<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArticleCategoryTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('slug', 255)->after('id')->default('')->comment('slug');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('slug', 255)->after('id')->default('')->comment('slug');
        });
        Schema::table('tags', function (Blueprint $table) {
            $table->string('slug', 255)->after('id')->default('')->comment('slug');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
