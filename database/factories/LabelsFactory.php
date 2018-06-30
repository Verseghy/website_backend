<?php

use Faker\Generator as Faker;

use App\Models\Posts\Labels;

$factory->define(Labels::class, function (Faker $faker) {
    return [
        'name'=>$faker->word,
        'color'=>$faker->hexColor,
    ];
});
