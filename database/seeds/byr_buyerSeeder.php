<?php

use Illuminate\Database\Seeder;

class byr_buyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $json_path=public_path('json_files/OUK/buyer_setting_information.json');
        $buyer_setting_data = file_get_contents($json_path);

        $byr_byr = array(
            [
                'cmn_company_id'=>1,
                'super_code'=>"OUK",
                'adm_role_id'=>5,
                'setting_information'=>$buyer_setting_data,
            ],
            [
                'cmn_company_id'=>6,
                'super_code'=>"NTN",
                'adm_role_id'=>6,
                'setting_information'=>$buyer_setting_data
            ],
        );
        App\Models\BYR\byr_buyer::insert($byr_byr);
    }
}
