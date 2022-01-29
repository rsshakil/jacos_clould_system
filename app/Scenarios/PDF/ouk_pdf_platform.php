<?php

namespace App\Scenarios\PDF;

use App\Models\DATA\ORD\data_order;
use App\Models\DATA\ORD\data_order_voucher;

class ouk_pdf_platform
{
    //
    public function exec($request,$sc)
    {
        $line_per_page=$request->line_per_page;
        $data_order_id=$request->data_order_id;
        $report_arr_final=array();
        $order_data=data_order::select(
            'cmn_connects.optional',
            'data_order_vouchers.mes_lis_ord_par_sel_name_sbcs',
            'data_order_vouchers.mes_lis_ord_par_sel_code',
            'data_order_vouchers.mes_lis_ord_par_rec_name_sbcs',
            'data_order_vouchers.mes_lis_ord_tra_ins_goods_classification_code',
            'data_order_vouchers.mes_lis_ord_par_rec_code',
            'data_order_vouchers.mes_lis_ord_tra_goo_major_category',
            'data_order_vouchers.mes_lis_ord_par_shi_name',
            'data_order_vouchers.mes_lis_ord_log_del_delivery_service_code',
            'data_order_vouchers.mes_lis_ord_tra_trade_number',
            'data_order_vouchers.mes_lis_ord_tra_dat_order_date',
            'data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver',
            'data_order_vouchers.mes_lis_ord_tot_tot_selling_price_total',
            'data_order_vouchers.mes_lis_ord_tot_tot_net_price_total',
            'data_order_items.mes_lis_ord_lin_ite_name_sbcs',
            'data_order_items.mes_lis_ord_lin_ite_order_item_code',
            'data_order_items.mes_lis_ord_lin_qua_ord_quantity',
            'data_order_items.mes_lis_ord_lin_amo_item_selling_price',
            'data_order_items.mes_lis_ord_lin_amo_item_selling_price_unit_price',
            'data_order_items.mes_lis_ord_lin_amo_item_net_price',
            'data_order_items.mes_lis_ord_lin_amo_item_net_price_unit_price',
            'data_order_items.mes_lis_ord_lin_lin_line_number',
        )
        ->join('data_order_vouchers','data_order_vouchers.data_order_id','=','data_orders.data_order_id')
        ->join('data_order_items','data_order_items.data_order_voucher_id','=','data_order_vouchers.data_order_voucher_id')
        ->join('cmn_connects','cmn_connects.cmn_connect_id','=','data_orders.cmn_connect_id')
        ->where('data_orders.data_order_id',$data_order_id)
        ->get();
        $collection = collect($order_data);

        $grouped = $collection->groupBy('mes_lis_ord_tra_trade_number');

        $aaa=$grouped->all();
        $report_arr_count=count($aaa);
        for ($i=0; $i < $report_arr_count; $i++) {
            $step0=array_keys($aaa)[$i];
            // =====
            for ($k=0; $k < count($aaa[$step0]); $k++) {
                $aaa[$step0][$k]['fax']=json_decode($aaa[$step0][$k]['optional'])->order->fax;
            }
            // =====
            $step0_data_array=$aaa[$step0];
            $report_arr_final[]=$step0_data_array;
        }
        return ['status'=>1,'new_report_array'=>$report_arr_final];
    }


}
