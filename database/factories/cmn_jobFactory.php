<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CMN\cmn_job;
use Faker\Generator as Faker;

$factory->define(cmn_job::class, function (Faker $faker) {
    return [
        'cmn_connect_id' => App\Models\CMN\cmn_connect::all()->random()->cmn_connect_id,
        'class' => $faker->randomElement(['order','shipment','invoice','payment','inventory','other']),
        'vector' => $faker->randomElement(['to_jacos','from_jacos','in_jacos','other']),
        'cmn_scenario_id' => App\Models\CMN\cmn_scenario::all()->random()->cmn_scenario_id,
        'is_active' => $faker->randomElement([0,1]),
    ];
});
