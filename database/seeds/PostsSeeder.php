<?php

use Illuminate\Database\Seeder;

use App\Models\Posts;
use App\Models\Posts\Labels;
use App\Models\Posts\Authors;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Authors::class, 10)->create();
        factory(Labels::class, 20)->create();
        factory(Posts::class, 50)->create()->each(function ($post) {
            $labels = Labels::inRandomOrder()->take(rand(2, 7))->get();
            foreach ($labels as $label) {
                $post->labels()->attach($label);
            }
        });
	}
}
