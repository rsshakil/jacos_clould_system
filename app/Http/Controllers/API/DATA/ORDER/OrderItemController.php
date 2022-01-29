<?php

namespace App\Http\Controllers\API\DATA\ORDER;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CMN\cmn_tbl_col_setting;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\BYR\byr_buyer;
use App\Models\BYR\byr_item;
use App\Models\ADM\User;
use App\Models\CMN\cmn_companies_user;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\DATA\ORD\data_order_item;

use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
    }
    public function orderItemDetails($data_shipment_voucher_id)
    {
        $orderItem=data_order_item::select('dsv.*','dor.*')
        ->join('data_order_vouchers as dov','dov.data_order_voucher_id','=','data_order_items.data_order_voucher_id')
        ->join('data_shipment_vouchers as dsv','dsv.data_order_voucher_id','=','data_order_items.data_order_voucher_id')
        ->join('data_orders as dor','dor.data_order_id','=','dov.data_order_id')
        ->where('data_order_items.data_order_voucher_id',$data_shipment_voucher_id)
        ->first();
        $result=data_shipment_item::select('data_shipment_items.*','dsv.*','dov.*','doi.*')
        ->join('data_shipment_vouchers as dsv','dsv.data_shipment_voucher_id','=','data_shipment_items.data_shipment_voucher_id')
        ->join('data_shipments as ds','ds.data_shipment_id','=','dsv.data_shipment_id')
        ->join('data_order_vouchers as dov', function($join){
            $join->on('dov.data_order_id','=', 'ds.data_order_id')
            ->on('dov.mes_lis_ord_tra_trade_number','=','dsv.mes_lis_shi_tra_trade_number');
        })
        ->join('data_order_items as doi', function($join){
            $join->on('doi.data_order_voucher_id','=', 'dov.data_order_voucher_id')
            ->on('doi.mes_lis_ord_lin_lin_line_number','=','data_shipment_items.mes_lis_shi_lin_lin_line_number');
        })
        ->where('data_shipment_items.data_shipment_voucher_id',$data_shipment_voucher_id)
        ->orderBy('data_shipment_items.mes_lis_shi_lin_lin_line_number')
        ->get();
        $slected_list = array();
        return response()->json(['order_item_list_detail' => $result, 'orderItem' => $orderItem, 'slected_list' => $slected_list]);
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
        ->groupBy('dsv.mes_lis_shi_tra_trade_number')->first();
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
            //inner join data_order_items on data_order_items.data_order_voucher_id=data_shipment_vouchers.data_order_voucher_id

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
            ->groupBy('dsv.mes_lis_shi_tra_trade_number')->get();


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
    public function masterItem($adm_user_id){
        $authUser=User::find($adm_user_id);
        if(!$authUser->hasRole(config('const.adm_role_name'))){
            $cmn_company_info = cmn_companies_user::select('byr_buyers.cmn_company_id','byr_buyers.byr_buyer_id','cmn_connects.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->join('cmn_connects', 'cmn_connects.byr_buyer_id', '=', 'byr_buyers.byr_buyer_id')
            ->where('cmn_companies_users.adm_user_id',$adm_user_id)->first();
            $byr_buyer_id = $cmn_company_info->byr_buyer_id;
        }
        $result = byr_item::select('byr_items.*','byr_item_classes.*','cmn_makers.maker_name_kana','cmn_makers.maker_name')
        ->join('byr_item_classes', 'byr_item_classes.byr_item_id', '=', 'byr_items.byr_item_id')
        ->join('cmn_makers', 'cmn_makers.cmn_maker_id', '=', 'byr_items.cmn_maker_id');

        if(!$authUser->hasRole(config('const.adm_role_name'))){
            $result =$result->where('byr_items.byr_buyer_id',$byr_buyer_id)
            ->get();
            $byr_buyer = byr_buyer::where('byr_buyer_id',$byr_buyer_id)->get();
        }else{
            $result =$result->get();
            $byr_buyer = byr_buyer::all();
        }
        return response()->json(['item_list' => $result,'byr_buyer_list'=>$byr_buyer]);
    }
}
