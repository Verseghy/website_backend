<?php

use App\Models\Posts\Images;
use Faker\Generator as Faker;

$factory->define(Images::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
    ];
});
