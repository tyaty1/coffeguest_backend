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
   $g= $faker->randomElement(['male','femele']);

    return [
        'name' => $faker->name($g),
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'in_cafe_id' => -1,
        'in_geo_longitude'=> $faker->randomFloat(5,18.9,19.3),
        'in_geo_latitude' => $faker->randomFloat(5,47.3,47.6),
        'birth_date' => $faker->date('Y-m-d','-18 years'),

        'address' => $faker->address,


        'sex'=>$g, 

    ];
});
$factory->define(App\Cafe::class, function (Faker\Generator $faker) {
    
$city=$faker->city;
$street=$faker->streetAddress;
 $days=['monday','tuesday','wednesday','thursday','friday','saturnday','sunday'];


$f_cafe=[
        'name' => $faker->company,
        'email' => $faker->unique()->safeEmail,
        'website' =>$faker->unique()->domainName,
        'weekday_open'=> $faker->randomElement(['06:00:00','07:00:00','08:00:00','09:00:00']),
        'free_wifi'=> $faker->randomElement([0,1]),
        'free_parking'=> $faker->randomElement([0,1]),
        'tea_avaliable'=> $faker->randomElement([0,1]),
        'terrace_avaliable'=> $faker->randomElement([0,1]),
        'cake_avaliable'=> $faker->randomElement([0,1]),
        'cgf'=>$faker->randomElement([0,0,0,0,1]),
        

        'weekday_close'=> $faker->randomElement(['18:00:00','19:00:00','20:00:00','21:00:00']),
        'city'=>$city,
        'address'=>$street,
        'geo_longitude'=> $faker->randomFloat(5,18.9,19.3),
        'geo_latitude' => $faker->randomFloat(5,47.3,47.6),


    ];
    foreach ($days as $day) {
   if ($day=='saturnday'||$day=='sunday'){
        $f_cafe["{$day}_is_closed"]= $faker->randomElement([0,0,1]);
   }
   else{
            $f_cafe["{$day}_is_closed"]= $faker->randomElement([0,0,0,0,0,0,0,0,0,0,0,0,0,0,1]);
   }
   if (!$f_cafe["{$day}_is_closed"]) {
        
        $o=$faker->randomElement(['06:00:00','07:00:00','08:00:00','09:00:00','10:00:00','11:00:00']);
        $c=$faker->randomElement(['15:00:00','16:00:00','17:00:00','19:00:00','20:00:00','21:00:00']);
        if($o!=$f_cafe['weekday_open']&&random_int(0, 3)>1){
            $f_cafe["{$day}_open"]=$o;
        }
        if($c!=$f_cafe['weekday_close']){
            $f_cafe["{$day}_close"]=$c;
        }
}
}
    return  $f_cafe;
});
$factory->define(App\Review::class, function (Faker\Generator $faker) {
    

    return [
        'title' => $faker->sentence,
        'body'=> $faker->paragraph,
        'recommended' => $faker->numberBetween(1,5),

    ];
});
$factory->define(App\Image::class, function (Faker\Generator $faker) {
    

    return [
        'filepath' => $faker->imageUrl($width = 640, $height = 480),
        'is_external'=> 1,

    ];
});
$factory->define(App\Event::class, function (Faker\Generator $faker) {
   

    return [
        'title' => $faker->sentence,
        'body'=> $faker->paragraph,
        'cafe_id' => -1,
        'start'=> $faker->dateTimeBetween('now','+30 days'),
        'end'=> $faker->dateTimeBetween('+30 days','+60 days'),
        'type'=> $faker->randomElement(['A','B','C']),


        //'recommended' => $faker->randomElement(NULL,0,1),

    ];
});
