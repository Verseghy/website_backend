<?php

use Illuminate\Database\Seeder;
use App\Models\Events;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(Events::class, 150)->create();
    }
}
