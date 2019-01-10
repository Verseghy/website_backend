<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(PostsSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(CanteensSeeder::class);
        $this->call(NewsletterSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ColleaguesSeeder::class);
        $this->call(RolesPermissionsSeeder::class);
    }
}
