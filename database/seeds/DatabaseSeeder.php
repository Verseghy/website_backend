<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PostsSeeder::class);
        $this->call(EventsSeeder::class);
        $this->call(CanteensSeeder::class);
        $this->call(NewsletterSeeder::class);
        $this->call(UserSeeder::class);
    }
}
