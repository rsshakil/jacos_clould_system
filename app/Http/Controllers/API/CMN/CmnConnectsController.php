<?php

namespace App\Http\Controllers\API\CMN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\API\AllUsedFunction;
use App\Models\ADM\User;
use App\Models\BYR\byr_buyer;
use App\Models\BYR\byr_order;
use App\Models\BYR\byr_order_item;
use App\Models\BYR\byr_shipment_item;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_connect;
use App\Models\CMN\cmn_pdf_canvas;
use App\Models\CMN\cmn_scenario;
use App\Models\CMN\cmn_tbl_col_setting;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\SLR\slr_seller;

use DB;

class CmnConnectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_cmn_connects_optional(Request $request){
        $invoicedayslist = $request->selected_item;
        $cmn_connect_id = $request->cmn_connect_id;
        $cmnConnectInfo = cmn_connect::where('cmn_connect_id',$cmn_connect_id)->first();;
        $customdata = array();
        foreach($invoicedayslist as $val){
            if($val['value']!=''){
                $customdata[]=$val['value'];
            }      
        }
        
        $optionNalValue =  \json_decode($cmnConnectInfo->optional);
        $optionNalValue->invoice->closing_date = $customdata;  
        $invoicedayslistjson = \json_encode($optionNalValue);
        cmn_connect::where('cmn_connect_id',$cmn_connect_id)->update(['optional'=>$invoicedayslistjson]);
        return response()->json(['success'=>1]);
    }

    public function get_allInvoiceJsonSetting_info(Request $request){
        $result = cmn_connect::where('cmn_connect_id',1)->first();
        $jsnresp = array();
        if($result->optional){
            $jsdecode = \json_decode($result->optional);
            foreach($jsdecode->invoice['closing_date'] as $key=>$inv){
                $jsnresp[]= array(
                    'id'=>$key,
                    'value'=>$inv
                );
            }
            return response()->json(['result'=>$jsnresp,'success'=>1]);
        }
        return response()->json(['result'=>$jsnresp,'success'=>0]);
    }

    public function get_partner_fax_list(Request $request){
        $adm_user_id=$request->adm_user_id;
        $slrInfo = cmn_companies_user::select('slr_sellers.slr_seller_id','cmn_companies_users.adm_user_id','cmn_companies_users.cmn_company_id')
        ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
        ->where('cmn_companies_users.adm_user_id', $adm_user_id)->first();
        $result = cmn_connect::where('byr_buyer_id',$request->byr_buyer_id)->where('slr_seller_id',$slrInfo->slr_seller_id)->get();
        $nwArray = array();
        if($result){
            foreach($result as $val){
                $nwArray[]=array(
                    'rows'=>$val,
                    'jsonRows'=>json_decode($val->optional),
                );
            }
        }
        return response()->json(['result'=>$nwArray]);
    }
    public function update_cmn_connects_optionalAllJson(Request $request){
        $postJson = $request->allJson;
        foreach($postJson as $row){
            $invoicedayslistjson = \json_encode($row['jsonRows']);

            cmn_connect::where('cmn_connect_id',$row['rows']['cmn_connect_id'])->update(['optional'=>$invoicedayslistjson]);
        }
       
        return response()->json(['success'=>1]);
    }
}
