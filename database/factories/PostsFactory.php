<?php

use Faker\Generator as Faker;

use App\Models\Posts;
use App\Models\Posts\Authors;
use App\Models\Posts\Images;
use App\Models\Posts\Labels;

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
        'image_id'=>factory(Images::class)->create()->id,
    ];
});



$factory->define(Posts::class, function (Faker $faker) {

    if(!function_exists('makeMLData'))
    {
        // helper function
        function makeMLData($faker)
        {
            // tweak these variables to configure this generator
            $NUM_CATEGORIES = 2;
            $MIN_VALUE = 0;
            $MAX_VALUE =5;
        
            $data = array();
            for ($i=0; $i<$NUM_CATEGORIES; $i++) {
                // generate random float with 3 digits precision
                array_push($data, $faker->randomFloat(3, $MIN_VALUE, $MAX_VALUE));
            }
        
            return json_encode($data);
        }
    }
    
    
    return [
        'title'=>$faker->sentence(3, false),
        'description'=>$faker->sentences($faker->numberBetween(1, 5), true),
        'content'=>$faker->paragraphs($faker->numberBetween(3, 7), true),
        'author_id'=>Authors::inRandomOrder()->first()->id,
        'index_image'=>factory(Images::class)->create()->id,
        'date'=>$faker->dateTime,
        'type'=>$faker->numberBetween(0, 2),
        'color'=>$faker->hexColor,
        'mldata'=>makeMLData($faker),
    ];
});
