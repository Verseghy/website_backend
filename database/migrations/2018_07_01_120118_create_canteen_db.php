<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCanteenDb extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('canteen_data', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date');
            $table->timestamps();
        });

        Schema::create('canteen_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('menu');
            $table->unsignedSmallInteger('type');
            $table->timestamps();
        });

        Schema::create('canteen_pivot_menus_data', function (Blueprint $table) {
            $table->unsignedInteger('data_id')->nullable();
            $table->foreign('data_id')->references('id')->on('canteen_data');

            $table->unsignedInteger('menu_id')->nullable();
            $table->foreign('menu_id')->references('id')->on('canteen_menus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('canteen_data');
        Schema::dropIfExists('canteen_menus');
        Schema::dropIfExists('canteen_pivot_menus_data');

        Schema::enableForeignKeyConstraints();
    }
}
