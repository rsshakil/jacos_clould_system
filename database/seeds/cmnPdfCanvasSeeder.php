<?php

use Illuminate\Database\Seeder;
use App\Models\CMN\cmn_pdf_canvas;

class cmnPdfCanvasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json_path=public_path('backend/pdf_json/pdf_canvas_demo.json');
        $canvas_objects = $json = file_get_contents($json_path);
        $canvas_array=array(
            [
                'byr_buyer_id' => 1,
                'canvas_name' => 'Test',
                'canvas_image' => "canvas_image_screenshoot_seeder.png",
                'canvas_bg_image' => "canvas_bg_image_seeder.png",
                'canvas_objects' =>$canvas_objects,
            ],
        );
        cmn_pdf_canvas::insert($canvas_array);
    }
}
