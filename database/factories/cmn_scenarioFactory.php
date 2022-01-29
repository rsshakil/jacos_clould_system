<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CMN\cmn_scenario;
use Faker\Generator as Faker;

$factory->define(cmn_scenario::class, function (Faker $faker) {
    return [
        // 'byr_buyer_id' => factory(App\byr_buyer::class),
        'byr_buyer_id' => App\Models\BYR\byr_buyer::all()->random()->byr_buyer_id,
        'slr_seller_id' => App\Models\SLR\slr_seller::all()->random()->slr_seller_id,
        // 'slr_seller_id' => factory(App\slr_seller::class),
        'class' => $faker->randomElement(['order','shipment','invoice','payment','inventory','other']),
        'vector' => $faker->randomElement(['to_jacos','from_jacos','in_jacos','other']),
        'name' => $faker->randomElement(['BMS_ORDER','CREATE_VAN_FORMAT']),
        'description' => $faker->randomElement(['BiwareにてOrderファイルを受信','file download']),
        'file_path' => $faker->randomElement(['scenario/bms_order_start','scenario/create_van_format']),
        // 'file_path' => 'App\Scenarios\ouk_order_toj',
    ];
});
