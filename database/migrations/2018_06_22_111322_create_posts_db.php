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
        
        Schema::create('posts_authors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->longText('description');
            $table->string('image')->nullable();
        });

        Schema::create('posts_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('color')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('index_image')->nullable();
            $table->unsignedInteger('author_id')->nullable();
            $table->longText('images')->nullable();
            $table->timestamp('date')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->timestamps();
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

        Schema::table('posts_data', function (Blueprint $table) {
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
