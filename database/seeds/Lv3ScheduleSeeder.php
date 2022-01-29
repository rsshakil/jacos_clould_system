<?php

use Illuminate\Database\Seeder;
use App\Models\LV3\lv3_trigger_schedule;

class Lv3ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedule_array=array(
            [
                'lv3_service_id' => 1,
                'day' => null,
                'weekday' => 45,
                'time' => '01:00:00',
                'last_day' => 0,
                'disabled' => 0,
            ],
            [
                'lv3_service_id' => 1,
                'day' => null,
                'weekday' => 82,
                'time' => '00:00:00',
                'last_day' => 0,
                'disabled' => 0,
            ],
            [
                'lv3_service_id' => 1,
                'day' => 0,
                'weekday' => 0,
                'time' => '14:00:00',
                'last_day' => 1,
                'disabled' => 0,
            ],
            [
                'lv3_service_id' => 1,
                'day' => 14,
                'weekday' => 0,
                'time' => '15:00:00',
                'last_day' => 0,
                'disabled' => 0,
            ]
        );
        lv3_trigger_schedule::insert($schedule_array);
    }
}
