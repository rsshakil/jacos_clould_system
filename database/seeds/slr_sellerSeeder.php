<?php

use Illuminate\Database\Seeder;

class slr_sellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $slr = array(
            // [
            //     'cmn_company_id'=>1,
            //     'adm_role_id'=>0,
            // ],
            [
                'cmn_company_id' => 2,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 3,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 4,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 5,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 7,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 8,
                'adm_role_id' => 0,
            ],
            [
                'cmn_company_id' => 9,
                'adm_role_id' => 0,
            ],

        );
        App\Models\SLR\slr_seller::insert($slr);
    }
}
