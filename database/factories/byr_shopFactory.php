<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BYR\byr_shop;
use Faker\Generator as Faker;

$factory->define(byr_shop::class, function (Faker $faker) {
    return [
        'byr_buyer_id' => factory(App\Models\BYR\byr_buyer::class),
        'slr_seller_id' => factory(App\Models\SLR\slr_seller::class),
        'shop_code' => rand(1000, 20000),
        'shop_name' => $faker->name,
        'shop_name_kana' => $faker->word,
        'shop_postal_code' => rand(1089, 10299),
        'shop_address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'fax' => $faker->email,
        'email' => $faker->email,
        'delivery_cycle' => rand(0, 1),
    ];
});
