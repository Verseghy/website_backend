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
        Schema::create('posts_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
        });

        Schema::create('posts_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
            $table->unsignedInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts_data');
        });
        
        Schema::create('posts_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');

            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')->on('posts_images');
        });

        Schema::create('posts_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('content');

            $table->unsignedInteger('index_image')->nullable();
            $table->foreign('index_image')->references('id')->on('posts_images');

            $table->unsignedInteger('author_id');
            $table->foreign('author_id')->references('id')->on('posts_authors');

            $table->timestamp('date')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
        });

        Schema::create('posts_pivot_labels_data', function (Blueprint $table) {
            $table->unsignedInteger('labels_id');
            $table->foreign('labels_id')->references('id')->on('posts_labels');

            $table->unsignedInteger('posts_id');
            $table->foreign('posts_id')->references('id')->on('posts_data');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts_data');
        Schema::dropIfExists('posts_labels');
        Schema::dropIfExists('posts_authors');
        Schema::dropIfExists('posts_images');
    }
}
