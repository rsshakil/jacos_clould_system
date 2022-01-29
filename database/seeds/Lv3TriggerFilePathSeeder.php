<?php

use Illuminate\Database\Seeder;
use App\Models\LV3\lv3_trigger_file_path;

class Lv3TriggerFilePathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path_array=array(
            [
                'lv3_service_id'=>1,
                'check_folder_path'=>"C:\Users\ASUS\OneDrive\Desktop\JCS\Check Folder",
                'moved_folder_path'=>"C:\Users\ASUS\OneDrive\Desktop\JCS\Check Folder\done",
                'api_scenario'=>null,
                'api_folder_path'=>null,
                'path_execution_flag'=>1,
            ],
            [
                'lv3_service_id'=>2,
                'check_folder_path'=>null,
                'moved_folder_path'=>null,
                'api_scenario'=>18,
                // 'api_scenario'=>"https://jcs.dev.jacos.jp/api/get_shipment_file",
                // 'api_folder_path'=>"C:\Users\ASUS\OneDrive\Desktop\JCS\Shipment_File",
                'api_folder_path'=>"C:\Users\Administrator\Documents\jacos\jcs\data\shipment",
                'path_execution_flag'=>0,
            ]
        );
        lv3_trigger_file_path::insert($path_array);
    }
}
