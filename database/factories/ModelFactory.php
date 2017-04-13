<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
   $g= $faker->randomElement(['male','famele']);

    return [
        'name' => $faker->name($g),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'in_cafe_id' => -1,
        'in_geo_longitude'=> $faker->randomFloat(5,18.9,19.3),
        'in_geo_latitude' => $faker->randomFloat(5,47.3,47.6),
        'birth_date' => $faker->date('Y-m-d','-18 years'),
        'sex'=>$g,

    ];
});
$factory->define(App\Cafe::class, function (Faker\Generator $faker) {
    

    return [
        'name' => $faker->company,
        'geo_longitude'=> $faker->randomFloat(5,18.9,19.3),
        'geo_latitude' => $faker->randomFloat(5,47.3,47.6),

    ];
});
$factory->define(App\Review::class, function (Faker\Generator $faker) {
    

    return [
        'title' => $faker->sentence,
        'body'=> $faker->paragraph,
        //'recommended' => $faker->randomElement(NULL,0,1),

    ];
});
$factory->define(App\Event::class, function (Faker\Generator $faker) {
   

    return [
        'title' => $faker->sentence,
        'body'=> $faker->paragraph,
        'cafe_id' => -1,
        'start'=>$faker->dateTimeBetween('now','+30 days'),
        'end'=>$faker->dateTimeBetween('+30 days','+60 days'),
        'type'=>$faker->randomElement(['A','B','C']),


        //'recommended' => $faker->randomElement(NULL,0,1),

    ];
});
