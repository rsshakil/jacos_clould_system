<?php

use Illuminate\Database\Seeder;
use App\Models\LV3\lv3_service;

class Lv3ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service_array=array(
            [
                'cmn_connect_id'=>3,
                'adm_user_id'=>1,
                'service_name'=>"Order",
            ],
            [
                'cmn_connect_id'=>3,
                'adm_user_id'=>1,
                'service_name'=>"Shipment",
            ],
        );
        lv3_service::insert($service_array);
    }
}
