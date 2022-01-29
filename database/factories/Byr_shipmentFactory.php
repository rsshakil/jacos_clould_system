<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BYR\byr_shipment;
use Faker\Generator as Faker;

$factory->define(byr_shipment::class, function (Faker $faker) {
    return [
        'byr_order_id' => factory(App\Models\BYR\byr_order::class),
        'cmn_connect_id' => factory(App\Models\CMN\cmn_connect::class),
        'category' => $faker->randomElement(['edi', 'manual']),
        'send_date' => now(),
        'send_file_path' => $faker->url,
        'data_count' => rand(1, 6),
    ];
});
