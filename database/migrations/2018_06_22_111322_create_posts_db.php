<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Table creation
         */
        Schema::create('posts_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
        });

        Schema::create('posts_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->unsignedInteger('post_id')->nullable();
        });
        
        Schema::create('posts_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('image_id')->nullable();
        });

        Schema::create('posts_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('content')->nullable();
            $table->unsignedInteger('index_image')->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
        });

        Schema::create('posts_pivot_labels_data', function (Blueprint $table) {
            $table->unsignedInteger('labels_id')->nullable();
            $table->foreign('labels_id')->references('id')->on('posts_labels');

            $table->unsignedInteger('posts_id')->nullable();
            $table->foreign('posts_id')->references('id')->on('posts_data');
        });
        /**
         * Foreign keys
         */
        Schema::table('posts_images', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts_data');
        });

        Schema::table('posts_authors', function (Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('posts_images');
        });

        Schema::table('posts_data', function (Blueprint $table) {
            $table->foreign('index_image')->references('id')->on('posts_images');
            $table->foreign('author_id')->references('id')->on('posts_authors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('posts_labels');
        Schema::dropIfExists('posts_images');
        Schema::dropIfExists('posts_authors');
        Schema::dropIfExists('posts_data');
        Schema::dropIfExists('posts_pivot_labels_data');
        Schema::enableForeignKeyConstraints();
    }
}
