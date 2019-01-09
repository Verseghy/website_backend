<?php

use Illuminate\Database\Seeder;
use App\Models\Canteens;
use App\Models\Canteens\Menus;

class CanteensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        factory(Menus::class, 25)->create();
        factory(Canteens::class, 50)->create()->each(function ($canteen) {
            $soup = Menus::where('type', '=', 0)->inRandomOrder()->first();
            $meal = Menus::where('type', '=', 1)->inRandomOrder()->first();
            $dessert = Menus::where('type', '=', 2)->inRandomOrder()->first();

            $canteen->menus()->attach($soup);
            $canteen->menus()->attach($meal);
            $canteen->menus()->attach($dessert);
        });
    }
}
