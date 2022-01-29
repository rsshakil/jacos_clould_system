<?php

namespace App\Http\Controllers\API\BYR\DATA\ORDER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\ADM\User;
use App\Models\DATA\ORD\data_order;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Http\Controllers\API\BYR\DATA\ORDER\DataController;
use App\Http\Controllers\API\BYR\DATA\DFLT\DefaultFunctions;
use App\Traits\Csv;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Models\CMN\cmn_tbl_col_setting;
// use App\Http\Controllers\API\AllUsedFunction;

class SlrOrderController extends Controller
{
    // private $default_functions;
    // private $all_used_fun;
    public function __construct()
    {
        // $this->default_functions = new DefaultFunctions();
        // $this->all_used_fun = new AllUsedFunction();
    }

    public function slrOrderList(Request $request){
        Log::debug(__METHOD__.':start---');
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $per_page = $request->per_page?$request->per_page:10;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $trash_status = $request->trash_status;

        $table_name='data_order_vouchers.';
        if ($sort_by=="data_order_id" || $sort_by=="receive_datetime") {
            $table_name='dor.';
        } elseif ($sort_by=="decision_datetime" || $sort_by=="print_datetime") {
            $table_name='dsv.';
        }
        $result = data_order_voucher::select(
            'dor.data_order_id',
            'dor.receive_datetime',
            'data_order_vouchers.deleted_at',
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
        ->where('cc.byr_buyer_id', $byr_buyer_id);
        // ->where('cc.slr_seller_id', $slr_seller_id);
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
        $confirmation_status = $request->confirmation_status; // 参照
        $decission_cnt = $request->decission_cnt; // 確定
        $send_cnt = $request->send_cnt; // 印刷
        $byr_category_code = $request->category_code; // 印刷
        $mes_lis_ord_par_sel_code = $request->mes_lis_ord_par_sel_code; // 印刷
        $trade_number=$request->trade_number;


        $byr_category_code = $byr_category_code['category_code'];

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
        // }
        $result = $result->groupBy([
            'dor.receive_datetime',
            'dor.sta_sen_identifier',
            'data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver',
            'data_order_vouchers.mes_lis_ord_tra_goo_major_category',
            'data_order_vouchers.mes_lis_ord_log_del_delivery_service_code',
            'data_order_vouchers.mes_lis_ord_tra_ins_temperature_code',
            'data_order_vouchers.mes_lis_ord_par_sel_code'
        ])
        ->orderBy($table_name.$sort_by, $sort_type)
        ->orderBy('data_order_vouchers.mes_lis_ord_par_sel_code')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_goo_major_category')
        ->orderBy('data_order_vouchers.mes_lis_ord_log_del_delivery_service_code')
        ->orderBy('data_order_vouchers.mes_lis_ord_tra_ins_temperature_code');
        if ($trash_status=='*') {
            $result = $result->withTrashed();
        }
        $result = $result->paginate($per_page);
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_list' => $result]);
    }
    // slrCustomerCodeList
    public function slrCustomerCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $query = data_order_voucher::join('data_orders as dor', 'dor.data_order_id', '=', 'data_order_vouchers.data_order_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dor.cmn_connect_id')
            ->select(
                'data_order_vouchers.mes_lis_ord_par_sel_code',
                'data_order_vouchers.mes_lis_ord_par_sel_name',
                'data_order_vouchers.mes_lis_ord_par_pay_code',
                'data_order_vouchers.mes_lis_ord_par_pay_name'
            )
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->withTrashed()
            ->groupby('data_order_vouchers.mes_lis_ord_par_sel_code', 'data_order_vouchers.mes_lis_ord_par_pay_code');
        $result = $query->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_customer_code_lists' => $result]);
    }

    public function slrOrderDetails(Request $request){
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

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
        ->withTrashed()
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
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $sel_code)
            ->withTrashed();


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
        /*coll setting*/
        $slected_list = array();
        Log::debug(__METHOD__.':end---');
        return response()->json(['order_list_detail' => $result, 'order_info' => $order_info, 'slected_list' => $slected_list]);
    }
    public function getVoucherShiCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_order_id=$request->data_order_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $order_info=$request->order_info;

        $delivery_date=$order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'];
        $major_category=$order_info['mes_lis_shi_tra_goo_major_category'];
        $delivery_service_code=$order_info['mes_lis_shi_log_del_delivery_service_code'];
        $temperature_code=$order_info['mes_lis_shi_tra_ins_temperature_code'];
        $sel_code=$order_info['mes_lis_shi_par_sel_code'];

        $shi_code_list = data_shipment_voucher::select('data_shipment_vouchers.mes_lis_shi_par_shi_code',
        'data_shipment_vouchers.mes_lis_shi_par_shi_name','data_shipment_vouchers.mes_lis_shi_log_del_route_code')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','ds.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('ds.data_order_id',$data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$sel_code)
        ->groupBy('data_shipment_vouchers.mes_lis_shi_par_shi_code')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_par_shi_code')
        ->withTrashed()
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['shi_code_list' => $shi_code_list]);
    }

    public function getVoucherRecCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_order_id=$request->data_order_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $order_info=$request->order_info;
        $delivery_date=$order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'];
        $major_category=$order_info['mes_lis_shi_tra_goo_major_category'];
        $delivery_service_code=$order_info['mes_lis_shi_log_del_delivery_service_code'];
        $temperature_code=$order_info['mes_lis_shi_tra_ins_temperature_code'];
        $sel_code=$order_info['mes_lis_shi_par_sel_code'];

        $rec_code_list = data_shipment_voucher::select('data_shipment_vouchers.mes_lis_shi_par_rec_code',
        'data_shipment_vouchers.mes_lis_shi_par_rec_name','data_shipment_vouchers.mes_lis_shi_log_del_route_code')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','ds.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('ds.data_order_id',$data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$sel_code)
        ->groupBy('data_shipment_vouchers.mes_lis_shi_par_rec_code')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_par_rec_code')
        ->withTrashed()
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['rec_code_list' => $rec_code_list]);
    }

    public function getVoucherItemCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);
        $data_order_id=$request->data_order_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $order_info=$request->order_info;

        $delivery_date=$order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'];
        $major_category=$order_info['mes_lis_shi_tra_goo_major_category'];
        $delivery_service_code=$order_info['mes_lis_shi_log_del_delivery_service_code'];
        $temperature_code=$order_info['mes_lis_shi_tra_ins_temperature_code'];
        $sel_code=$order_info['mes_lis_shi_par_sel_code'];

        $item_code_list = data_shipment_voucher::select('dsi.mes_lis_shi_lin_ite_order_item_code',
        'dsi.mes_lis_shi_lin_ite_name','dsi.mes_lis_shi_lin_ite_ite_spec')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi','dsi.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','ds.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('ds.data_order_id',$data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',$delivery_date)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',$major_category)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',$delivery_service_code)
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',$temperature_code)
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code',$sel_code)
        ->groupBy('dsi.mes_lis_shi_lin_ite_order_item_code')
        ->orderBy('dsi.mes_lis_shi_lin_ite_order_item_code')
        ->withTrashed()
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['item_code_list' => $item_code_list]);
    }
    public function shipmentItemDetailSearch(Request $request)
    {
        $orderItem = data_shipment_item::select(
            'dor.receive_datetime',
            'dsv.mes_lis_shi_par_sel_code',
            'dsv.mes_lis_shi_par_sel_name',
            'dsv.data_shipment_voucher_id',
            'dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver',
            'dsv.mes_lis_shi_tra_goo_major_category',
            'dsv.mes_lis_shi_log_del_delivery_service_code',
            'dsv.mes_lis_shi_tra_ins_temperature_code',
            'dsv.decision_datetime',
            'dsv.mes_lis_shi_par_shi_code',
            'dsv.mes_lis_shi_par_rec_code',
            'dsv.mes_lis_shi_par_rec_name',
            'dsv.mes_lis_shi_tra_trade_number',
            'dsv.mes_lis_shi_tra_ins_goods_classification_code',
            'dsv.mes_lis_shi_tot_tot_net_price_total',
            'dsv.status',
            'dsv.updated_at',
            'dsv.print_datetime',
            'dsv.send_datetime',
            'data_shipment_items.*',
            'doi.*'
        )
        ->leftJoin('data_shipment_vouchers as dsv', 'dsv.data_shipment_voucher_id', '=', 'data_shipment_items.data_shipment_voucher_id')
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'dsv.data_shipment_id')
        ->join('data_orders as dor', 'dor.data_order_id', '=', 'ds.data_order_id')
        ->join('data_order_items as doi', 'doi.data_order_voucher_id', '=', 'dsv.data_order_voucher_id')
        ->where('data_shipment_items.mes_lis_shi_lin_ite_supplier_item_code', $request->item_code)
        ->where('ds.data_order_id', $request->data_order_id)
        ->where('dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver', $request->delivery_date)
        ->where('dsv.mes_lis_shi_tra_goo_major_category', $request->major_category)
        ->where('dsv.mes_lis_shi_log_del_delivery_service_code', $request->delivery_service_code)
        ->where('dsv.mes_lis_shi_tra_ins_temperature_code', $request->temperature_code)
        ->whereNull('dsv.decision_datetime')
        ->groupBy('dsv.mes_lis_shi_tra_trade_number')->withTrashed()->first();
        //shipment
        $result = data_shipment_item::select(
                'dor.receive_datetime',
                'dsv.mes_lis_shi_par_sel_code',
                'dsv.mes_lis_shi_par_sel_name',
                'dsv.data_shipment_voucher_id',
                'dsv.mes_lis_shi_tra_tax_tax_rate',
                'dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver',
                'dsv.mes_lis_shi_tra_goo_major_category',
                'dsv.mes_lis_shi_log_del_delivery_service_code',
                'dsv.mes_lis_shi_tra_ins_temperature_code',
                'dsv.decision_datetime',
                'dsv.mes_lis_shi_par_shi_code',
                'dsv.mes_lis_shi_par_rec_code',
                'dsv.mes_lis_shi_par_rec_name',
                'dsv.mes_lis_shi_tra_trade_number',
                'dsv.mes_lis_shi_tra_ins_goods_classification_code',
                'dsv.mes_lis_shi_tot_tot_net_price_total',
                'dsv.status',
                'dsv.updated_at',
                'dsv.print_datetime',
                'dsv.send_datetime',
                'data_shipment_items.*',
                'doi.*'
            )
            ->leftJoin('data_shipment_vouchers as dsv', 'dsv.data_shipment_voucher_id', '=', 'data_shipment_items.data_shipment_voucher_id')
            ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'dsv.data_shipment_id')
            ->join('data_orders as dor', 'dor.data_order_id', '=', 'ds.data_order_id')
            ->join('data_order_items as doi', 'doi.data_order_voucher_id', '=', 'dsv.data_order_voucher_id')
            ->where('data_shipment_items.mes_lis_shi_lin_ite_supplier_item_code', $request->item_code)
            ->where('ds.data_order_id', $request->data_order_id)
            ->where('dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver', $request->delivery_date)
            ->where('dsv.mes_lis_shi_tra_goo_major_category', $request->major_category)
            ->where('dsv.mes_lis_shi_log_del_delivery_service_code', $request->delivery_service_code)
            ->where('dsv.mes_lis_shi_tra_ins_temperature_code', $request->temperature_code)
            ->whereNull('dsv.decision_datetime')
            ->groupBy('dsv.mes_lis_shi_tra_trade_number')->withTrashed()->get();
            $slected_list = array();
            $result_data = cmn_tbl_col_setting::where('url_slug', 'order_item_list_detail')->first();
            if ($result_data) {
                $header_list = json_decode($result_data->content_setting);
                foreach ($header_list as $header) {
                    if ($header->header_status == true) {
                        $slected_list[] = $header->header_field;
                    }
                }
            }
        /*coll setting*/
        return response()->json(['order_item_list_detail' => $result, 'orderItem' => $orderItem, 'slected_list' => $slected_list]);
    }
    public function orderDeleteRetrive(Request $request){
        // return $request->all();
        $data_order_id=$request->data_order_id;
        $mes_lis_ord_log_del_delivery_service_code=$request->mes_lis_ord_log_del_delivery_service_code;
        $mes_lis_ord_par_sel_code=$request->mes_lis_ord_par_sel_code;
        $mes_lis_ord_par_sel_name=$request->mes_lis_ord_par_sel_name;
        $mes_lis_ord_tra_dat_delivery_date_to_receiver=$request->mes_lis_ord_tra_dat_delivery_date_to_receiver;
        $mes_lis_ord_tra_goo_major_category=$request->mes_lis_ord_tra_goo_major_category;
        $mes_lis_ord_tra_ins_temperature_code=$request->mes_lis_ord_tra_ins_temperature_code;
        $receive_datetime=$request->receive_datetime;
        $deleted_at=$request->deleted_at;

        $order_voucher_var=data_order_voucher::where('data_order_id',$data_order_id)
        ->where('mes_lis_ord_log_del_delivery_service_code',$mes_lis_ord_log_del_delivery_service_code)
        ->where('mes_lis_ord_par_sel_code',$mes_lis_ord_par_sel_code)
        ->where('mes_lis_ord_par_sel_name',$mes_lis_ord_par_sel_name)
        ->where('mes_lis_ord_tra_dat_delivery_date_to_receiver',$mes_lis_ord_tra_dat_delivery_date_to_receiver)
        ->where('mes_lis_ord_tra_goo_major_category',$mes_lis_ord_tra_goo_major_category)
        ->where('mes_lis_ord_tra_ins_temperature_code',$mes_lis_ord_tra_ins_temperature_code);

        $shipment_voucher_var=data_shipment_voucher::
        // where('ds.data_order_id',$data_order_id)
        where('mes_lis_shi_log_del_delivery_service_code',$mes_lis_ord_log_del_delivery_service_code)
        ->where('mes_lis_shi_par_sel_code',$mes_lis_ord_par_sel_code)
        ->where('mes_lis_shi_par_sel_name',$mes_lis_ord_par_sel_name)
        ->where('mes_lis_shi_tra_dat_delivery_date_to_receiver',$mes_lis_ord_tra_dat_delivery_date_to_receiver)
        ->where('mes_lis_shi_tra_goo_major_category',$mes_lis_ord_tra_goo_major_category)
        ->where('mes_lis_shi_tra_ins_temperature_code',$mes_lis_ord_tra_ins_temperature_code);
        // ->where('receive_datetime',$receive_datetime);

        if ($deleted_at==null) {
            $order_voucher_var->delete();
            $shipment_voucher_var->delete();
        }else{
            $order_voucher_var->withTrashed()->restore();
            $shipment_voucher_var->withTrashed()->restore();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'data_count' => $order_voucher_var->withTrashed()->count()]);
    }
}
