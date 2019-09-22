<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostsPublishedMigration extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('posts_data', function (Blueprint $table) {
            $table->boolean('published')->default(false);
            $table->string('previewToken')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('posts_data', function (Blueprint $table) {
            $table->dropColumn('published');
            $table->dromColumn('previewToken');
        });
    }
}
