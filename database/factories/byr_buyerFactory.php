<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BYR\byr_buyer;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(byr_buyer::class, function (Faker $faker) {
    return [
        // 'cmn_company_id' => factory(App\cmn_company::class),
        'cmn_company_id' => App\Models\CMN\cmn_company::all()->random()->cmn_company_id,
        'super_code' => "OUK",
        // 'adm_role_id' => factory(Spatie\Permission\Models\Role::class),
        'adm_role_id' => Spatie\Permission\Models\Role::all()->random()->id,
    ];
});
