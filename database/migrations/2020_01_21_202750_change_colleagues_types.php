<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColleaguesTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Change all category 5's into category 4's
        // warning: this can not be easily reversed
        // altough no data is lost
        $rows = DB::table('colleagues_data')->get(['id', 'category']);
        foreach ($rows as $row) {
            if ($row->category==5) {
                DB::table('colleagues_data')
                    ->where('id', $row->id)
                    ->update(['category' => 4]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Change back all category 4's into category 5's
        // warning: this is not a 100% reverse of the migration
        // altough no data is lost
        $rows = DB::table('colleagues_data')->get(['id', 'category']);
        foreach ($rows as $row) {
            if ($row->category==4) {
                DB::table('colleagues_data')
                    ->where('id', $row->id)
                    ->update(['category' => 5]);
            }
        }
    }
}
