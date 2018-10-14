<?php

use Faker\Generator as Faker;
use App\Models\Colleagues;

$factory->define(Colleagues::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'jobs'=>$faker->sentences($faker->numberBetween(1, 3), true),
        'subjects'=>$faker->sentences($faker->numberBetween(1, 1), true),
        'roles'=>$faker->sentences($faker->numberBetween(1, 3), true),
        'awards'=>$faker->sentences($faker->numberBetween(1, 3), true),
        'category'=>$faker->numberBetween(0, 5),
    ];
});
