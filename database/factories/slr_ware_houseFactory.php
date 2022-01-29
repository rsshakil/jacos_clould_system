<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SLR\slr_ware_house;
use Faker\Generator as Faker;

$factory->define(slr_ware_house::class, function (Faker $faker) {
    return [
        'slr_seller_id' => factory(App\Models\SLR\slr_seller::class),
        'sub_jcode' => rand(0, 20000),
        'ware_house_name' => $faker->word,
        'ware_house_code' => rand(1089, 10299),
        'postal_code' => rand(1089, 10299),
        'address' => $faker->address,
        'phone' => $faker->phoneNumber,
        'fax' => $faker->email,
        'email' => $faker->email,
    ];
});
