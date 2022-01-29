<?php

namespace App\Http\Controllers\API\CANVAS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMN\cmn_scenario;
use App\Models\BYR\byr_order;
use App\Models\CMN\cmn_pdf_canvas;
use App\Models\BYR\byr_buyer;
use Illuminate\Support\Facades\Log;

class CanvasController extends Controller
{
    public function canvasAllData(Request $request)
    {
        $cmn_scenario_id = $request->cmn_scenario_id;
        $byr_order_id = $request->byr_order_id;
        $sc = cmn_scenario::where('cmn_scenario_id', $cmn_scenario_id)->first();
        // scenario call
        if (!file_exists(app_path() . '/' . $sc->file_path . '.php')) {
            Log::error('Scenario file is not exist!:' . $sc->file_path);
            return ['status' => '1', 'message' => 'Scenario file is not exist!' . $sc->file_path];
        }
        // ファイル読み込み
        $customClassPath = "\\App\\";
        $nw_f_pth = explode('/', $sc->file_path);
        foreach ($nw_f_pth as $p) {
            $customClassPath .= $p . '\\';
        }
        $customClassPath = rtrim($customClassPath, "\\");
        $sc_obj = new $customClassPath;
        if (!method_exists($sc_obj, 'exec')) {
            Log::error('scenario exec error');
            return ['status' => '1', 'message' => 'Scenario exec function is not exist!'];
        }
        $ret = $sc_obj->exec($request, $sc);
        $canvas_data = byr_order::select('cmn_pdf_canvas.*', 'byr_orders.byr_order_id')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_orders.cmn_connect_id')
            ->join('cmn_pdf_canvas', 'cmn_pdf_canvas.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->where('byr_orders.byr_order_id', $byr_order_id)
            ->get();
        return response()->json(['canvas_data' => $canvas_data, 'can_info' => $ret]);
    }
    public function deleteCanvasData(Request $request)
    {
        $canvas_id = $request->cmn_pdf_canvas_id;
        $canvas_image_info = cmn_pdf_canvas::select('canvas_image', 'canvas_bg_image')->where('cmn_pdf_canvas_id', $canvas_id)->first();
        $file_path = storage_path() . '/app/public/backend/images/canvas/';
        Log::info('file_name_new=' . $file_path . 'Canvas_screenshoot/' . $canvas_image_info['canvas_image']);
        if (file_exists($file_path . 'Canvas_screenshoot/' . $canvas_image_info['canvas_image'])) {
            @unlink($file_path . 'Canvas_screenshoot/' . $canvas_image_info['canvas_image']);
        }
        if ($canvas_image_info['canvas_bg_image'] != "bg_image.jpg") {
            if (file_exists($file_path . 'Background/' . $canvas_image_info['canvas_bg_image'])) {
                @unlink($file_path . 'Background/' . $canvas_image_info['canvas_bg_image']);
            }
        }
        $canvas_del = cmn_pdf_canvas::where('cmn_pdf_canvas_id', $canvas_id)->delete();
        if ($canvas_del) {
            return response()->json(['message' => 'success', 'class_name' => 'success', 'title' => 'Deleted!']);
        } else {
            return response()->json(['message' => 'faild', 'class_name' => 'error', 'title' => 'Not Deleted!']);
        }
    }
    public function canvasSettingData()
    {
        $all_buyer = byr_buyer::select('byr_buyers.byr_buyer_id', 'cmn_companies.company_name')
            ->join('cmn_companies', 'byr_buyers.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->orderBy('byr_buyers.byr_buyer_id', 'ASC')
            ->get();
        $canvas_info = cmn_pdf_canvas::select('cmn_pdf_canvas.*', 'cmn_companies.company_name')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_pdf_canvas.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->orderBy('cmn_pdf_canvas.updated_at', 'DESC')->get();
        $canvas_array = array();
        if (!empty($canvas_info)) {
            foreach ($canvas_info as $key => $canvas) {
                $tmp['cmn_pdf_canvas_id'] = $canvas->cmn_pdf_canvas_id;
                $tmp['byr_buyer_id'] = $canvas->byr_buyer_id;
                $tmp['company_name'] = $canvas->company_name;
                $tmp['canvas_name'] = $canvas->canvas_name;
                $tmp['canvas_image'] = $canvas->canvas_image;
                $tmp['canvas_bg_image'] = $canvas->canvas_bg_image;
                $tmp['canvas_objects'] = \json_decode($canvas->canvas_objects);
                $tmp['created_at'] = $canvas->created_at;
                $tmp['updated_at'] = $canvas->updated_at;
                $canvas_array[] = $tmp;
            }
        }
        return response()->json(['canvas_info' => $canvas_array, 'all_buyer' => $all_buyer]);
    }
    public function canvasDataSave(Request $request)
    {
        $canvas_id = $request->canvas_id;
        $update_image_info = $request->update_image_info;
        $byr_id = $request->byr_id;
        $canvas_name = $request->canvas_name;
        $base64_canvas_image = $request->canvasImage;
        $canData = $request->canData;
        $canvasRawBgImg = $canData['backgroundImage']['src'];
        $canvas_array = array(
            'byr_buyer_id' => $byr_id,
            'canvas_name' => $canvas_name,
            'canvas_objects' => json_encode($canData),
        );
        if (!empty($canvas_id)) {
            $can_exist = cmn_pdf_canvas::where('cmn_pdf_canvas_id', $canvas_id)->first();
            if ($can_exist['byr_buyer_id'] != $byr_id) {
                if (cmn_pdf_canvas::where('byr_buyer_id', $byr_id)->exists()) {
                    return response()->json(['message' => 'duplicated', 'class_name' => 'error', 'title' => 'Not Updated!']);
                }
            }

            $canvas_image_info = cmn_pdf_canvas::select('canvas_image', 'canvas_bg_image')->where('cmn_pdf_canvas_id', $canvas_id)->first();
            $file_path = \storage_path() . '/app/public/backend/images/canvas/';
            if ($canvas_image_info['canvas_image'] != "canvas_image_screenshoot_seeder.png") {
                if (file_exists($file_path . 'Canvas_screenshoot/' . $canvas_image_info['canvas_image'])) {
                    @unlink($file_path . 'Canvas_screenshoot/' . $canvas_image_info['canvas_image']);
                }
            }
            $canvas_image = $this->all_used_fun->save_base64_image($base64_canvas_image, 'canvas_image_' . time() . '_' . $byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/Canvas_screenshoot/");

            if (!empty($update_image_info)) {
                if ($canvas_image_info['canvas_bg_image'] != "bg_image.jpg" || $canvas_image_info['canvas_bg_image'] != "canvas_bg_image_seeder.png") {
                    if (file_exists($file_path . 'Background/' . $canvas_image_info['canvas_bg_image'])) {
                        @unlink($file_path . 'Background/' . $canvas_image_info['canvas_bg_image']);
                    }
                }
                $canvasBgImg = $this->all_used_fun->save_base64_image($canvasRawBgImg, 'canvas_bg_image_' . time() . '_' . $byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/Background/");
            } else {
                $canvasBgImgTmp = explode('/', $canvasRawBgImg);
                $canvasBgImg = $canvasBgImgTmp[count($canvasBgImgTmp) - 1];
            }
            $canvas_array['canvas_image'] = $canvas_image;
            $canvas_array['canvas_bg_image'] = $canvasBgImg;
            cmn_pdf_canvas::where('cmn_pdf_canvas_id', $canvas_id)->update($canvas_array);
            return response()->json(['message' => 'updated', 'class_name' => 'success', 'title' => 'Updated!']);
        } else {
            if (!(cmn_pdf_canvas::where('byr_buyer_id', $byr_id)->exists())) {
                if (!empty($update_image_info)) {
                    $canvasBgImg = $this->all_used_fun->save_base64_image($canvasRawBgImg, 'canvas_bg_image_' . time() . '_' . $byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/Background/");
                } else {
                    $canvasBgImgTmp = explode('/', $canvasRawBgImg);
                    $canvasBgImg = $canvasBgImgTmp[count($canvasBgImgTmp) - 1];
                }
                $canvas_image = $this->all_used_fun->save_base64_image($base64_canvas_image, 'canvas_image_' . time() . '_' . $byr_id, $path_with_end_slash = "storage/app/public/backend/images/canvas/Canvas_screenshoot/");
                $canvas_array['canvas_image'] = $canvas_image;
                $canvas_array['canvas_bg_image'] = $canvasBgImg;
                cmn_pdf_canvas::insert($canvas_array);
                return response()->json(['message' => 'created', 'class_name' => 'success', 'title' => 'Created!']);
            } else {
                return response()->json(['message' => 'duplicated', 'class_name' => 'error', 'title' => 'Not Created!']);
            }
        }
    }
}
