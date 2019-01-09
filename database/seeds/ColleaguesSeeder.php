<?php

use Illuminate\Database\Seeder;
use App\Models\Colleagues;

class ColleaguesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(Colleagues::class, 10)->create();
    }
}
