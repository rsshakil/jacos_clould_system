<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CMN\cmn_company;
use Faker\Generator as Faker;

$factory->define(cmn_company::class, function (Faker $faker) {
    return [
        // 'company_name' => $faker->name,
        // 'company_name_kana' => $faker->word,
        // 'jcode' => rand(1000, 20000),
        // 'phone' => $faker->phoneNumber,
        // 'fax' => $faker->phoneNumber,
        // 'postal_code' => rand(1089, 10299),
        // 'address' => $faker->address,
        'company_name' => "株式会社スーパーバリュー",
        'company_name_kana' => "ｶﾌﾞｼｷｶﾞｲｼｬ ｽｰﾊﾟｰﾊﾞﾘｭｰ",
        'jcode' => 1000010,
        'phone' => "03-1234-5678",
        'fax' => "03-1234-5678",
        'postal_code' => "362-0034",
        'address' => "埼玉県上尾市愛宕3-1-40",
    ];
});
