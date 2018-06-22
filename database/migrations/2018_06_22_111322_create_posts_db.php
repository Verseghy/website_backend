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
        Schema::create('posts_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->string('content'); // md parsed by php
            $table->unsignedInteger('index_image'); // X-ref to posts_images
            $table->string('labels'); // X-ref to many from posts_labels
            $table->unsignedInteger('author'); // X-ref to posts_authors
            $table->timestamp('date');
            $table->unsignedSmallInteger('type');
        });
        
        Schema::create('posts_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('color');
        });
        
        Schema::create('posts_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('image'); // X-ref to posts_images
        });
        
        
        Schema::create('posts_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url');
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
