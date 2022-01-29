<?php

use Illuminate\Database\Seeder;

class cmn_jobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\cmn_job::class, 3)->create();
        $cmn_job = array(
            [
                'cmn_connect_id'=>1,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>1,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>2,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>1,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>3,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>1,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>4,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>1,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>1,
                'class'=>'order',
                'vector'=>'from_jacos',
                'cmn_scenario_id'=>2,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>2,
                'class'=>'order',
                'vector'=>'from_jacos',
                'cmn_scenario_id'=>2,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>3,
                'class'=>'order',
                'vector'=>'from_jacos',
                'cmn_scenario_id'=>2,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>4,
                'class'=>'order',
                'vector'=>'from_jacos',
                'cmn_scenario_id'=>2,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>1,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>3,
                'is_active'=>1,
            ],[
                'cmn_connect_id'=>1,
                'class'=>'order',
                'vector'=>'to_jacos',
                'cmn_scenario_id'=>4,
                'is_active'=>1,
            ]
        );
        App\Models\CMN\cmn_job::insert($cmn_job);
    }
}
