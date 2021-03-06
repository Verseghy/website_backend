<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsDb extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events_data', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date_from');
            $table->dateTime('date_to');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('events_data');
    }
}
