<?php

use Faker\Generator as Faker;
use App\Models\Canteens;
use App\Models\Canteens\Menus;

$factory->define(Menus::class, function (Faker $faker) {
    return [
        'menu' => $faker->word,
        'type' => $faker->numberBetween(0, 2),
    ];
});

$factory->define(Canteens::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime,
    ];
});
