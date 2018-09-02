<?php

use Faker\Generator as Faker;

use App\Models\Posts;
use App\Models\Posts\Authors;
use App\Models\Posts\Images;
use App\Models\Posts\Labels;


$factory->define(Images::class, function (Faker $faker) use ($factory) {
    return [
        'url'=>$faker->imageUrl,
        'post_id'=>App\Models\Posts::inRandomOrder()->first()->id,
    ];
});



$factory->define(Labels::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'color'=>$faker->hexColor,
    ];
});



$factory->define(Authors::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'description'=>$faker->sentence,
        'image'=>$faker->imageUrl,
    ];
});



$factory->define(Posts::class, function (Faker $faker) {
    return [
        'title'=>$faker->sentence(3, false),
        'description'=>$faker->sentences($faker->numberBetween(1, 5), true),
        'content'=>$faker->paragraphs($faker->numberBetween(3, 7), true),
        'author_id'=>Authors::inRandomOrder()->first()->id,
        'index_image'=>$faker->imageUrl,
        'date'=>$faker->dateTime,
        'type'=>$faker->numberBetween(0, 2),
        'color'=>$faker->hexColor,
    ];
});
