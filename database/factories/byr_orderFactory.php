<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BYR\byr_order;
use Faker\Generator as Faker;

$factory->define(byr_order::class, function (Faker $faker) {
    return [
        'cmn_connect_id' => factory(App\Models\CMN\cmn_connect::class),
        'category' => $faker->randomElement(['edi', 'manual']),
        'status' => $faker->randomElement(['未確定', '確定済み', '未出荷', '出荷中', '出荷済み']),
        'receive_date' => now(),
        'receive_file_path' => $faker->url,
        'data_count' => rand(1, 6),
    ];
});
