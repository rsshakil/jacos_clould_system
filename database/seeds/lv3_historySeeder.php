<?php

use Illuminate\Database\Seeder;

class lv3_historySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\LV3\lv3_history::class, 3)->create();
    }
}
