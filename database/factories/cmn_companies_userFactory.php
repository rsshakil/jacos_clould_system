<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CMN\cmn_companies_user;
use Faker\Generator as Faker;

$factory->define(cmn_companies_user::class, function (Faker $faker) {
    return [
        'cmn_company_id' => App\Models\CMN\cmn_company::all()->random()->cmn_company_id,
        'adm_user_id' => App\Models\ADM\User::all()->random()->id,
        // 'adm_user_id' => factory(App\User::class),
    ];
});
