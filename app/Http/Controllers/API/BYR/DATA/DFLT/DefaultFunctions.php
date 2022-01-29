<?php

namespace App\Http\Controllers\API\BYR\DATA\DFLT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DefaultFunctions extends Controller
{
    public function getByrInfo($adm_user_id){
        return cmn_companies_user::select('bb.*')
        ->join('cmn_companies as cc','cc.cmn_company_id','=','cmn_companies_users.cmn_company_id')
        ->join('byr_buyers as bb','bb.cmn_company_id','=','cc.cmn_company_id')
        ->where('cmn_companies_users.adm_user_id',$adm_user_id)
        ->first();
    }

    public function downloadFileName($request, $file_type="csv", $file_header="受注")
    {
        Log::debug(__METHOD__.':start---');

        // Log::info($request);
        // $adm_user_id=$request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $file_name_info=byr_buyer::select('cmn_companies.company_name')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('byr_buyers.byr_buyer_id', $byr_buyer_id)
            ->first();
        // Log::info($file_name_info);
        $file_name = $file_header.'_'.$file_name_info->company_name.'_'.date('YmdHis').'.'.$file_type;
        // \Log::info($file_name);
        // $file_name = $file_name_info->super_code.'-'."shipment_".$file_name_info->super_code.'-'.$file_name_info->partner_code."-".$file_name_info->jcode.'_shipment_'.date('YmdHis').'.'.$file_type;
        Log::debug(__METHOD__.':end---');
        return $file_name;
    }
}
