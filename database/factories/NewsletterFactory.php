<?php

use Faker\Generator as Faker;

use App\Models\Newsletter;

$factory->define(Newsletter::class, function (Faker $faker) {
    return [
        'email'=>$faker->email,
        'mldata' =>$faker->randomElements(array(1, 2, 3, 4, 5), $faker->numberBetween(2, 5)),
        'token' =>$faker->asciify('********************************'),
    ];
});
