<?php

use Illuminate\Database\Seeder;
use App\Models\CMN\cmn_pdf_platform_canvas;

class CmnPdfPlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_path=public_path('json_files/pdf_platform_json.json');
        $canvas_objects = file_get_contents($json_path);
        $canvas_array=array(
            [
                'byr_buyer_id' => 1,
                'canvas_name' => 'Test',
                'canvas_image' => "canvas_image_screenshoot_seeder.png",
                'canvas_bg_image' => "canvas_bg_image_seeder.png",
                'canvas_objects' =>$canvas_objects,
                'line_gap' => "35",
                'line_per_page' => "26",
            ],
        );
        $publicBgPath = public_path('backend/images/pdf_platform/Background/canvas_bg_image_seeder.png'); // BG Public Path
        $storageBgPath = storage_path('app/public/backend/images/canvas/pdf_platform/Background/canvas_bg_image_seeder.png'); // BG Storage Path

        $publicScPath = public_path('backend/images/pdf_platform/Canvas_screenshoot/canvas_image_screenshoot_seeder.png'); // Screenshoot Public Path
        $storageScPath = storage_path('app/public/backend/images/canvas/pdf_platform/Canvas_screenshoot/canvas_image_screenshoot_seeder.png'); // Screenshoot Storage Path

        if (\File::copy($publicBgPath , $storageBgPath)) {
            if (\File::copy($publicScPath , $storageScPath)) {
                cmn_pdf_platform_canvas::insert($canvas_array);
            }
        }
    }
}
