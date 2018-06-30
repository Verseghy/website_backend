<?php

use App\Models\Posts\Images;
use Faker\Generator as Faker;

$factory->define(Images::class, function (Faker $faker) {
    return [
        'url' => $faker->imageUrl,
    ];
});

$factory->defineAs(Images::class, 'postImage', function (Faker $faker) use ($factory) {
    $img = $factory->raw('App\Models\Posts\Images');
    return array_merge($img, [
        'post_id'=>App\Models\Posts::inRandomOrder()->first()->id,
    ]);
});
