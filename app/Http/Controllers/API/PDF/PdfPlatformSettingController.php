<?php

namespace App\Http\Controllers\API\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_pdf_platform_canvas;

class PdfPlatformSettingController extends Controller
{
    private $all_used_func;

    public function __construct(){
        $this->all_used_func = new AllUsedFunction();
    }
    public function canvasSettingData(){
        $all_buyer=byr_buyer::select('byr_buyers.byr_buyer_id','cmn_companies.company_name')
        ->join('cmn_companies','byr_buyers.cmn_company_id','=','cmn_companies.cmn_company_id')
        ->orderBy('byr_buyers.byr_buyer_id','ASC')
        ->get();
        $canvas_info = cmn_pdf_platform_canvas::select('cmn_pdf_platform_canvas.*','cmn_companies.company_name')
        ->join('byr_buyers','byr_buyers.byr_buyer_id','=','cmn_pdf_platform_canvas.byr_buyer_id')
        ->join('cmn_companies','cmn_companies.cmn_company_id','=','byr_buyers.cmn_company_id')
        ->orderBy('cmn_pdf_platform_canvas.updated_at','DESC')->get();
        $canvas_array=array();
        if (!empty($canvas_info)) {
            foreach ($canvas_info as $key => $canvas) {
                $tmp['cmn_pdf_platform_canvas_id']=$canvas->cmn_pdf_platform_canvas_id;
                $tmp['byr_buyer_id']=$canvas->byr_buyer_id;
                $tmp['company_name']=$canvas->company_name;
                $tmp['canvas_name']=$canvas->canvas_name;
                $tmp['canvas_image']=$canvas->canvas_image;
                $tmp['canvas_bg_image']=$canvas->canvas_bg_image;
                $tmp['canvas_objects']=\json_decode($canvas->canvas_objects);
                $tmp['line_gap']=$canvas->line_gap;
                $tmp['line_per_page']=$canvas->line_per_page;
                $tmp['created_at']=$canvas->created_at;
                $tmp['updated_at']=$canvas->updated_at;
                $canvas_array[]=$tmp;
            }
        }
        return response()->json(['canvas_info'=>$canvas_array,'all_buyer'=>$all_buyer]);
    }
    public function canvasDataSave(Request $request){
        $canvas_id = $request->canvas_id;
        $update_image_info = $request->update_image_info;
        $byr_id = $request->byr_id;
        $canvas_name = $request->canvas_name;
        $base64_canvas_image = $request->canvasImage;
        $canData = $request->canData;
        $line_gap = $request->line_gap;
        $line_per_page = $request->line_per_page;

        $canvas_array = array(
            'byr_buyer_id' => $byr_id,
            'canvas_name' => $canvas_name,
            'canvas_objects' => json_encode($canData),
            'line_gap' => $line_gap,
            'line_per_page' => $line_per_page,
        );
        $file_path = \storage_path() . '/app/public/backend/images/canvas/pdf_platform/';
        if (!empty($canvas_id)) {
            $can_exist=cmn_pdf_platform_canvas::where('cmn_pdf_platform_canvas_id', $canvas_id)->first();
            if ($can_exist['byr_buyer_id']!=$byr_id) {
                if (cmn_pdf_platform_canvas::where('byr_buyer_id', $byr_id)->exists()) {
                    return response()->json(['message' =>'duplicated', 'class_name' => 'error','title'=>'Not Updated!']);
                }
            }
            // ....
            if (array_key_exists("backgroundImage",$canData)) {
                $canvasRawBgImg = $canData['backgroundImage']['src'];
                if ($this->all_used_func->itsBase64($canvasRawBgImg)==1) {
                    // "Base";
                    $canvasBgImg = $this->all_used_func->save_base64_image($canvasRawBgImg, 'canvas_bg_image_'. time().'_'.$byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/pdf_platform/Background/");# code...
                }else{
                    // "Non Base";
                    $canvasBgImgTmp = explode('/', $canvasRawBgImg);
                    $canvasBgImg = $canvasBgImgTmp[count($canvasBgImgTmp) - 1];
                }
            }else{
                $canvasBgImg="";
            }

            $canvas_image = $this->all_used_func->save_base64_image($base64_canvas_image, 'canvas_image_'. time().'_'.$byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/pdf_platform/Canvas_screenshoot/");
            $canvas_image_info = cmn_pdf_platform_canvas::select('canvas_image','canvas_bg_image')->where('cmn_pdf_platform_canvas_id', $canvas_id)->first();
            if (file_exists($file_path .'Canvas_screenshoot/'. $canvas_image_info['canvas_image'])) {
                @unlink($file_path .'Canvas_screenshoot/'. $canvas_image_info['canvas_image']);
            }
                if ($canvas_image_info['canvas_bg_image']!="bg_image.png") {
                    if (file_exists($file_path.'Background/' . $canvas_image_info['canvas_bg_image'])) {
                        @unlink($file_path.'Background/' . $canvas_image_info['canvas_bg_image']);
                    }
                }
            $canvas_array['canvas_image']=$canvas_image;
            $canvas_array['canvas_bg_image']=$canvasBgImg;
            cmn_pdf_platform_canvas::where('cmn_pdf_platform_canvas_id', $canvas_id)->update($canvas_array);
            return response()->json(['message' =>'updated', 'class_name' => 'success','title'=>'Updated!']);
        } else {
            if (!(cmn_pdf_platform_canvas::where('byr_buyer_id', $byr_id)->exists())) {
                if (array_key_exists("backgroundImage",$canData)) {
                    $canvasRawBgImg = $canData['backgroundImage']['src'];
                    if ($this->all_used_func->itsBase64($canvasRawBgImg)==1) {
                        // "Base";
                        $canvasBgImg = $this->all_used_func->save_base64_image($canvasRawBgImg, 'canvas_bg_image_'. time().'_'.$byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/pdf_platform/Background/");# code...
                    }else{
                        // "Non Base";
                        $canvasBgImgTmp = explode('/', $canvasRawBgImg);
                        $canvasBgImg = $canvasBgImgTmp[count($canvasBgImgTmp) - 1];
                    }
                }else{
                    $canvasBgImg="";
                }

                $canvas_image = $this->all_used_func->save_base64_image($base64_canvas_image, 'canvas_image_'. time().'_'.$byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/pdf_platform/Canvas_screenshoot/");
                $canvas_array['canvas_image']=$canvas_image;
                $canvas_array['canvas_bg_image']=$canvasBgImg;
                cmn_pdf_platform_canvas::insert($canvas_array);
                return response()->json(['message' =>'created', 'class_name' => 'success','title'=>'Created!']);
            }else{
                return response()->json(['message' =>'duplicated', 'class_name' => 'error','title'=>'Not Created!']);
            }
        }
    }
    public function deleteCanvasData(Request $request){
        $canvas_id=$request->cmn_pdf_canvas_id;
        $canvas_image_info = cmn_pdf_platform_canvas::select('canvas_image','canvas_bg_image')->where('cmn_pdf_platform_canvas_id', $canvas_id)->first();
            $file_path = storage_path() . '/app/public/backend/images/canvas/pdf_platform/';
            if (file_exists($file_path .'Canvas_screenshoot/'. $canvas_image_info['canvas_image'])) {
                @unlink($file_path .'Canvas_screenshoot/'. $canvas_image_info['canvas_image']);
            }
            if ($canvas_image_info['canvas_bg_image']!="bg_image.png") {
                if (file_exists($file_path .'Background/'. $canvas_image_info['canvas_bg_image'])) {
                    @unlink($file_path .'Background/'. $canvas_image_info['canvas_bg_image']);
                }
            }
            $canvas_del=cmn_pdf_platform_canvas::where('cmn_pdf_platform_canvas_id', $canvas_id)->delete();
            if ($canvas_del) {
                return response()->json(['message' =>'success', 'class_name' => 'success','title'=>'Deleted!']);
            }else{
                return response()->json(['message' =>'faild', 'class_name' => 'error','title'=>'Not Deleted!']);
            }

    }
}
