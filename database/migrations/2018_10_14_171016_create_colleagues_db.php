<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColleaguesDb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colleagues_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->longText('jobs')->nullable();
            $table->longText('subjects')->nullable();
            $table->longText('roles')->nullable();
            $table->longText('awards')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colleagues_data');
    }
}
