<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CMN\cmn_connect;
use Faker\Generator as Faker;

$factory->define(cmn_connect::class, function (Faker $faker) {
    return [
        'byr_buyer_id' => factory(App\Models\BYR\byr_buyer::class),
        'byr_shop_id' => factory(App\Models\BYR\byr_shop::class),
        'slr_seller_id' => factory(App\Models\SLR\slr_seller::class),
        'slr_ware_house_id' => factory(App\Models\SLR\slr_ware_house::class),
        'partner_code' => rand(100, 1000),
        'is_active' => rand(0, 1),
    ];
});
