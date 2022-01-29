<?php

namespace App\Http\Controllers\API\BYR\DATA\STOCK;

use App\Http\Controllers\Controller;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SlrStockItemController extends Controller
{
    public function stockItemList(Request $request)
    {
        // return $request->all();
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $per_page = $request->per_page?$request->per_page:10;
        // $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;

        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $date_to_receiver_from = $request->date_to_receiver_from;
        $date_to_receiver_to = $request->date_to_receiver_to;
        $date_to_receiver_from = $date_to_receiver_from!=null? date('Y-m-d 00:00:00', strtotime($date_to_receiver_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $date_to_receiver_to = $date_to_receiver_to!=null? date('Y-m-d 23:59:59', strtotime($date_to_receiver_to)):config('const.TO_DATETIME'); // 受信日時終了
        $item_code = $request->item_code;
        $partner_code = $request->partner_code;

        $table_name='';
        if ($sort_by=="mes_lis_shi_lin_ite_name" || $sort_by=="mes_lis_shi_lin_ite_order_item_code" || $sort_by=="mes_lis_shi_lin_amo_item_net_price_unit_price") {
            $table_name='dsi.';
        }
        if ($sort_by=="partner_code") {
            $table_name='cc.';
        }
        if ($sort_by=="seller_name") {
            $table_name='ccom.';
            $sort_by='company_name';
        }
        $stock_items=data_shipment_voucher::select(
            'dsi.mes_lis_shi_lin_ite_name',
            'dsi.mes_lis_shi_lin_ite_order_item_code',
            DB::raw("COUNT(data_shipment_vouchers.mes_lis_shi_tra_trade_number) as mes_lis_shi_tra_trade_number"),
            DB::raw("SUM(dsi.mes_lis_shi_lin_qua_ord_quantity) as mes_lis_shi_lin_qua_ord_quantity"),
            'dsi.mes_lis_shi_lin_amo_item_net_price_unit_price',
            DB::raw("SUM(dsi.mes_lis_shi_lin_amo_item_net_price) as mes_lis_shi_lin_amo_item_net_price"),
            DB::raw("SUM(dsi.mes_lis_shi_lin_qua_sto_quantity) as mes_lis_shi_lin_qua_sto_quantity"),
            DB::raw("ROUND((SUM(dsi.mes_lis_shi_lin_qua_sto_quantity)/SUM(dsi.mes_lis_shi_lin_qua_ord_quantity))*100) as percentage"),
            'cc.partner_code',
            'ccom.company_name as seller_name'
        )
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
        ->join('slr_sellers as ss', 'ss.slr_seller_id', '=', 'cc.slr_seller_id')
        ->join('cmn_companies as ccom', 'ccom.cmn_company_id', '=', 'ss.cmn_company_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        // ->where('cc.slr_seller_id',$slr_seller_id)
        ->whereBetween('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', [$date_to_receiver_from,$date_to_receiver_to]);
        if ($item_code!=null) {
            $stock_items=$stock_items->where('dsi.mes_lis_shi_lin_ite_order_item_code', $item_code);
        }
        if ($partner_code!=null) {
            $stock_items=$stock_items->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $partner_code);
        }
        $stock_items=$stock_items
        ->whereNotNull('data_shipment_vouchers.send_datetime')
        ->groupBy('dsi.mes_lis_shi_lin_ite_order_item_code')
        ->orderBy($table_name.$sort_by, $sort_type)
        ->paginate($per_page);
        return response()->json(['stock_items'=>$stock_items]);
    }
    public function stockItemCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        // $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $query = data_shipment_voucher::select(
            'dsi.mes_lis_shi_lin_ite_name',
            'dsi.mes_lis_shi_lin_ite_order_item_code',
        )
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        // ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereNotNull('data_shipment_vouchers.send_datetime')
        ->groupby('dsi.mes_lis_shi_lin_ite_order_item_code');
        $result = $query->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['stockItemCodeList' => $result]);
    }

    public function slrStockItemPartnerCodeList(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id = $request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;

        $query = data_shipment_voucher::join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
            ->select(
                'data_shipment_vouchers.mes_lis_shi_par_sel_code',
                'data_shipment_vouchers.mes_lis_shi_par_sel_name',
                'data_shipment_vouchers.mes_lis_shi_par_pay_code',
                'data_shipment_vouchers.mes_lis_shi_par_pay_name'
            )
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->whereNotNull('data_shipment_vouchers.send_datetime')
            ->withTrashed()
            ->groupby('data_shipment_vouchers.mes_lis_shi_par_sel_code', 'data_shipment_vouchers.mes_lis_shi_par_pay_code');
        $result = $query->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['partner_code_lists' => $result]);
    }
    // public function slrCustomerCodeList(Request $request)
    // {
    //     Log::debug(__METHOD__.':start---');

    //     $byr_buyer_id = $request->byr_buyer_id;
    //     $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
    //     $query = data_shipment_voucher::join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
    //         ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
    //         ->select(
    //             'data_shipment_vouchers.mes_lis_shi_par_sel_code',
    //             'data_shipment_vouchers.mes_lis_shi_par_sel_name',
    //             'data_shipment_vouchers.mes_lis_shi_par_pay_code',
    //             'data_shipment_vouchers.mes_lis_shi_par_pay_name'
    //         )
    //         ->where('cc.byr_buyer_id', $byr_buyer_id)
    //         ->whereNotNull('data_shipment_vouchers.send_datetime')
    //         ->withTrashed()
    //         ->groupby('data_shipment_vouchers.mes_lis_shi_par_sel_code', 'data_shipment_vouchers.mes_lis_shi_par_pay_code');
    //     $result = $query->get();
    //     Log::debug(__METHOD__.':end---');
    //     return response()->json(['order_customer_code_lists' => $result]);
    // }
}
