<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SLR\slr_seller;
use Faker\Generator as Faker;

$factory->define(slr_seller::class, function (Faker $faker) {
    return [
        'cmn_company_id' => App\Models\CMN\cmn_company::all()->random()->cmn_company_id,
        'adm_role_id' => Spatie\Permission\Models\Role::all()->random()->id,
    ];
});
