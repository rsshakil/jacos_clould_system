<?php

namespace App\Http\Controllers\API\PDF;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_scenario;
use App\Models\CMN\cmn_pdf_platform_canvas;
use App\Models\DATA\ORD\data_order;
use App\Http\Controllers\API\CMN\CmnScenarioController;

class PdfPlatformController extends Controller
{
    private $all_used_func;

    public function __construct(){
        $this->all_used_func = new AllUsedFunction();
    }
    public function pdfPlatformAllData(Request $request){
        $data_order_id=$request->data_order_id;
        $line_per_page=$request->line_per_page;
        $canvas_data=data_order::select('cmn_pdf_platform_canvas.*','data_orders.data_order_id')
        ->join('cmn_connects','cmn_connects.cmn_connect_id','=','data_orders.cmn_connect_id')
        ->join('cmn_pdf_platform_canvas','cmn_pdf_platform_canvas.byr_buyer_id','=','cmn_connects.byr_buyer_id')
        ->where('data_orders.data_order_id',$data_order_id)
        ->get();
        // $line_per_page=26;
        if (!empty(json_decode($canvas_data))) {
            $line_per_page=$canvas_data[0]->line_per_page;
        }
        $request->request->add(['line_per_page' => $line_per_page]);
        $cs = new CmnScenarioController();
        $ret = $cs->exec($request);
        return response()->json(['canvas_data'=>$canvas_data,'can_info'=>$ret['new_report_array']]);
    }
}
