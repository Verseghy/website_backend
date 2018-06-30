<?php

use Faker\Generator as Faker;

use App\Models\Posts;
use App\Models\Posts\Authors;
use App\Models\Posts\Images;

$factory->define(Posts::class, function (Faker $faker) {
    return [
        'title'=>$faker->sentence(3,false),
        'description'=>$faker->sentence,
        'content'=>$faker->text,
        'author_id'=>Authors::inRandomOrder()->first()->id,
        'index_image'=>factory(Images::class)->create()->id,
        'date'=>$faker->dateTime,
        'type'=>$faker->numberBetween(0,2),
    ];
}); 
