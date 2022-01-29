<?php

namespace App\Http\Controllers\API\BYR\DATA\RECEIVE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\ADM\User;
use App\Models\DATA\RCV\data_receive;
use App\Models\DATA\RCV\data_receive_voucher;
use App\Models\DATA\RCV\data_receive_item;
use App\Models\BYR\byr_buyer;
use App\Models\BYR\byr_corrected_receive;
use App\Traits\Csv;
use App\Http\Controllers\API\BYR\DATA\RECEIVE\DataController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class ReceiveController extends Controller
{
    //orderReceiveList

    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.SLR_RECEIVE_DOWNLOAD_CSV_PATH'));
    }

    public function orderReceiveList(Request $request)
    {

        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $adm_user_id = $request->adm_user_id;
       // $byr_buyer_id = $request->byr_buyer_id;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;

        $receive_date_from = $request->receive_date_from; // 受信日時開始
        $receive_date_to = $request->receive_date_to; // 受信日時終了
        $ownership_date_from = $request->ownership_date_from; // 納品日開始
        $ownership_date_to = $request->ownership_date_to; // 納品日終了
        $sel_code = $request->sel_code;
        $delivery_service_code = $request->delivery_service_code; // 便
        $temperature_code = $request->temperature_code; // 配送温度区分
        $major_category = $request->major_category; // 配送温度区分
        $sta_doc_type = $request->sta_doc_type; // 配送温度区分
        $check_datetime = $request->check_datetime; // 配送温度区分
        $trade_number = $request->trade_number;

        $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $ownership_date_from = $ownership_date_from!=null? date('Y-m-d 00:00:00', strtotime($ownership_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $ownership_date_to = $ownership_date_to!=null? date('Y-m-d 23:59:59', strtotime($ownership_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $table_name='data_receive_vouchers.';
        if ($sort_by=="data_receive_id" || $sort_by=="receive_datetime") {
            $table_name='dr.';
        }


        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        // 検索
        $result=data_receive_voucher::select(
            'dr.data_receive_id',
            'dr.sta_doc_type',
            'dr.receive_datetime',
            'data_receive_vouchers.deleted_at',
            'data_receive_vouchers.mes_lis_acc_par_sel_code',
            'data_receive_vouchers.mes_lis_acc_par_sel_name',
            'data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_delivery_date',
            'data_receive_vouchers.mes_lis_acc_tra_goo_major_category',
            'data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code',
            'data_receive_vouchers.check_datetime',
            DB::raw('COUNT(distinct data_receive_vouchers.data_receive_voucher_id) AS cnt'),
            'data_receive_vouchers.data_receive_voucher_id'
        )
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id);
        $result =$result->whereBetween('dr.receive_datetime', [$receive_date_from, $receive_date_to])
        ->whereBetween('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date', [$ownership_date_from, $ownership_date_to]);
        if ($trade_number!=null) {
            $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_trade_number', $trade_number);
        }
        if ($sel_code) {
            $result =$result->where('data_receive_vouchers.mes_lis_acc_par_sel_code', $sel_code);
        }
        if ($delivery_service_code!='*') {
            $result =$result->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code', $delivery_service_code);
        }
        if ($temperature_code!='*') {
            $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code', $temperature_code);
        }
        if ($major_category!='*') {
            $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category', $major_category);
        }
        if ($sta_doc_type!='*') {
            $result =$result->where('dr.sta_doc_type', $sta_doc_type);
        }
        if ($check_datetime!='*') {
            if ($check_datetime==1) {
                $result= $result->whereNull('data_receive_vouchers.check_datetime');
            } else {
                $result= $result->whereNotNull('data_receive_vouchers.check_datetime');
            }
        }

        $result = $result->groupBy([
            'dr.receive_datetime',
            'data_receive_vouchers.mes_lis_acc_par_sel_code',
            'data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date',
            'data_receive_vouchers.mes_lis_acc_tra_goo_major_category',
            'data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code',
        // 'data_receive_vouchers.mes_lis_acc_tra_trade_number'
        ])
        ->orderBy($table_name.$sort_by, $sort_type)
        ->orderBy('dr.receive_datetime', 'DESC')
        ->orderBy('data_receive_vouchers.mes_lis_acc_par_sel_code')
        ->orderBy('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date')
        ->orderBy('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code')
        ->orderBy('data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code')
        ->orderBy('data_receive_vouchers.mes_lis_acc_tra_goo_major_category')
        ->withTrashed()
        ->paginate($per_page);
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['received_item_list' => $result, 'byr_buyer_list' => $byr_buyer]);
    }

    public function orderReceiveDetailList(Request $request)
    {
        $today=date('Y-m-d H:i:s');
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $data_receive_id = $request->data_receive_id;

        $sel_name = $request->par_sel_name;
        $sel_code = $request->sel_code;
        $major_category = $request->major_category;
        $delivery_service_code = $request->delivery_service_code;
        $ownership_date = $request->ownership_date;

        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;

        $decesion_status=$request->decesion_status;
        $confirm_status=$request->confirm_status;
        $voucher_class=$request->voucher_class;
        $goods_classification_code=$request->goods_classification_code;
        $trade_number=$request->trade_number;
        $table_name='data_receive_vouchers.';

        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        $cmn_connect_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }
        /*receive order info for single row*/
        $orderInfo=data_receive_voucher::join('data_receives as dr', 'dr.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->withTrashed()
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('data_receive_vouchers.data_receive_id', '=', $data_receive_id)
        ->groupBy('dr.sta_sen_identifier')
        ->groupBy('data_receive_vouchers.mes_lis_acc_par_sel_code')
        ->groupBy('data_receive_vouchers.mes_lis_acc_par_sel_name')->first();
        /*receive order info for single row*/
        // 検索
        $result=data_receive_voucher::select('data_receive_vouchers.*', 'dsv.mes_lis_shi_tra_dat_order_date', 'dsv.mes_lis_shi_tra_trade_number', 'dsv.mes_lis_shi_tot_tot_net_price_total', 'dr.cmn_connect_id')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
        ->join('data_receive_items', 'data_receive_items.data_receive_voucher_id', '=', 'data_receive_vouchers.data_receive_voucher_id')
        ->leftJoin('data_shipment_vouchers as dsv', 'dsv.mes_lis_shi_tra_trade_number', '=', 'data_receive_vouchers.mes_lis_acc_tra_trade_number')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('data_receive_vouchers.data_receive_id', '=', $data_receive_id)
        // ->where('data_receive_vouchers.mes_lis_acc_par_sel_name',$sel_name)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code', $sel_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category', $major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code', '=', $delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date', '=', $ownership_date);
        if ($decesion_status!="*") {
            if ($decesion_status=="訂正あり") {
                $result = $result->whereNotNull('data_receive_vouchers.update_datetime');
            }
            if ($decesion_status=="訂正なし") {
                $result = $result->whereNull('data_receive_vouchers.update_datetime');
            }
        }

        if($confirm_status!='*'){
            if ($confirm_status=="差分あり") {
                $result = $result->where('data_receive_vouchers.mes_lis_acc_tot_tot_net_price_total', '!=', 'dsv.mes_lis_shi_tot_tot_net_price_total');
            }
            if ($confirm_status=="差分なし") {
                $result = $result->where('data_receive_vouchers.mes_lis_acc_tot_tot_net_price_total', '=', 'dsv.mes_lis_shi_tot_tot_net_price_total');
            }
        }

        if ($request->searchCode1!='') {
            $result = $result->where('data_receive_vouchers.mes_lis_acc_par_shi_code', $request->searchCode1);
        }
        if ($request->searchCode2!='') {
            $result = $result->where('data_receive_vouchers.mes_lis_acc_par_rec_code', $request->searchCode2);
        }
        if ($request->searchCode3!='') {
            $result = $result->where('data_receive_items.mes_lis_acc_lin_ite_order_item_code', $request->searchCode3);
        }

        if ($voucher_class!="*") {
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_ins_trade_type_code', $voucher_class);
        }
        if ($goods_classification_code!="*") {
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_ins_goods_classification_code', $goods_classification_code);
        }
        if ($trade_number!=null) {
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_trade_number', $trade_number);
        }
        $result=$result->groupBy('data_receive_vouchers.mes_lis_acc_tra_trade_number')
        ->orderBy($table_name.$sort_by, $sort_type)
        ->withTrashed()
        ->paginate($per_page);

        // $result = new Paginator($result, 2);
        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['received_detail_list' => $result, 'byr_buyer_list' => $byr_buyer, 'buyer_settings' => $buyer_settings->setting_information,'order_info'=>$orderInfo]);
    }

    public function data_receive_detail_list_pagination(Request $request)
    {
        // return $request->all();
        $today=date('Y-m-d H:i:s');
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $data_receive_id = $request->data_receive_id;

        $sel_name = $request->par_sel_name;
        $sel_code = $request->sel_code;
        $major_category = $request->major_category;
        $delivery_service_code = $request->delivery_service_code;
        $ownership_date = $request->ownership_date;

        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $sort_by = 'data_receive_voucher_id';
        $sort_type = 'ASC';

        $decesion_status=$request->decesion_status;
        $voucher_class=$request->voucher_class;
        $goods_classification_code=$request->goods_classification_code;
        $trade_number=$request->trade_number;

        //$slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;

        $table_name='data_receive_vouchers.';
        $result=data_receive_voucher::select('data_receive_vouchers.*', 'dsv.mes_lis_shi_tra_dat_order_date', 'dsv.mes_lis_shi_tra_trade_number', 'dsv.mes_lis_shi_tot_tot_net_price_total', 'dr.cmn_connect_id')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
        ->join('data_receive_items', 'data_receive_items.data_receive_voucher_id', '=', 'data_receive_vouchers.data_receive_voucher_id')
        ->leftJoin('data_shipment_vouchers as dsv', 'dsv.mes_lis_shi_tra_trade_number', '=', 'data_receive_vouchers.mes_lis_acc_tra_trade_number')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('data_receive_vouchers.data_receive_id', '=', $data_receive_id)
        // ->where('data_receive_vouchers.mes_lis_acc_par_sel_name',$sel_name)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code', $sel_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category', $major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code', '=', $delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date', '=', $ownership_date);

        $result=$result->groupBy('data_receive_vouchers.mes_lis_acc_tra_trade_number')
        ->orderBy($table_name.$sort_by, $sort_type)->withTrashed()->get();
        return response()->json(['received_detail_list_single_pagination' => $result]);
    }

    public function receiveDownload(Request $request)
    {
        // return $request->all();
        //ownloadType=2 for Fixed length
        $data_receive_id=$request->data_receive_id?$request->data_receive_id:1;
        $downloadType=$request->downloadType;
        $csv_data_count =0;
        if ($downloadType==1) {
            // CSV Download
            $new_file_name =$this->all_used_fun->downloadFileName($request, 'csv', '受領');
            $download_file_url = Config::get('app.url')."storage/app".config('const.SLR_RECEIVE_DOWNLOAD_CSV_PATH')."/". $new_file_name;
            // get shipment data query
            $shipment_query = DataController::getReceiveData($request);
            $csv_data_count = $shipment_query->count();
            $shipment_data = $shipment_query->get()->toArray();
            // CSV create
            Csv::create(
                config('const.SLR_RECEIVE_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                $shipment_data,
                DataController::receiveCsvHeading(),
                config('const.CSV_FILE_ENCODE')
            );
        }

        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    // private static function receiveFileName($data_receive_id, $file_type="csv")
    // {
    //     $file_name_info=data_receive::select('cmn_connects.partner_code', 'byr_buyers.super_code', 'cmn_companies.jcode','cmn_companies.company_name')
    //         ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'data_receives.cmn_connect_id')
    //         ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
    //         ->join('slr_sellers', 'slr_sellers.slr_seller_id', '=', 'cmn_connects.slr_seller_id')
    //         ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'slr_sellers.cmn_company_id')
    //         ->where('data_receives.data_receive_id', $data_receive_id)
    //         ->first();
    //         $file_name = '受注'.'_'.$file_name_info->company_name.'_'.date('YmdHis').'.'.$file_type;
    //     // $file_name = $file_name_info->super_code.'-'."receive_".$file_name_info->super_code.'-'.$file_name_info->partner_code."-".$file_name_info->jcode.'_receive_'.date('YmdHis').'.'.$file_type;
    //     return $file_name;
    // }
    public function orderReceiveItemDetailList(Request $request)
    {
        // return $request->all();
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $data_receive_voucher_id = $request->data_receive_voucher_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $authUser = User::find($adm_user_id);
       // $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $cmn_company_id = '';
        $cmn_connect_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }


        /*receive order info for single row*/
        $orderInfo=data_receive_item::
        join('data_receive_vouchers as drv', 'drv.data_receive_voucher_id', '=', 'data_receive_items.data_receive_voucher_id')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'drv.data_receive_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
       // ->where('cc.slr_seller_id', $slr_seller_id)
        // ->where('dr.cmn_connect_id','=',$cmn_connect_id)
        ->where('drv.data_receive_voucher_id', '=', $data_receive_voucher_id)
        // ->groupBy('data_receives.receive_datetime')
        ->groupBy('dr.sta_sen_identifier')
        ->groupBy('drv.mes_lis_acc_par_sel_code')
        ->groupBy('drv.mes_lis_acc_par_sel_name')->first();
        /*receive order info for single row*/
        // 検索
        $result=data_receive_item::join('data_receive_vouchers as drv', 'drv.data_receive_voucher_id', '=', 'data_receive_items.data_receive_voucher_id')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'drv.data_receive_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
       // ->where('cc.slr_seller_id', $slr_seller_id)
        // ->where('dr.cmn_connect_id','=',$cmn_connect_id)
        ->where('drv.data_receive_voucher_id', '=', $data_receive_voucher_id)
        // ->groupBy('drv.data_receive_voucher_id')
        // ->groupBy('drv.mes_lis_acc_tra_trade_number')
       // ->paginate($per_page);
        ->get();

        // $result = new Paginator($result, 2);
        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['received_item_detail_list' => $result, 'byr_buyer_list' => $byr_buyer, 'buyer_settings' => $buyer_settings->setting_information,'order_info'=>$orderInfo]);
    }

    public function correctedReceiveList($adm_user_id)
    {
        $authUser=User::find($adm_user_id);
        $cmn_company_id = 0;
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = $this->all_used_fun->get_user_info($adm_user_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
            $result = byr_corrected_receive::select('byr_corrected_receives.*', 'cmn_companies.company_name')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_corrected_receives.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('byr_corrected_receives.cmn_connect_id', $cmn_connect_id)->get();
        } else {
            $result = byr_corrected_receive::select('byr_corrected_receives.*', 'cmn_companies.company_name')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_corrected_receives.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')->get();
        }

        $byr_buyer =$this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['corrected_list' => $result,'byr_buyer_list'=>$byr_buyer]);
    }

    public function get_receive_customer_code_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $query = data_receive_voucher::join('data_receives as dr', 'dr.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
            ->select(
                'data_receive_vouchers.mes_lis_acc_par_sel_code',
                'data_receive_vouchers.mes_lis_acc_par_sel_name',
                'data_receive_vouchers.mes_lis_acc_par_pay_code',
                'data_receive_vouchers.mes_lis_acc_par_pay_name'
            )
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->groupby('data_receive_vouchers.mes_lis_acc_par_sel_code', 'data_receive_vouchers.mes_lis_acc_par_pay_code');
        $result = $query->withTrashed()->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_customer_code_lists' => $result]);
    }

    public function get_voucher_detail_popup1_receive(Request $request)
    {
        //$cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result = data_receive_voucher::select('data_receive_vouchers.mes_lis_acc_par_shi_code','data_receive_vouchers.mes_lis_acc_par_shi_name','data_receive_vouchers.mes_lis_acc_log_del_route_code')
        ->join('data_receives as dr','dr.data_receive_id','=','data_receive_vouchers.data_receive_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_receive_id',$request->data_receive_id)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category',$request->major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code',$request->sel_code)
        ->groupBy('data_receive_vouchers.mes_lis_acc_par_shi_code')
        ->orderBy('data_receive_vouchers.mes_lis_acc_par_shi_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup2_receive(Request $request)
    {
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result = data_receive_voucher::select('data_receive_vouchers.mes_lis_acc_par_rec_code','data_receive_vouchers.mes_lis_acc_par_rec_name','data_receive_vouchers.mes_lis_acc_log_del_route_code')
        ->join('data_receives as dr','dr.data_receive_id','=','data_receive_vouchers.data_receive_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_receive_id',$request->data_receive_id)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category',$request->major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code',$request->sel_code)
        ->groupBy('data_receive_vouchers.mes_lis_acc_par_rec_code')
        ->orderBy('data_receive_vouchers.mes_lis_acc_par_rec_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup3_receive(Request $request)
    {
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_receive_voucher::select('dri.mes_lis_acc_lin_ite_order_item_code',
        'dri.mes_lis_acc_lin_ite_name','dri.mes_lis_acc_lin_ite_ite_spec')
        ->join('data_receives as dr','dr.data_receive_id','=','data_receive_vouchers.data_receive_id')
        ->join('data_receive_items as dri','dri.data_receive_voucher_id','=','data_receive_vouchers.data_receive_voucher_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_receive_id',$request->data_receive_id)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category',$request->major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code',$request->sel_code)
        ->groupBy('dri.mes_lis_acc_lin_ite_order_item_code')
        ->orderBy('dri.mes_lis_acc_lin_ite_order_item_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }
    public function receiveDeleteRetrive(Request $request){
        // return $request->all();
        $data_receive_id=$request->data_receive_id;
        $mes_lis_acc_log_del_delivery_service_code=$request->mes_lis_acc_log_del_delivery_service_code;
        $mes_lis_acc_par_sel_code=$request->mes_lis_acc_par_sel_code;
        $mes_lis_acc_par_sel_name=$request->mes_lis_acc_par_sel_name;
        $mes_lis_acc_tra_dat_delivery_date=$request->mes_lis_acc_tra_dat_delivery_date;
        $mes_lis_acc_tra_dat_transfer_of_ownership_date=$request->mes_lis_acc_tra_dat_transfer_of_ownership_date;
        $mes_lis_acc_tra_goo_major_category=$request->mes_lis_acc_tra_goo_major_category;
        $mes_lis_acc_tra_ins_temperature_code=$request->mes_lis_acc_tra_ins_temperature_code;
        $receive_datetime=$request->receive_datetime;
        $deleted_at=$request->deleted_at;

        $receive_voucher_var=data_receive_voucher::where('data_receive_id',$data_receive_id)
        ->where('mes_lis_acc_log_del_delivery_service_code',$mes_lis_acc_log_del_delivery_service_code)
        ->where('mes_lis_acc_par_sel_code',$mes_lis_acc_par_sel_code)
        ->where('mes_lis_acc_par_sel_name',$mes_lis_acc_par_sel_name)
        ->where('mes_lis_acc_tra_dat_delivery_date',$mes_lis_acc_tra_dat_delivery_date)
        ->where('mes_lis_acc_tra_dat_transfer_of_ownership_date',$mes_lis_acc_tra_dat_transfer_of_ownership_date)
        ->where('mes_lis_acc_tra_goo_major_category',$mes_lis_acc_tra_goo_major_category)
        ->where('mes_lis_acc_tra_ins_temperature_code',$mes_lis_acc_tra_ins_temperature_code);

        if ($deleted_at==null) {
            $receive_voucher_var->delete();
        }else{
            $receive_voucher_var->withTrashed()->restore();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'data_count' => $receive_voucher_var->withTrashed()->count()]);
    }
}
