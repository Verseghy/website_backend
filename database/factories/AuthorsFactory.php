<?php

use Faker\Generator as Faker;
use App\Models\Posts\Authors;
use App\Models\Posts\Images;

$factory->define(Authors::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'description'=>$faker->sentence,
        'image_id'=>factory(Images::class)->create()->id,
    ];
});
