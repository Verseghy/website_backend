<?php

use Illuminate\Database\Seeder;

use App\Models\Newsletter;

class NewsletterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Newsletter::class, 150)->create();
    }
}
