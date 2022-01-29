<?php

namespace App\Scenarios\DATA\INVOICE;

use App\Scenarios\Common;
use App\Models\DATA\SHIPMENT\data_shipment;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class data_invoice_scheduler
{
    private $all_functions;

    public function __construct()
    {
        // $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request, $sc)
    {
        $start_date=$request->start_date;
        $end_date=$request->end_date;

        $data_invoice_array=array();
        $data_invoice_pay_array=array();
        $data_invoice_pay_details_array=array();
        $shipment_datas=self::shipmentQuery($request);
        $data_count=count($shipment_datas);
        $datashipment=true;
        $data_invoice_pay_id=1;
        DB::beginTransaction();
        try {
        foreach ($shipment_datas as $key => $shipment_data) {
            if ($datashipment) {
                $data_invoice_array['cmn_connect_id']=$shipment_data['cmn_connect_id'];
                $data_invoice_array['sta_sen_identifier']=$shipment_data['sta_sen_identifier'];
                $data_invoice_array['sta_sen_ide_authority']=$shipment_data['sta_sen_ide_authority'];
                $data_invoice_array['sta_rec_identifier']=$shipment_data['sta_rec_identifier'];
                $data_invoice_array['sta_rec_ide_authority']=$shipment_data['sta_rec_ide_authority'];
                $data_invoice_array['sta_doc_standard']=$shipment_data['sta_doc_standard'];
                $data_invoice_array['sta_doc_type_version']=$shipment_data['sta_doc_type_version'];
                $data_invoice_array['sta_doc_instance_identifier']=$shipment_data['sta_doc_instance_identifier'];
                $data_invoice_array['sta_doc_type']='Invoice';      //$shipment_data['sta_doc_type'];
                $data_invoice_array['sta_doc_creation_date_and_time']=$shipment_data['sta_doc_creation_date_and_time'];
                $data_invoice_array['sta_bus_scope_instance_identifier']=$shipment_data['sta_bus_scope_instance_identifier'];
                $data_invoice_array['sta_bus_scope_type']=$shipment_data['sta_bus_scope_type'];
                $data_invoice_array['sta_bus_scope_identifier']=$shipment_data['sta_bus_scope_identifier'];
                $data_invoice_array['mes_ent_unique_creator_identification']=$shipment_data['mes_ent_unique_creator_identification'];
                $data_invoice_array['mes_mes_sender_station_address']=$shipment_data['mes_mes_sender_station_address'];
                $data_invoice_array['mes_mes_ultimate_receiver_station_address']=$shipment_data['mes_mes_ultimate_receiver_station_address'];
                $data_invoice_array['mes_mes_immediate_receiver_station_addres']=$shipment_data['mes_mes_immediate_receiver_station_addres'];
                $data_invoice_array['mes_mes_number_of_trading_documents']=$shipment_data['tod']; //change
                $data_invoice_array['mes_mes_sys_key']=$shipment_data['mes_mes_sys_key'];
                $data_invoice_array['mes_mes_sys_value']=$shipment_data['mes_mes_sys_value'];
                $data_invoice_array['mes_lis_con_version']=$shipment_data['mes_lis_con_version'];
                $data_invoice_array['mes_lis_doc_version']=$shipment_data['mes_lis_doc_version'];
                $data_invoice_array['mes_lis_ext_namespace']=$shipment_data['mes_lis_ext_namespace'];
                $data_invoice_array['mes_lis_ext_version']=$shipment_data['mes_lis_ext_version'];
                $data_invoice_array['mes_lis_pay_code']=$shipment_data['mes_lis_pay_code'];
                $data_invoice_array['mes_lis_pay_gln']=$shipment_data['mes_lis_pay_gln'];
                $data_invoice_array['mes_lis_pay_name']=$shipment_data['mes_lis_pay_name'];
                $data_invoice_array['mes_lis_pay_name_sbcs']=$shipment_data['mes_lis_pay_name_sbcs'];
                $datashipment=false;
                $data_invoice_id=data_invoice::insertGetId($data_invoice_array);
            }
            data_shipment_voucher::where('data_shipment_voucher_id',$shipment_data['data_shipment_voucher_id'])->update(['invoice_datetime'=>date('Y-m-d H:i:s')]);

                $data_invoice_pay_array['mes_lis_buy_code']=$shipment_data['mes_lis_buy_code'];
                $data_invoice_pay_array['mes_lis_buy_gln']=$shipment_data['mes_lis_buy_gln'];
                $data_invoice_pay_array['mes_lis_buy_name']=$shipment_data['mes_lis_buy_name'];
                $data_invoice_pay_array['mes_lis_buy_name_sbcs']=$shipment_data['mes_lis_buy_name_sbcs'];
                //static
                $data_invoice_pay_array['mes_lis_inv_pay_id']='';
                $data_invoice_pay_array['mes_lis_inv_pay_code']=$shipment_data['mes_lis_shi_par_pay_code'];
                $data_invoice_pay_array['mes_lis_inv_pay_gln']=$shipment_data['mes_lis_shi_par_pay_gln'];
                $data_invoice_pay_array['mes_lis_inv_pay_name']=$shipment_data['mes_lis_shi_par_pay_name'];
                $data_invoice_pay_array['mes_lis_inv_pay_name_sbcs']=$shipment_data['mes_lis_shi_par_pay_name_sbcs'];
                $data_invoice_pay_array['mes_lis_inv_per_begin_date']=$start_date;
                $data_invoice_pay_array['mes_lis_inv_per_end_date']=$end_date;
                //static


                $data_invoice_pay_count = data_invoice_pay::where([
                    ['mes_lis_inv_pay_id','=',''],
                    ['mes_lis_buy_code','=',$shipment_data['mes_lis_buy_code']],
                    ['mes_lis_inv_pay_code','=',$shipment_data['mes_lis_shi_par_pay_code']],
                    ['mes_lis_inv_per_begin_date','=',$start_date],
                    ['mes_lis_inv_per_end_date','=',$end_date],
                ])->get()->count();
                if (!$data_invoice_pay_count) {
                    $data_invoice_pay_array['data_invoice_id']=$data_invoice_id;
                    $data_invoice_pay_id = data_invoice_pay::insertGetId($data_invoice_pay_array);
                }

                $data_invoice_pay_details_array['data_shipment_voucher_id']=$shipment_data['data_shipment_voucher_id'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_lin_trade_number_reference']=$shipment_data['mes_lis_shi_tra_trade_number'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_lin_issue_classification_code']=$shipment_data['mes_lis_shi_tra_additional_trade_number']; //Not confirmed
                $data_invoice_pay_details_array['mes_lis_inv_lin_lin_sequence_number']=$shipment_data['mes_lis_shi_fre_shipment_number']; //Not confirmed
                $data_invoice_pay_details_array['mes_lis_inv_lin_tra_code']=$shipment_data['mes_lis_shi_par_tra_code'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_tra_gln']=$shipment_data['mes_lis_shi_par_tra_gln'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_tra_name']=$shipment_data['mes_lis_shi_par_tra_name'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_tra_name_sbcs']=$shipment_data['mes_lis_shi_par_tra_name_sbcs'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_sel_code']=$shipment_data['mes_lis_shi_par_sel_code'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_sel_gln']=$shipment_data['mes_lis_shi_par_sel_gln'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_sel_name']=$shipment_data['mes_lis_shi_par_sel_name'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_sel_name_sbcs']=$shipment_data['mes_lis_shi_par_sel_name_sbcs'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_goo_major_category']=$shipment_data['mes_lis_shi_tra_goo_major_category'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_goo_sub_major_category']=$shipment_data['mes_lis_shi_tra_goo_sub_major_category'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_transfer_of_ownership_date']=$shipment_data['ownership_date'];
                //static
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_amo_requested_amount']=$shipment_data['mes_lis_shi_tot_tot_net_price_total'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_amo_req_plus_minus']='+';
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_amo_tax']=$shipment_data['mes_lis_shi_tot_tot_tax_total'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_balance_carried_code']='01'; // Static Data
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_credit_or_unsettlement']='';
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_pay_code']='1001'; //schedule: 1001
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_tax_tax_type_code']=$shipment_data['mes_lis_shi_tra_tax_tax_type_code'];
                $data_invoice_pay_details_array['mes_lis_inv_lin_det_tax_tax_rate']=$shipment_data['mes_lis_shi_tra_tax_tax_rate'];
                 //static
                $data_invoice_pay_details_array['data_invoice_pay_id']=$data_invoice_pay_id;
                data_invoice_pay_detail::insert($data_invoice_pay_details_array);

                $data_invoice_array=array();
                $data_invoice_pay_array=array();
                $data_invoice_pay_details_array=array();
        }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return ['message' => $e->getMessage(), 'status' => 0,'data'=>['class'=>'error']];
            // something went wrong
        }
        return ['message' => "success", 'status' => 1,'data'=>['total_success_data'=>$data_count,'class'=>'success']];
    }
    public static function shipmentQuery($request){
        $cmn_connect_id=$request->cmn_connect_id;
        $start_date=$request->start_date;
        $end_date=$request->end_date;
        $byr_buyer_id=$request->byr_buyer_id;
        $arg=$request->arg;
        // if ($arg!=0) {
        //     $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        // }
        $start_date = $start_date!=null? date('Y-m-d 00:00:00',strtotime($start_date)):$start_date;
        $end_date = $end_date!=null? date('Y-m-d 23:59:59',strtotime($end_date)):$end_date;
        $shipment_datas=data_shipment_voucher::select(
            'ds.cmn_connect_id',
            'ds.sta_sen_identifier',
            'ds.sta_sen_ide_authority',
            'ds.sta_rec_identifier',
            'ds.sta_rec_ide_authority',
            'ds.sta_doc_standard',
            'ds.sta_doc_type_version',
            'ds.sta_doc_instance_identifier',
            'ds.sta_doc_type',
            'ds.sta_doc_creation_date_and_time',
            'ds.sta_bus_scope_type',
            'ds.sta_bus_scope_instance_identifier',
            'ds.sta_bus_scope_identifier',
            'ds.mes_ent_unique_creator_identification',
            'ds.mes_mes_sender_station_address',
            'ds.mes_mes_ultimate_receiver_station_address',
            'ds.mes_mes_immediate_receiver_station_addres',
            'ds.mes_mes_number_of_trading_documents',
            'ds.mes_mes_sys_key',
            'ds.mes_mes_sys_value',
            'ds.mes_lis_con_version',
            'ds.mes_lis_doc_version',
            'ds.mes_lis_ext_namespace',
            'ds.mes_lis_ext_version',
            'ds.mes_lis_pay_code',
            'ds.mes_lis_pay_gln',
            'ds.mes_lis_pay_name',
            'ds.mes_lis_pay_name_sbcs',
            'ds.mes_lis_buy_code',
            'ds.mes_lis_buy_gln',
            'ds.mes_lis_buy_name',
            'ds.mes_lis_buy_name_sbcs',
        'data_shipment_vouchers.*','data_shipment_items.*','data_shipment_item_details.*',
        DB::raw('count(data_shipment_vouchers.mes_lis_shi_tra_dat_transfer_of_ownership_date) as tod'),
        DB::raw('(CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date is null
        THEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver
        ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date END) as ownership_date')
        )
        ->leftJoin('data_shipments as ds','ds.data_shipment_id','data_shipment_vouchers.data_shipment_id')
        ->leftJoin('data_shipment_items','data_shipment_items.data_shipment_voucher_id','data_shipment_vouchers.data_shipment_voucher_id')
        ->leftJoin('data_shipment_item_details','data_shipment_item_details.data_shipment_item_id','data_shipment_items.data_shipment_item_id');
        // if($arg!=0){
        //     $shipment_datas=$shipment_datas->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_shipments.cmn_connect_id');
        // }
        $shipment_datas=$shipment_datas->whereNotNull('data_shipment_vouchers.send_datetime')
        ->whereNull('data_shipment_vouchers.invoice_datetime')
        ->whereBetween(DB::raw('(CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date is null
        THEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver
        ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date END)'), [$start_date, $end_date])
        ->where('data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total','!=',0)
        ->where('ds.cmn_connect_id',$cmn_connect_id);
            // if($arg==0){
            //     $shipment_datas=$shipment_datas->where('data_shipments.cmn_connect_id',$cmn_connect_id);
            // }else{
            //     $shipment_datas=$shipment_datas->where('cc.byr_buyer_id', $byr_buyer_id)
            //     ->where('cc.slr_seller_id', $slr_seller_id);
            // }
        $shipment_datas = $shipment_datas
        ->groupBy('data_shipment_vouchers.data_shipment_voucher_id')->get();
        return $shipment_datas;
    }
}
