<?php

namespace App\Http\Controllers\API\DATA\ORDER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_connect;
use Illuminate\Support\Facades\DB;
use App\Models\ADM\User;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\CMN\cmn_tbl_col_setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        Log::debug(__METHOD__.':start---');
        $this->all_used_fun = new AllUsedFunction();
        Log::debug(__METHOD__.':end---');
    }
    public function get_order_customer_code_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $byr_buyer_id = $request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $query = data_order_voucher::join('data_orders as dor', 'dor.data_order_id', '=', 'data_order_vouchers.data_order_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dor.cmn_connect_id')
            ->select(
                'data_order_vouchers.mes_lis_ord_par_sel_code',
                'data_order_vouchers.mes_lis_ord_par_sel_name',
                'data_order_vouchers.mes_lis_ord_par_pay_code',
                'data_order_vouchers.mes_lis_ord_par_pay_name'
            )
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->where('cc.slr_seller_id', $slr_seller_id)
            ->groupby('data_order_vouchers.mes_lis_ord_par_sel_code', 'data_order_vouchers.mes_lis_ord_par_pay_code');
        $result = $query->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_customer_code_lists' => $result]);
    }

    /**
     * orderList
     *
     * @param  mixed $request
     * @return void
     */
    public function orderList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $per_page = $request->per_page?$request->per_page:10;

        $authUser = User::find($adm_user_id);
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;

        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;

        $table_name='dor.';
        if ($sort_by=="data_order_id" || $sort_by=="receive_datetime") {
            $table_name='dor.';
        } elseif ($sort_by=="decision_datetime" || $sort_by=="print_datetime") {
            $table_name='dsv.';
        } else {
            $table_name='data_order_vouchers.';
        }
        $result = data_order_voucher::select(
            'dor.data_order_id',
            'dor.receive_datetime',
            'data_order_vouchers.mes_lis_ord_par_sel_code',
            'data_order_vouchers.mes_lis_ord_par_sel_name',
            'data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver',
            'data_order_vouchers.mes_lis_ord_tra_goo_major_category',
            'data_order_vouchers.mes_lis_ord_log_del_delivery_service_code',
            'data_order_vouchers.mes_lis_ord_tra_ins_temperature_code',
            DB::raw('COUNT(distinct data_order_vouchers.data_order_voucher_id) AS cnt'),
            DB::raw('COUNT( isnull( dsv.decision_datetime) OR NULL) AS decision_cnt'),
            DB::raw('COUNT( isnull( dsv.send_datetime)  OR NULL) AS send_cnt'),
            'data_order_vouchers.check_datetime'
        )
        ->join('data_orders AS dor', 'dor.data_order_id', '=', 'data_order_vouchers.data_order_id')
        ->join('data_shipment_vouchers AS dsv', 'dsv.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dor.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id);
        // 条件指定検索
        $receive_date_from = $request->receive_date_from;
        $receive_date_to = $request->receive_date_to;
        $delivery_date_from = $request->delivery_date_from;
        $delivery_date_to = $request->delivery_date_to;
        $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $delivery_date_from = $delivery_date_from!=null? date('Y-m-d 00:00:00', strtotime($delivery_date_from)):config('const.FROM_DATETIME'); // 納品日開始
        $delivery_date_to =$delivery_date_to!=null? date('Y-m-d 23:59:59', strtotime($delivery_date_to)):config('const.TO_DATETIME'); // 納品日終了
        $delivery_service_code = $request->delivery_service_code; // 便
        $temperature = $request->temperature; // 配送温度区分
        $check_datetime = $request->check_datetime;
        $decission_cnt = $request->decission_cnt; // 確定
        $send_cnt = $request->send_cnt; // 印刷
        $byr_category_code = $request->category_code; // 印刷
        $mes_lis_ord_par_sel_code = $request->mes_lis_ord_par_sel_code; // 印刷
        $byr_category_code = $byr_category_code['category_code'];
        $trade_number = $request->trade_number;

        $result= $result->whereBetween('dor.receive_datetime', [$receive_date_from, $receive_date_to])
        ->whereBetween('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver', [$delivery_date_from, $delivery_date_to]);

        if ($trade_number!=null) {
            $result= $result->where('data_order_vouchers.mes_lis_ord_tra_trade_number', $trade_number);
        }
        if ($mes_lis_ord_par_sel_code!='') {
            $result= $result->where('data_order_vouchers.mes_lis_ord_par_sel_code', $mes_lis_ord_par_sel_code);
        }
        if ($delivery_service_code!='*') {
            $result= $result->where('data_order_vouchers.mes_lis_ord_log_del_delivery_service_code', $delivery_service_code);
        }
        if ($temperature!='*') {
            $result= $result->where('data_order_vouchers.mes_lis_ord_tra_ins_temperature_code', $temperature);
        }

        if ($byr_category_code!='*') {
            $result= $result->where('data_order_vouchers.mes_lis_ord_tra_goo_major_category', $byr_category_code);
        }

        if ($check_datetime!='*') {
            if ($check_datetime==1) {
                $result= $result->whereNull('data_order_vouchers.check_datetime');
            } else {
                $result= $result->whereNotNull('data_order_vouchers.check_datetime');
            }
        }

        if ($send_cnt == "!0") {
            $result= $result->having('send_cnt', '!=', '0');
        } elseif ($send_cnt != "*") {
            $result= $result->having('send_cnt', '=', $send_cnt);
        }
        if ($decission_cnt == "!0") {
            $result= $result->having('decision_cnt', '!=', '0');
        } elseif ($decission_cnt != "*") {
            $result= $result->having('decision_cnt', '=', $decission_cnt);
        }
        $result = $result->groupBy([
            'dor.receive_datetime',
            'dor.sta_sen_identifier',
            'data_order_vouchers.mes_lis_ord_par_sel_code',
            'data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver',
            'data_order_vouchers.mes_lis_ord_tra_goo_major_category',
            'data_order_vouchers.mes_lis_ord_log_del_delivery_service_code',
            'data_order_vouchers.mes_lis_ord_tra_ins_temperature_code'
        ])
        ->orderBy($table_name.$sort_by, $sort_type)
        ->orderBy('data_order_vouchers.mes_lis_ord_par_sel_code')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_goo_major_category')
        ->orderBy('data_order_vouchers.mes_lis_ord_log_del_delivery_service_code')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_ins_temperature_code')
        ->paginate($per_page);
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_list' => $result, 'byr_buyer_list' => $byr_buyer]);
    }
    public function getByrOrderDataBySlr(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $user_id = $request->user_id;
        $slr_order_info =array();
        $slr_info = cmn_companies_user::select('slr_sellers.slr_seller_id')
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->where('cmn_companies_users.adm_user_id', $user_id)->first();
        if ($slr_info) {
            $slr_id = $slr_info->slr_seller_id;
            // $slr_order_info = data_shipment_voucher::select(
            //     DB::raw('COUNT(data_shipment_vouchers.data_shipment_voucher_id) - COUNT(data_shipment_vouchers.send_datetime) as total_order'),
            //     'byr_buyers.byr_buyer_id',
            //     'cmn_companies.company_name as buyer_name'
            // )
            //     ->join('data_shipments', 'data_shipments.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
            //     ->join('cmn_connects','cmn_connects.cmn_connect_id','data_shipments.cmn_connect_id')
            //     ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            //     ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            //     ->where('cmn_connects.slr_seller_id', $slr_id)
            //     ->groupBy('byr_buyers.byr_buyer_id')
            //     ->get();

            $slr_order_info = cmn_connect::select(
                DB::raw('COUNT(data_shipment_vouchers.data_shipment_voucher_id) - COUNT(data_shipment_vouchers.send_datetime) as total_order'),
                'byr_buyers.byr_buyer_id',
                'cmn_companies.company_name as buyer_name'
            )
                ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
                ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
                ->leftJoin('data_shipments', 'data_shipments.cmn_connect_id', '=', 'cmn_connects.cmn_connect_id')
                ->leftJoin('data_shipment_vouchers', 'data_shipment_vouchers.data_shipment_id', '=', 'data_shipments.data_shipment_id')
                ->whereNull('data_shipment_vouchers.deleted_at')
                ->where('cmn_connects.slr_seller_id', $slr_id)
                ->groupBy('byr_buyers.byr_buyer_id')
                ->get();
        }
        Log::debug(__METHOD__.':end---');
        return response()->json(['slr_order_info' => $slr_order_info]);
    }
    public function getByrListWithOrder(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $byr_info=$this->all_used_fun->get_company_list();
        Log::debug(__METHOD__.':end---');
        return response()->json(['byr_info' => $byr_info]);
    }
    public function orderDetails(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_order_id = $request->data_order_id;
        $order_info=$request->order_info;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $par_shi_code = $request->par_shi_code;
        $par_rec_code = $request->par_rec_code;
        $order_item_code = $request->order_item_code;
        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $mes_lis_shi_tra_trade_number=$request->mes_lis_shi_tra_trade_number;
        $fixedSpecial=$request->fixedSpecial;
        $printingStatus=$request->printingStatus;
        $situation=$request->situation;
        $send_datetime=$request->send_datetime;

        $delivery_date = $order_info['delivery_date'];
        $delivery_service_code = $order_info['delivery_service_code'];
        $major_category = $order_info['major_category'];
        $temperature_code = $order_info['temperature_code'];
        $sel_code = $order_info['sel_code'];
        $temperature_code = $temperature_code == null ? '' : $temperature_code;

        data_order_voucher::where('data_order_id', $data_order_id)
        ->where('mes_lis_ord_tra_goo_major_category', $major_category)
        ->where('mes_lis_ord_log_del_delivery_service_code', $delivery_service_code)
        ->where('mes_lis_ord_tra_dat_delivery_date_to_receiver', $delivery_date)
        ->where('mes_lis_ord_par_sel_code', $sel_code)
        ->whereNull('check_datetime')->update(['check_datetime'=>date('Y-m-d H:i:s')]);
        $order_info = data_shipment_voucher::select(
            'dor.receive_datetime',
            'dor.mes_lis_buy_name',
            'data_shipment_vouchers.mes_lis_shi_par_sel_code',
            'data_shipment_vouchers.mes_lis_shi_par_sel_name',
            'data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',
            'data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',
            'data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',
            'data_shipment_vouchers.mes_lis_shi_tra_trade_number'
        )
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_orders as dor', 'dor.data_order_id', '=', 'ds.data_order_id')
        ->where('ds.data_order_id', $data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $sel_code)
        ->groupBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->first();

        $result = data_shipment_voucher::select(
                'dor.receive_datetime',
                'data_shipment_vouchers.mes_lis_shi_par_sel_code',
                'data_shipment_vouchers.mes_lis_shi_par_sel_name',
                'data_shipment_vouchers.data_shipment_voucher_id',
                'data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',
                'data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',
                'data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',
                'data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',
                'data_shipment_vouchers.decision_datetime',
                'data_shipment_vouchers.mes_lis_shi_par_shi_code',
                'data_shipment_vouchers.mes_lis_shi_par_rec_code',
                'data_shipment_vouchers.mes_lis_shi_par_rec_name',
                'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
                'data_shipment_vouchers.mes_lis_shi_tra_ins_goods_classification_code',
                'data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total',
                'data_shipment_vouchers.status',
                'data_shipment_vouchers.updated_at',
                'data_shipment_vouchers.print_datetime',
                'data_shipment_vouchers.send_datetime'
            )
            ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
            ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
            ->join('data_orders as dor', 'dor.data_order_id', '=', 'ds.data_order_id')
            ->where('ds.data_order_id', $data_order_id)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $delivery_date)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $major_category)
            ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $delivery_service_code)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $temperature_code)
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $sel_code);


        if ($mes_lis_shi_tra_trade_number!=null) {
            $result = $result->where('data_shipment_vouchers.mes_lis_shi_tra_trade_number', $mes_lis_shi_tra_trade_number);
        }
        if ($fixedSpecial!="*") {
            $result = $result->where('data_shipment_vouchers.mes_lis_shi_tra_ins_goods_classification_code', $fixedSpecial);
        }
        if ($printingStatus!="*") {
            if ($printingStatus=="未印刷あり") {
                $result = $result->whereNull('data_shipment_vouchers.print_datetime');
            }
            if ($printingStatus=="印刷済") {
                $result = $result->whereNotNull('data_shipment_vouchers.print_datetime');
            }
        }
        if ($situation!="*") {
            if ($situation=="未確定あり") {
                $result = $result->whereNull('data_shipment_vouchers.decision_datetime');
            }
            if ($situation=="確定済") {
                $result = $result->whereNotNull('data_shipment_vouchers.decision_datetime');
            }
        }
        if ($send_datetime!="*") {
            if ($send_datetime=="未送信あり") {
                $result = $result->whereNull('data_shipment_vouchers.send_datetime');
            }
            if ($send_datetime=="送信済") {
                $result = $result->whereNotNull('data_shipment_vouchers.send_datetime');
            }
        }
        if ($par_shi_code!=null) {
            $result = $result->where('data_shipment_vouchers.mes_lis_shi_par_shi_code', $par_shi_code);
        }
        if ($par_rec_code!=null) {
            $result = $result->where('data_shipment_vouchers.mes_lis_shi_par_rec_code', $par_rec_code);
        }
        if ($order_item_code!=null) {
            $result = $result->where('dsi.mes_lis_shi_lin_ite_order_item_code', $order_item_code);
        }
        $result = $result->orderBy('data_shipment_vouchers.'.$sort_by, $sort_type);
        $result = $result->groupBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')
                ->paginate($per_page);
        $slected_list = array();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_list_detail' => $result, 'order_info' => $order_info, 'slected_list' => $slected_list]);
    }
    public function order_detail_paginations(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        // $form_search = $request->form_search;
        $data_order_id = $request->order_info['data_order_id'];
        $order_info=$request->order_info;
        $sort_by = 'data_shipment_voucher_id';//$request->sort_by;
        $sort_type = 'asc';//$request->sort_type;
        $par_shi_code = $request->par_shi_code;
        $par_rec_code = $request->par_rec_code;
        $order_item_code = $request->order_item_code;
        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $mes_lis_shi_tra_trade_number=$request->mes_lis_shi_tra_trade_number;
        $fixedSpecial=$request->fixedSpecial;
        $printingStatus=$request->printingStatus;
        $situation=$request->situation;

        $delivery_date = $order_info['delivery_date'];
        $delivery_service_code = $order_info['delivery_service_code'];
        $major_category = $order_info['major_category'];
        $temperature_code = $order_info['temperature_code'];
        $sel_code = $order_info['sel_code'];
        $temperature_code = $temperature_code == null ? '' : $temperature_code;
        $result = data_shipment_voucher::select(
                'dor.receive_datetime',
                'data_shipment_vouchers.mes_lis_shi_par_sel_code',
                'data_shipment_vouchers.mes_lis_shi_par_sel_name',
                'data_shipment_vouchers.data_shipment_voucher_id',
                'data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',
                'data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',
                'data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',
                'data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',
                'data_shipment_vouchers.decision_datetime',
                'data_shipment_vouchers.mes_lis_shi_par_shi_code',
                'data_shipment_vouchers.mes_lis_shi_par_rec_code',
                'data_shipment_vouchers.mes_lis_shi_par_rec_name',
                'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
                'data_shipment_vouchers.mes_lis_shi_tra_ins_goods_classification_code',
                'data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total',
                'data_shipment_vouchers.status',
                'data_shipment_vouchers.updated_at',
                'data_shipment_vouchers.print_datetime',
                'data_shipment_vouchers.send_datetime'
            )
            ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
            ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
            ->join('data_orders as dor', 'dor.data_order_id', '=', 'ds.data_order_id')
            ->where('ds.data_order_id', $data_order_id)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $delivery_date)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $major_category)
            ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $delivery_service_code)
            ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $temperature_code)
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $sel_code);

        $result = $result->orderBy('data_shipment_vouchers.'.$sort_by, $sort_type);
        $result = $result->groupBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')->get();
        // ->paginate($per_page);
        /*coll setting*/
        Log::debug(__METHOD__.':end---');

        return response()->json(['order_list_detail' => $result]);
    }
    public function getOrderById($byr_order_id)
    {
        Log::debug(__METHOD__.':start---');
        $result = DB::table('bms_orders')->where('bms_orders.byr_order_id', $byr_order_id)
            ->get();
        /*coll setting*/
        $slected_list = array();
        $result_data = cmn_tbl_col_setting::where('url_slug', 'order_list_detail')->first();
        $header_list = json_decode($result_data->content_setting);
        foreach ($header_list as $header) {
            if ($header->header_status == true) {
                $slected_list[] = $header->header_field;
            }
        }
        /*coll setting*/
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_list_detail' => $result, 'slected_list' => $slected_list]);
    }

    public function get_voucher_detail_popup1(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);
        $result = data_shipment_voucher::select('data_shipment_vouchers.mes_lis_shi_par_shi_code',
        'data_shipment_vouchers.mes_lis_shi_par_shi_name','data_shipment_vouchers.mes_lis_shi_log_del_route_code')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->where('ds.cmn_connect_id',$cmn_connect_id)
        ->where('ds.data_order_id',$request->data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$request->delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$request->major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$request->temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$request->sel_code)
        ->groupBy('data_shipment_vouchers.mes_lis_shi_par_shi_code')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_par_shi_code')
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup2(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);
        $result = data_shipment_voucher::select('data_shipment_vouchers.mes_lis_shi_par_rec_code',
        'data_shipment_vouchers.mes_lis_shi_par_rec_name','data_shipment_vouchers.mes_lis_shi_log_del_route_code')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->where('ds.cmn_connect_id',$cmn_connect_id)
        ->where('ds.data_order_id',$request->data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$request->delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$request->major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$request->temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$request->sel_code)
        ->groupBy('data_shipment_vouchers.mes_lis_shi_par_rec_code')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_par_rec_code')
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['popUpList' => $result]);
    }

    public function get_voucher_detail_popup3(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);
        $result = data_shipment_voucher::select('dsi.mes_lis_shi_lin_ite_order_item_code',
        'dsi.mes_lis_shi_lin_ite_name','dsi.mes_lis_shi_lin_ite_ite_spec')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi','dsi.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
        ->where('ds.cmn_connect_id',$cmn_connect_id)
        ->where('ds.data_order_id',$request->data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$request->delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$request->major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$request->delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$request->temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$request->sel_code)
        ->groupBy('dsi.mes_lis_shi_lin_ite_order_item_code')
        ->orderBy('dsi.mes_lis_shi_lin_ite_order_item_code')
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['popUpList' => $result]);
    }
}
