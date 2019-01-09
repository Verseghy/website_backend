<?php

use Faker\Generator as Faker;
use App\Models\Events;

$factory->define(Events::class, function (Faker $faker) {
    $endDate = $faker->dateTime;
    $startDate = $faker->dateTime($endDate);

    return [
        'date_from' => $startDate,
        'date_to' => $endDate,
        'title' => $faker->sentence(3, false),
        'description' => $faker->sentence(3, false),
        'color' => $faker->hexColor,
    ];
});
