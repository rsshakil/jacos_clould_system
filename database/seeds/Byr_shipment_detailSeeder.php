<?php

use Illuminate\Database\Seeder;

class byr_shipment_detailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\BYR\byr_shipment_detail::class, 3)->create();
    }
}
