<?php

namespace App\Http\Controllers\API\BYR\DATA\RTN;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Http\Request;
use App\Models\ADM\User;
use App\Models\BYR\byr_return;
use App\Models\DATA\RTN\data_return;
use App\Models\DATA\RTN\data_return_voucher;
use App\Models\DATA\RTN\data_return_item;
use App\Models\BYR\byr_buyer;
use App\Traits\Csv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\API\BYR\DATA\RTN\DataController;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.SLR_RETURN_DOWNLOAD_CSV_PATH'));
    }

    public function getReturnItemList($adm_user_id)
    {
        $authUser=User::find($adm_user_id);
        $cmn_company_id = 0;
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = $this->all_used_fun->get_user_info($adm_user_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
            $byr_buyer_id = $cmn_company_info['byr_buyer_id'];
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
            $result = byr_return::select('byr_returns.*', 'cmn_companies.company_name')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_returns.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->where('byr_returns.cmn_connect_id', $cmn_connect_id)->get();
        } else {
            $result = byr_return::select('byr_returns.*', 'cmn_companies.company_name')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_returns.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')->get();
        }

        $byr_buyer =$this->all_used_fun->get_company_list($cmn_company_id);


        return response()->json(['return_list' => $result,'byr_buyer_list'=>$byr_buyer]);
    }


    //reutn list
    public function returnList(Request $request)
    {
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;
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
        $major_category = $major_category['category_code']; // 印刷
        $table_name='data_return_vouchers.';
        if ($sort_by=="data_return_id" || $sort_by=="receive_datetime") {
            $table_name='dr.';
        }
        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        // 検索

        //union query
        $result=data_return_voucher::select(
            'dr.data_return_id',
            'dr.sta_doc_type',
            'dr.receive_datetime',
            'data_return_vouchers.deleted_at',
            'data_return_vouchers.mes_lis_ret_par_sel_code',
            'data_return_vouchers.mes_lis_ret_par_sel_name',
            'data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date',
            'data_return_vouchers.mes_lis_ret_tra_goo_major_category',
            'data_return_vouchers.check_datetime',
            DB::raw('COUNT(distinct data_return_vouchers.data_return_voucher_id) AS cnt'),
            'data_return_vouchers.data_return_voucher_id'
        )
        ->join('data_returns as dr', 'dr.data_return_id', '=', 'data_return_vouchers.data_return_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->withTrashed();
        // 条件指定検索
        $result =$result->whereBetween('dr.receive_datetime', [$receive_date_from, $receive_date_to])
        ->whereBetween('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date', [$ownership_date_from, $ownership_date_to]);
        if ($trade_number!=null) {
            $result =$result->where('data_return_vouchers.mes_lis_ret_tra_trade_number', $trade_number);
        }
        if ($major_category!='*') {
            $result =$result->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category', $major_category);
        }
        if ($request->sel_code!=null) {
            $result =$result->where('data_return_vouchers.mes_lis_ret_par_sel_code', $request->sel_code);
        }
        if ($sta_doc_type!='*') {
            $result =$result->where('dr.sta_doc_type', $sta_doc_type);
        }
        if ($check_datetime!='*') {
            if ($check_datetime==1) {
                $result= $result->whereNull('data_return_vouchers.check_datetime');
            } else {
                $result= $result->whereNotNull('data_return_vouchers.check_datetime');
            }
        }
        $result = $result->groupBy([
            'dr.receive_datetime',
            'data_return_vouchers.mes_lis_ret_par_sel_code',
            'data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date',
            'data_return_vouchers.mes_lis_ret_tra_goo_major_category'
        ])
        ->orderBy($table_name.$sort_by, $sort_type)
        ->orderBy('dr.receive_datetime','DESC')
        ->orderBy('data_return_vouchers.mes_lis_ret_par_sel_code')
        ->orderBy('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date')
        ->orderBy('data_return_vouchers.mes_lis_ret_tra_goo_major_category')
        ->paginate($per_page);
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['return_item_list' => $result, 'byr_buyer_list' => $byr_buyer]);
    }

    public function returnDetailList(Request $request)
    {
        // return $request->all();
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $data_return_id = $request->data_return_id;

        $sel_name = $request->par_sel_name;
        $sel_code = $request->sel_code;
        $major_category = $request->major_category;
        $ownership_date = $request->ownership_date;

        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;

        $decesion_status=$request->decesion_status;
        $voucher_class=$request->voucher_class;
        $goods_classification_code=$request->goods_classification_code;
        $trade_number=$request->trade_number;

        $table_name='data_return_vouchers.';

        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        /*receive order info for single row*/
        $orderInfo=data_return_voucher::join('data_returns as dr', 'dr.data_return_id', '=', 'data_return_vouchers.data_return_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('data_return_vouchers.data_return_id', '=', $data_return_id)
        ->groupBy('dr.sta_sen_identifier')
        ->groupBy('data_return_vouchers.mes_lis_ret_par_sel_code')
        ->groupBy('data_return_vouchers.mes_lis_ret_par_sel_name')->first();
        /*receive order info for single row*/
        // 検索
        $result=data_return_voucher::select(
            'data_return_vouchers.data_return_voucher_id',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_name',
            'data_return_vouchers.mes_lis_ret_par_return_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_from_name',
            'data_return_vouchers.mes_lis_ret_tra_trade_number',
            'data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code',
            'data_return_vouchers.mes_lis_ret_tot_tot_net_price_total',
            'dr.cmn_connect_id'
        )
        ->join('data_returns as dr', 'dr.data_return_id', '=', 'data_return_vouchers.data_return_id')
        ->join('data_return_items as dri', 'dri.data_return_voucher_id', '=', 'data_return_vouchers.data_return_voucher_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('dr.data_return_id', '=', $data_return_id)
        // ->where('data_return_vouchers.mes_lis_ret_par_sel_name',$sel_name)
        ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category', $major_category==null?'':$major_category)
        ->where('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date', $ownership_date)
        ->where('data_return_vouchers.mes_lis_ret_par_sel_code', $sel_code);
        if ($decesion_status!="*") {
            if ($decesion_status=="訂正あり") {
                $result = $result->where('data_return_vouchers.mes_lis_ret_tot_tot_net_price_total', '>', 0);
            }
            if ($decesion_status=="訂正なし") {
                $result = $result->where('data_return_vouchers.mes_lis_ret_tot_tot_net_price_total', 0);
            }
        }
        if ($request->searchCode1!='') {
            $result = $result->where('data_return_vouchers.mes_lis_ret_par_return_receive_from_code', $request->searchCode1);
        }
        if ($request->searchCode2!='') {
            $result = $result->where('data_return_vouchers.mes_lis_ret_par_return_from_code', $request->searchCode2);
        }
        if ($request->searchCode3!='') {
            $result = $result->where('dri.mes_lis_ret_lin_ite_order_item_code', $request->searchCode3);
        }
        if ($voucher_class!="*") {
            $result = $result->where('data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code', $voucher_class);
        }
        if ($goods_classification_code!="*") {
            $result = $result->where('data_return_vouchers.mes_lis_ret_tra_ins_goods_classification_code', $goods_classification_code);
        }
        if ($trade_number!=null) {
            $result = $result->where('data_return_vouchers.mes_lis_ret_tra_trade_number', $trade_number);
        }
        $result=$result->groupBy('data_return_vouchers.mes_lis_ret_tra_trade_number')
        ->orderBy($table_name.$sort_by, $sort_type)
        ->withTrashed()
        ->paginate($per_page);
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['retrun_detail_list' => $result, 'byr_buyer_list' => $byr_buyer,'order_info'=>$orderInfo]);
    }


    public function data_return_detail_list_pagination(Request $request)
    {
        // return $request->all();
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $data_return_id = $request->data_return_id;

        $sel_name = $request->par_sel_name;
        $sel_code = $request->sel_code;
        $major_category = $request->major_category;
        $ownership_date = $request->ownership_date;

        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $sort_by = 'data_return_voucher_id';
        $sort_type = 'ASC';

        $decesion_status=$request->decesion_status;
        $voucher_class=$request->voucher_class;
        $goods_classification_code=$request->goods_classification_code;
        $trade_number=$request->trade_number;

        $table_name='data_return_vouchers.';

        $authUser = User::find($adm_user_id);


        $result=data_return_voucher::select(
            'data_return_vouchers.data_return_voucher_id',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_name',
            'data_return_vouchers.mes_lis_ret_par_return_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_from_name',
            'data_return_vouchers.mes_lis_ret_tra_trade_number',
            'data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code',
            'data_return_vouchers.mes_lis_ret_tot_tot_net_price_total',
            'dr.cmn_connect_id'
        )
            ->join('data_returns as dr', 'dr.data_return_id', '=', 'data_return_vouchers.data_return_id')
            ->join('data_return_items as dri', 'dri.data_return_voucher_id', '=', 'data_return_vouchers.data_return_voucher_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->where('dr.data_return_id', '=', $data_return_id)
        // ->where('data_return_vouchers.mes_lis_ret_par_sel_name',$sel_name)
            ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category', $major_category==null?'':$major_category)
            ->where('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date', $ownership_date)
            ->where('data_return_vouchers.mes_lis_ret_par_sel_code', $sel_code);

            $result=$result->groupBy('data_return_vouchers.mes_lis_ret_tra_trade_number')
            ->orderBy($table_name.$sort_by, $sort_type)
            ->withTrashed()
            ->get();
            return response()->json(['retrun_detail_list_pagination' => $result]);
    }

    public function returnDownload(Request $request)
    {
        // return $request->all();
        //ownloadType=2 for Fixed length
        $data_return_id=$request->data_return_id?$request->data_return_id:1;
        $downloadType=$request->downloadType;
        $csv_data_count =0;
        if ($downloadType==1) {
            // CSV Download
            $new_file_name =$this->all_used_fun->downloadFileName($request, 'csv', '返品');
            //  self::returnFileName($data_return_id, 'csv');
            $download_file_url = Config::get('app.url')."storage/app".config('const.SLR_RETURN_DOWNLOAD_CSV_PATH')."/". $new_file_name;

            // get shipment data query
            $shipment_query = DataController::getRtnData($request);
            $csv_data_count = $shipment_query->count();
            $shipment_data = $shipment_query->get()->toArray();

            // CSV create
            Csv::create(
                config('const.SLR_RETURN_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                $shipment_data,
                DataController::rtnCsvHeading(),
                config('const.CSV_FILE_ENCODE')
            );
        }

        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    public function returnItemDetailList(Request $request)
    {
        // return $request->all();
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $data_return_voucher_id = $request->data_return_voucher_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }

        $result=data_return_item::select(
            'dr.receive_datetime',
            'drv.mes_lis_ret_par_sel_code',
            'drv.mes_lis_ret_par_sel_name',
            'drv.mes_lis_ret_tra_dat_transfer_of_ownership_date',
            'drv.mes_lis_ret_tra_goo_major_category',
            'drv.mes_lis_ret_par_return_receive_from_code',
            'drv.mes_lis_ret_par_return_receive_from_name',
            'drv.mes_lis_ret_par_return_from_code',
            'drv.mes_lis_ret_par_return_from_name',
            'drv.mes_lis_ret_tra_trade_number',
            'drv.mes_lis_ret_tra_fre_variable_measure_item_code',
            'drv.mes_lis_ret_tra_ins_trade_type_code',
            'drv.mes_lis_ret_tra_tax_tax_type_code',
            'drv.mes_lis_ret_tra_not_text',
            'drv.mes_lis_ret_tot_tot_net_price_total',
            'drv.mes_lis_ret_tot_tot_selling_price_total',
            'data_return_items.mes_lis_ret_lin_ite_order_item_code',
            'data_return_items.mes_lis_ret_lin_ite_gtin',
            'data_return_items.mes_lis_ret_lin_ite_name',
            'data_return_items.mes_lis_ret_lin_ite_ite_spec',
            'data_return_items.mes_lis_ret_lin_fre_field_name',
            'data_return_items.mes_lis_ret_lin_qua_quantity',
            'data_return_items.mes_lis_ret_lin_fre_return_weight',
            'data_return_items.mes_lis_ret_lin_amo_item_net_price',
            'data_return_items.mes_lis_ret_lin_amo_item_net_price_unit_price',
            'data_return_items.mes_lis_ret_lin_amo_item_selling_price',
            'data_return_items.mes_lis_ret_lin_amo_item_selling_price_unit_price'
        )
        ->join('data_return_vouchers as drv', 'drv.data_return_voucher_id', '=', 'data_return_items.data_return_voucher_id')
        ->join('data_returns as dr', 'dr.data_return_id', '=', 'drv.data_return_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('drv.data_return_voucher_id', '=', $data_return_voucher_id)
       // ->paginate($per_page);
        ->get();
        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        return response()->json(['return_item_detail_list' => $result, 'byr_buyer_list' => $byr_buyer, 'buyer_settings' => $buyer_settings->setting_information]);
    }

    public function get_return_customer_code_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $query = data_return_voucher::join('data_returns as dr', 'dr.data_return_id', '=', 'data_return_vouchers.data_return_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
            ->select(
                'data_return_vouchers.mes_lis_ret_par_sel_code',
                'data_return_vouchers.mes_lis_ret_par_sel_name',
                'data_return_vouchers.mes_lis_ret_par_pay_code',
                'data_return_vouchers.mes_lis_ret_par_pay_name'
            )
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->groupby('data_return_vouchers.mes_lis_ret_par_sel_code', 'data_return_vouchers.mes_lis_ret_par_pay_code');
        $result = $query->withTrashed()->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_customer_code_lists' => $result]);
    }

    public function get_voucher_detail_popup1_return(Request $request)
    {
        // return $request->all();
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_return_voucher::select('data_return_vouchers.mes_lis_ret_par_return_receive_from_code',
        'data_return_vouchers.mes_lis_ret_par_return_receive_from_name','data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code')
        ->join('data_returns as dr','dr.data_return_id','=','data_return_vouchers.data_return_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_return_id',$request->data_return_id)
        ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category',$request->major_category)
        ->groupBy('data_return_vouchers.mes_lis_ret_par_return_receive_from_code')
        ->orderBy('data_return_vouchers.mes_lis_ret_par_return_receive_from_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup2_return(Request $request)
    {
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_return_voucher::select('data_return_vouchers.mes_lis_ret_par_return_from_code',
        'data_return_vouchers.mes_lis_ret_par_return_from_name','data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code')
        ->join('data_returns as dr','dr.data_return_id','=','data_return_vouchers.data_return_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_return_id',$request->data_return_id)
        ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category',$request->major_category)
        ->groupBy('data_return_vouchers.mes_lis_ret_par_return_from_code')
        ->orderBy('data_return_vouchers.mes_lis_ret_par_return_from_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup3_return(Request $request)
    {
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_return_voucher::select('dri.mes_lis_ret_lin_ite_order_item_code',
        'dri.mes_lis_ret_lin_ite_name','dri.mes_lis_ret_lin_ite_ite_spec')
        ->join('data_returns as dr','dr.data_return_id','=','data_return_vouchers.data_return_id')
        ->join('data_return_items as dri','dri.data_return_voucher_id','=','data_return_vouchers.data_return_voucher_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','dr.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('dr.data_return_id',$request->data_return_id)
        ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category',$request->major_category)
        ->groupBy('dri.mes_lis_ret_lin_ite_order_item_code')
        ->orderBy('dri.mes_lis_ret_lin_ite_order_item_code')
        ->withTrashed()
        ->get();
        return response()->json(['popUpList' => $result]);
    }
    public function returnDeleteRetrive(Request $request){
        $data_return_id=$request->data_return_id;
        $mes_lis_ret_par_sel_code=$request->mes_lis_ret_par_sel_code;
        $mes_lis_ret_par_sel_name=$request->mes_lis_ret_par_sel_name;
        $mes_lis_ret_tra_dat_transfer_of_ownership_date=$request->mes_lis_ret_tra_dat_transfer_of_ownership_date;
        $mes_lis_ret_tra_goo_major_category=$request->mes_lis_ret_tra_goo_major_category;
        $data_return_voucher_id=$request->data_return_voucher_id;
        $receive_datetime=$request->receive_datetime;
        $deleted_at=$request->deleted_at;

        $return_voucher_var=data_return_voucher::where('data_return_id',$data_return_id)
        ->where('mes_lis_ret_par_sel_code',$mes_lis_ret_par_sel_code)
        ->where('mes_lis_ret_par_sel_name',$mes_lis_ret_par_sel_name)
        ->where('mes_lis_ret_tra_dat_transfer_of_ownership_date',$mes_lis_ret_tra_dat_transfer_of_ownership_date)
        ->where('mes_lis_ret_tra_goo_major_category',$mes_lis_ret_tra_goo_major_category);

        if ($deleted_at==null) {
            $return_voucher_var->delete();
        }else{
            $return_voucher_var->withTrashed()->restore();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'data_count' => $return_voucher_var->withTrashed()->count()]);
    }
}
