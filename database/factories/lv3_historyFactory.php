<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\LV3\lv3_history;
use Faker\Generator as Faker;

$factory->define(lv3_history::class, function (Faker $faker) {
    return [
        'lv3_service_id' => factory(App\Models\LV3\lv3_service::class),
        'execute_type' => $faker->randomElement(['自動','手動']),
        'execute_date' => now(),
        'status' => $faker->randomElement(['Success','Error','Pending']),
        'message' => $faker->sentence(5),
    ];
});
