<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\ScenarioBase;

use App\Http\Controllers\API\AllUsedFunction;
use App\Models\DATA\ORD\data_order;
use App\Models\DATA\ORD\data_order_item;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\SHIPMENT\data_shipment;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\DATA\SHIPMENT\data_shipment_item_detail;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\CMN\cmn_connect;
use App\Scenarios\Common;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

// require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
// use Symfony\Component\HttpFoundation\Response;

// use setasign\Fpdi\Tcpdf\Fpdi;
// use tecnickcom\tcpdf\TCPDF_FONTS;

class data_csv_order extends ScenarioBase
{
    private $all_functions;
    private $common_class_obj;
    private $fax_number;
    private $attachment_paths_all;
    private $attachment_paths;
    public function __construct()
    {
        parent::__construct();
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
        $this->attachment_paths_all=array();
        $this->attachment_paths=array();
        $this->fax_number='';
    }

    //
    public function exec($request, $sc)
    {
        Log::debug(__METHOD__.':start---');

        // test
        // $data=$this->pdfGenerate(1,993477);
        // return ['message'=>'Success','status'=>1,'data'=>$data];
        // test

        // file save
        $file_info = $this->upfileSave($request, config('const.ORDER_DATA_PATH') . date('Y-m'));
        $cmn_connect_id = $file_info['cmn_connect_id'];

        // csv
        $dataArr = $this->all_functions->csvReader($file_info['save_path'], 1);

        // data check
        $this->checkCsvData($dataArr, 158);
        $cur_date = date( 'Y-m-d H:i:s' );
        $order_flg = true;
        $trade_number = '';
        $data_order_id=null;
        DB::beginTransaction();
        try {
            foreach ($dataArr as $key => $value) {
                if (count($value) === 1) {
                    // 空であればcontinue
                    continue;
                }
                // $exists_order=data_order::select('data_orders.cmn_connect_id', 'dov.mes_lis_ord_tra_trade_number', 'dov.mes_lis_ord_tra_dat_order_date')
                // ->join('data_order_vouchers as dov', 'dov.data_order_id', '=', 'data_orders.data_order_id')
                // ->join('data_order_items as doi', 'doi.data_order_voucher_id', '=', 'dov.data_order_voucher_id')
                // ->where('data_orders.cmn_connect_id', $cmn_connect_id)
                // ->where('dov.mes_lis_ord_tra_trade_number', $value[31])
                // ->where('dov.mes_lis_ord_tra_dat_order_date', $value[73])
                // ->where('doi.mes_lis_ord_lin_ite_order_item_code', $value[112]);
                $exists_order=data_order_voucher::select('dor.cmn_connect_id', 'data_order_vouchers.mes_lis_ord_tra_trade_number', 'data_order_vouchers.mes_lis_ord_tra_dat_order_date')
                ->join('data_orders as dor', 'dor.data_order_id', '=', 'data_order_vouchers.data_order_id')
                ->join('data_order_items as doi', 'doi.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
                ->where('dor.cmn_connect_id', $cmn_connect_id)
                ->where('data_order_vouchers.mes_lis_ord_tra_trade_number', $value[31])
                ->where('data_order_vouchers.mes_lis_ord_tra_dat_order_date', $value[73])
                ->where('doi.mes_lis_ord_lin_ite_order_item_code', $value[112]);

                if ($exists_order->exists()) {
                    Log::info('Already order data exists: [cmn_connect_id]:'.$cmn_connect_id . ' [mes_lis_ord_tra_trade_number]:'.$value[31].' [mes_lis_ord_tra_dat_order_date]:'.$value[73].' [mes_lis_ord_lin_ite_order_item_code]:'.$value[112]);
                    continue;
                }

                if ($order_flg) {
                    $data_order_array['sta_sen_identifier'] = $value[0];
                    $data_order_array['sta_sen_ide_authority'] = $value[1];
                    $data_order_array['sta_rec_identifier'] = $value[2];
                    $data_order_array['sta_rec_ide_authority'] = $value[3];
                    $data_order_array['sta_doc_standard'] = $value[4];
                    $data_order_array['sta_doc_type_version'] = $value[5];
                    $data_order_array['sta_doc_instance_identifier'] = $value[6];
                    $data_order_array['sta_doc_type'] = $value[7];
                    $data_order_array['sta_doc_creation_date_and_time'] = $value[8];
                    $data_order_array['sta_bus_scope_type'] = $value[9];
                    $data_order_array['sta_bus_scope_instance_identifier'] = $value[10];
                    $data_order_array['sta_bus_scope_identifier'] = $value[11];
                    $data_order_array['mes_ent_unique_creator_identification'] = $value[12];
                    $data_order_array['mes_mes_sender_station_address'] = $value[13];
                    $data_order_array['mes_mes_ultimate_receiver_station_address'] = $value[14];
                    $data_order_array['mes_mes_immediate_receiver_station_addres'] = $value[15];
                    $data_order_array['mes_mes_number_of_trading_documents'] = $value[16];
                    $data_order_array['mes_mes_sys_key'] = $value[17];
                    $data_order_array['mes_mes_sys_value'] = $value[18];
                    $data_order_array['mes_lis_con_version'] = $value[19];
                    $data_order_array['mes_lis_doc_version'] = $value[20];
                    $data_order_array['mes_lis_ext_namespace'] = $value[21];
                    $data_order_array['mes_lis_ext_version'] = $value[22];
                    $data_order_array['mes_lis_pay_code'] = $value[23];
                    $data_order_array['mes_lis_pay_gln'] = $value[24];
                    $data_order_array['mes_lis_pay_name'] = $value[25];
                    $data_order_array['mes_lis_pay_name_sbcs'] = $value[26];
                    $data_order_array['mes_lis_buy_code'] = $value[27];
                    $data_order_array['mes_lis_buy_gln'] = $value[28];
                    $data_order_array['mes_lis_buy_name'] = $value[29];
                    $data_order_array['mes_lis_buy_name_sbcs'] = $value[30];

                    // Order
                    $data_order_array['cmn_connect_id'] = $cmn_connect_id;
                    $data_order_array['route'] = 'edi';
                    $data_order_array['receive_datetime']=$cur_date;
                    $data_order_array['receive_file_path'] = $file_info['file_name'];

                    $data_order_id = data_order::insertGetId($data_order_array);

                    // Shipment
                    unset($data_order_array["route"]);
                    unset($data_order_array["receive_datetime"]);
                    unset($data_order_array["receive_file_path"]);
                    $data_order_array['data_order_id'] = $data_order_id;
                    $data_order_array['upload_datetime'] = '';
                    $data_order_array['upload_file_path'] = '';
                    $data_order_array['send_datetime'] = '';
                    $data_order_array['send_file_path'] = '';
                    $data_order_array['sta_sen_identifier'] = $value[2];
                    $data_order_array['sta_sen_ide_authority'] = $value[3];
                    $data_order_array['sta_rec_identifier'] = $value[0];
                    $data_order_array['sta_rec_ide_authority'] = $value[1];
                    $data_order_array['sta_doc_type'] = 'Shipment Notification';

                    $data_shipment_id = data_shipment::insertGetId($data_order_array);

                    $order_flg = false;
                }

                if ($trade_number != $value[31] . '-' . $value[32]) {
                    $data_voucher_array['mes_lis_ord_tra_trade_number'] = $value[31];
                    $data_voucher_array['mes_lis_ord_tra_additional_trade_number'] = $value[32];
                    $data_voucher_array['mes_lis_ord_par_shi_code'] = $value[33];
                    $data_voucher_array['mes_lis_ord_par_shi_gln'] = $value[34];
                    $data_voucher_array['mes_lis_ord_par_shi_name'] = $value[35];
                    $data_voucher_array['mes_lis_ord_par_shi_name_sbcs'] = $value[36];
                    $data_voucher_array['mes_lis_ord_par_rec_code'] = $value[37];
                    $data_voucher_array['mes_lis_ord_par_rec_gln'] = $value[38];
                    $data_voucher_array['mes_lis_ord_par_rec_name'] = $value[39];
                    $data_voucher_array['mes_lis_ord_par_rec_name_sbcs'] = $value[40];
                    $data_voucher_array['mes_lis_ord_par_tra_code'] = $value[41];
                    $data_voucher_array['mes_lis_ord_par_tra_gln'] = $value[42];
                    $data_voucher_array['mes_lis_ord_par_tra_name'] = $value[43];
                    $data_voucher_array['mes_lis_ord_par_tra_name_sbcs'] = $value[44];
                    $data_voucher_array['mes_lis_ord_par_dis_code'] = $value[45];
                    $data_voucher_array['mes_lis_ord_par_dis_name'] = $value[46];
                    $data_voucher_array['mes_lis_ord_par_dis_name_sbcs'] = $value[47];
                    $data_voucher_array['mes_lis_ord_par_pay_code'] = $value[48];
                    $data_voucher_array['mes_lis_ord_par_pay_gln'] = $value[49];
                    $data_voucher_array['mes_lis_ord_par_pay_name'] = $value[50];
                    $data_voucher_array['mes_lis_ord_par_pay_name_sbcs'] = $value[51];
                    $data_voucher_array['mes_lis_ord_par_sel_code'] = $value[52];
                    $data_voucher_array['mes_lis_ord_par_sel_gln'] = $value[53];
                    $data_voucher_array['mes_lis_ord_par_sel_name'] = $value[54];
                    $data_voucher_array['mes_lis_ord_par_sel_name_sbcs'] = $value[55];
                    $data_voucher_array['mes_lis_ord_par_sel_branch_number'] = $value[56];
                    $data_voucher_array['mes_lis_ord_par_sel_ship_location_code'] = $value[57];
                    $data_voucher_array['mes_lis_ord_log_shi_gln'] = $value[58];
                    $data_voucher_array['mes_lis_ord_log_del_route_code'] = $value[59];
                    $data_voucher_array['mes_lis_ord_log_del_delivery_service_code'] = $value[60];
                    $data_voucher_array['mes_lis_ord_log_del_stock_transfer_code'] = $value[61];
                    $data_voucher_array['mes_lis_ord_log_del_delivery_code'] = $value[62];
                    $data_voucher_array['mes_lis_ord_log_del_delivery_time'] = $value[63];
                    $data_voucher_array['mes_lis_ord_log_del_transportation_code'] = $value[64];
                    $data_voucher_array['mes_lis_ord_log_log_barcode_print'] = $value[65];
                    $data_voucher_array['mes_lis_ord_log_log_category_name_print1'] = $value[66];
                    $data_voucher_array['mes_lis_ord_log_log_category_name_print2'] = $value[67];
                    $data_voucher_array['mes_lis_ord_log_log_receiver_abbr_name'] = $value[68];
                    $data_voucher_array['mes_lis_ord_log_log_text'] = $value[69];
                    $data_voucher_array['mes_lis_ord_log_log_text_sbcs'] = $value[70];
                    $data_voucher_array['mes_lis_ord_tra_goo_major_category'] = $value[71];
                    $data_voucher_array['mes_lis_ord_tra_goo_sub_major_category'] = $value[72];
                    $data_voucher_array['mes_lis_ord_tra_dat_order_date'] = $value[73];
                    $data_voucher_array['mes_lis_ord_tra_dat_delivery_date'] = $value[74];
                    $data_voucher_array['mes_lis_ord_tra_dat_delivery_date_to_receiver'] = $value[75];
                    $data_voucher_array['mes_lis_ord_tra_dat_transfer_of_ownership_date'] = $value[76];
                    $data_voucher_array['mes_lis_ord_tra_dat_campaign_start_date'] = $value[77];
                    $data_voucher_array['mes_lis_ord_tra_dat_campaign_end_date'] = $value[78];
                    $data_voucher_array['mes_lis_ord_tra_dat_valid_until_date'] = $value[79];
                    $data_voucher_array['mes_lis_ord_tra_ins_goods_classification_code'] = $value[80];
                    $data_voucher_array['mes_lis_ord_tra_ins_order_classification_code'] = $value[81];
                    $data_voucher_array['mes_lis_ord_tra_ins_ship_notification_request_code'] = $value[82];
                    $data_voucher_array['mes_lis_ord_tra_ins_private_brand_code'] = $value[83];
                    $data_voucher_array['mes_lis_ord_tra_ins_temperature_code'] = $value[84];
                    $data_voucher_array['mes_lis_ord_tra_ins_liquor_code'] = $value[85];
                    $data_voucher_array['mes_lis_ord_tra_ins_trade_type_code'] = $value[86];
                    $data_voucher_array['mes_lis_ord_tra_ins_paper_form_less_code'] = $value[87];
                    $data_voucher_array['mes_lis_ord_tra_fre_trade_number_request_code'] = $value[88];
                    $data_voucher_array['mes_lis_ord_tra_fre_package_code'] = $value[89];
                    $data_voucher_array['mes_lis_ord_tra_fre_variable_measure_item_code'] = $value[90];
                    $data_voucher_array['mes_lis_ord_tra_tax_tax_type_code'] = $value[91];
                    $data_voucher_array['mes_lis_ord_tra_tax_tax_rate'] = $value[92];
                    $data_voucher_array['mes_lis_ord_tra_not_text'] = $value[93];
                    $data_voucher_array['mes_lis_ord_tra_not_text_sbcs'] = $value[94];
                    $data_voucher_array['mes_lis_ord_tot_tot_net_price_total'] = $value[95];
                    $data_voucher_array['mes_lis_ord_tot_tot_selling_price_total'] = $value[96];
                    $data_voucher_array['mes_lis_ord_tot_tot_tax_total'] = $value[97];
                    $data_voucher_array['mes_lis_ord_tot_tot_item_total'] = $value[98];
                    $data_voucher_array['mes_lis_ord_tot_tot_unit_total'] = $value[99];
                    $data_voucher_array['mes_lis_ord_tot_fre_unit_weight_total'] = $value[100];

                    $data_voucher_array['data_order_id'] = $data_order_id;
                    $data_order_voucher_id = data_order_voucher::insertGetId($data_voucher_array);

                    $data_shi_voucher_array['data_order_voucher_id'] = $data_order_voucher_id;
                    $data_shi_voucher_array['mes_lis_shi_tra_trade_number'] = $value[31];
                    $data_shi_voucher_array['mes_lis_shi_tra_additional_trade_number'] = $value[32];
                    $data_shi_voucher_array['mes_lis_shi_par_shi_code'] = $value[33];
                    $data_shi_voucher_array['mes_lis_shi_par_shi_gln'] = $value[34];
                    $data_shi_voucher_array['mes_lis_shi_par_shi_name'] = $value[35];
                    $data_shi_voucher_array['mes_lis_shi_par_shi_name_sbcs'] = $value[36];
                    $data_shi_voucher_array['mes_lis_shi_par_rec_code'] = $value[37];
                    $data_shi_voucher_array['mes_lis_shi_par_rec_gln'] = $value[38];
                    $data_shi_voucher_array['mes_lis_shi_par_rec_name'] = $value[39];
                    $data_shi_voucher_array['mes_lis_shi_par_rec_name_sbcs'] = $value[40];
                    $data_shi_voucher_array['mes_lis_shi_par_tra_code'] = $value[41];
                    $data_shi_voucher_array['mes_lis_shi_par_tra_gln'] = $value[42];
                    $data_shi_voucher_array['mes_lis_shi_par_tra_name'] = $value[43];
                    $data_shi_voucher_array['mes_lis_shi_par_tra_name_sbcs'] = $value[44];
                    $data_shi_voucher_array['mes_lis_shi_par_dis_code'] = $value[45];
                    $data_shi_voucher_array['mes_lis_shi_par_dis_name'] = $value[46];
                    $data_shi_voucher_array['mes_lis_shi_par_dis_name_sbcs'] = $value[47];
                    $data_shi_voucher_array['mes_lis_shi_par_pay_code'] = $value[48];
                    $data_shi_voucher_array['mes_lis_shi_par_pay_gln'] = $value[49];
                    $data_shi_voucher_array['mes_lis_shi_par_pay_name'] = $value[50];
                    $data_shi_voucher_array['mes_lis_shi_par_pay_name_sbcs'] = $value[51];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_code'] = $value[52];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_gln'] = $value[53];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_name'] = $value[54];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_name_sbcs'] = $value[55];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_branch_number'] = $value[56];
                    $data_shi_voucher_array['mes_lis_shi_par_sel_ship_location_code'] = $value[57];
                    $data_shi_voucher_array['mes_lis_shi_log_shi_gln'] = $value[58];
                    $data_shi_voucher_array['mes_lis_shi_log_del_route_code'] = $value[59];
                    $data_shi_voucher_array['mes_lis_shi_log_del_delivery_service_code'] = $value[60];
                    $data_shi_voucher_array['mes_lis_shi_log_del_stock_transfer_code'] = $value[61];
                    $data_shi_voucher_array['mes_lis_shi_log_del_delivery_code'] = $value[62];
                    $data_shi_voucher_array['mes_lis_shi_log_del_delivery_time'] = $value[63];
                    $data_shi_voucher_array['mes_lis_shi_log_del_transportation_code'] = $value[64];
                    $data_shi_voucher_array['mes_lis_shi_log_log_barcode_print'] = $value[65];
                    $data_shi_voucher_array['mes_lis_shi_log_log_category_name_print1'] = $value[66];
                    $data_shi_voucher_array['mes_lis_shi_log_log_category_name_print2'] = $value[67];
                    $data_shi_voucher_array['mes_lis_shi_log_log_receiver_abbr_name'] = $value[68];
                    $data_shi_voucher_array['mes_lis_shi_log_log_text'] = $value[69];
                    $data_shi_voucher_array['mes_lis_shi_log_log_text_sbcs'] = $value[70];
                    $data_shi_voucher_array['mes_lis_shi_log_maker_code_for_receiving'] = '';
                    $data_shi_voucher_array['mes_lis_shi_log_delivery_slip_number'] = '';
                    $data_shi_voucher_array['mes_lis_shi_tra_goo_major_category'] = $value[71];
                    $data_shi_voucher_array['mes_lis_shi_tra_goo_sub_major_category'] = $value[72];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_order_date'] = $value[73];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_delivery_date'] = $value[74];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_delivery_date_to_receiver'] = $value[75];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_revised_delivery_date'] = null;
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_transfer_of_ownership_date'] = $value[76];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_campaign_start_date'] = $value[77];
                    $data_shi_voucher_array['mes_lis_shi_tra_dat_campaign_end_date'] = $value[78];
                    // $data_shi_voucher_array['mes_lis_shi_tra_dat_valid_until_date']=$value[79];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_goods_classification_code'] = $value[80];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_order_classification_code'] = $value[81];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_ship_notification_request_code'] = $value[82];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_eos_code'] = '01';
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_private_brand_code'] = $value[83];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_temperature_code'] = $value[84];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_liquor_code'] = $value[85];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_trade_type_code'] = $value[86];
                    $data_shi_voucher_array['mes_lis_shi_tra_ins_paper_form_less_code'] = $value[87];
                    $data_shi_voucher_array['mes_lis_shi_tra_fre_trade_number_request_code'] = $value[88];
                    $data_shi_voucher_array['mes_lis_shi_tra_fre_package_code'] = $value[89];
                    $data_shi_voucher_array['mes_lis_shi_tra_fre_variable_measure_item_code'] = $value[90];
                    $data_shi_voucher_array['mes_lis_shi_tra_tax_tax_type_code'] = $value[91];
                    $data_shi_voucher_array['mes_lis_shi_tra_tax_tax_rate'] = $value[92];
                    $data_shi_voucher_array['mes_lis_shi_tra_not_text'] = $value[93];
                    $data_shi_voucher_array['mes_lis_shi_tra_not_text_sbcs'] = $value[94];
                    $data_shi_voucher_array['mes_lis_shi_tot_tot_net_price_total'] = $value[95];
                    $data_shi_voucher_array['mes_lis_shi_tot_tot_selling_price_total'] = $value[96];
                    $data_shi_voucher_array['mes_lis_shi_tot_tot_tax_total'] = $value[97];
                    $data_shi_voucher_array['mes_lis_shi_tot_tot_item_total'] = $value[98];
                    $data_shi_voucher_array['mes_lis_shi_tot_tot_unit_total'] = $value[99];
                    $data_shi_voucher_array['mes_lis_shi_tot_fre_unit_weight_total'] = $value[100];

                    $data_shi_voucher_array["data_shipment_id"] = $data_shipment_id;
                    $data_shipment_voucher_id = data_shipment_voucher::insertGetId($data_shi_voucher_array);

                    $trade_number = $value[31] . '-' . $value[32];
                }

                $data_item_array['mes_lis_ord_lin_lin_line_number'] = $value[101];
                $data_item_array['mes_lis_ord_lin_lin_additional_line_number'] = $value[102];
                $data_item_array['mes_lis_ord_lin_fre_trade_number'] = $value[103];
                $data_item_array['mes_lis_ord_lin_fre_line_number'] = $value[104];
                $data_item_array['mes_lis_ord_lin_goo_minor_category'] = $value[105];
                $data_item_array['mes_lis_ord_lin_goo_detailed_category'] = $value[106];
                $data_item_array['mes_lis_ord_lin_ite_scheduled_date'] = $value[107];
                $data_item_array['mes_lis_ord_lin_ite_deadline_date'] = $value[108];
                $data_item_array['mes_lis_ord_lin_ite_center_delivery_instruction_code'] = $value[109];
                $data_item_array['mes_lis_ord_lin_ite_maker_code'] = $value[110];
                $data_item_array['mes_lis_ord_lin_ite_gtin'] = $value[111];
                $data_item_array['mes_lis_ord_lin_ite_order_item_code'] = $value[112];
                $data_item_array['mes_lis_ord_lin_ite_ord_code_type'] = $value[113];
                $data_item_array['mes_lis_ord_lin_ite_supplier_item_code'] = $value[114];
                $data_item_array['mes_lis_ord_lin_ite_name'] = $value[115];
                $data_item_array['mes_lis_ord_lin_ite_name_sbcs'] = $value[116];
                $data_item_array['mes_lis_ord_lin_ite_ite_spec'] = $value[117];
                $data_item_array['mes_lis_ord_lin_ite_ite_spec_sbcs'] = $value[118];
                $data_item_array['mes_lis_ord_lin_ite_col_color_code'] = $value[119];
                $data_item_array['mes_lis_ord_lin_ite_col_description'] = $value[120];
                $data_item_array['mes_lis_ord_lin_ite_col_description_sbcs'] = $value[121];
                $data_item_array['mes_lis_ord_lin_ite_siz_size_code'] = $value[122];
                $data_item_array['mes_lis_ord_lin_ite_siz_description'] = $value[123];
                $data_item_array['mes_lis_ord_lin_ite_siz_description_sbcs'] = $value[124];
                $data_item_array['mes_lis_ord_lin_fre_packing_quantity'] = $value[125];
                $data_item_array['mes_lis_ord_lin_fre_prefecture_code'] = $value[126];
                $data_item_array['mes_lis_ord_lin_fre_country_code'] = $value[127];
                $data_item_array['mes_lis_ord_lin_fre_field_name'] = $value[128];
                $data_item_array['mes_lis_ord_lin_fre_water_area_code'] = $value[129];
                $data_item_array['mes_lis_ord_lin_fre_water_area_name'] = $value[130];
                $data_item_array['mes_lis_ord_lin_fre_area_of_origin'] = $value[131];
                $data_item_array['mes_lis_ord_lin_fre_item_grade'] = $value[132];
                $data_item_array['mes_lis_ord_lin_fre_item_class'] = $value[133];
                $data_item_array['mes_lis_ord_lin_fre_brand'] = $value[134];
                $data_item_array['mes_lis_ord_lin_fre_item_pr'] = $value[135];
                $data_item_array['mes_lis_ord_lin_fre_bio_code'] = $value[136];
                $data_item_array['mes_lis_ord_lin_fre_breed_code'] = $value[137];
                $data_item_array['mes_lis_ord_lin_fre_cultivation_code'] = $value[138];
                $data_item_array['mes_lis_ord_lin_fre_defrost_code'] = $value[139];
                $data_item_array['mes_lis_ord_lin_fre_item_preservation_code'] = $value[140];
                $data_item_array['mes_lis_ord_lin_fre_item_shape_code'] = $value[141];
                $data_item_array['mes_lis_ord_lin_fre_use'] = $value[142];
                $data_item_array['mes_lis_ord_lin_sta_statutory_classification_code'] = $value[143];
                $data_item_array['mes_lis_ord_lin_amo_item_net_price'] = $value[144];
                $data_item_array['mes_lis_ord_lin_amo_item_net_price_unit_price'] = $value[145];
                $data_item_array['mes_lis_ord_lin_amo_item_selling_price'] = $value[146];
                $data_item_array['mes_lis_ord_lin_amo_item_selling_price_unit_price'] = $value[147];
                $data_item_array['mes_lis_ord_lin_amo_item_tax'] = $value[148];
                $data_item_array['mes_lis_ord_lin_qua_unit_multiple'] = $value[149];
                $data_item_array['mes_lis_ord_lin_qua_unit_of_measure'] = $value[150];
                $data_item_array['mes_lis_ord_lin_qua_package_indicator'] = $value[151];
                $data_item_array['mes_lis_ord_lin_qua_ord_quantity'] = $value[152];
                $data_item_array['mes_lis_ord_lin_qua_ord_num_of_order_units'] = $value[153];
                $data_item_array['mes_lis_ord_lin_fre_unit_weight'] = $value[154];
                $data_item_array['mes_lis_ord_lin_fre_unit_weight_code'] = $value[155];
                $data_item_array['mes_lis_ord_lin_fre_item_weight'] = $value[156];
                $data_item_array['mes_lis_ord_lin_fre_order_weight'] = $value[157];

                $data_shi_item_array['mes_lis_shi_lin_lin_line_number'] = $value[101];
                $data_shi_item_array['mes_lis_shi_lin_lin_additional_line_number'] = $value[102];
                $data_shi_item_array['mes_lis_shi_lin_fre_trade_number'] = $value[103];
                $data_shi_item_array['mes_lis_shi_lin_fre_line_number'] = $value[104];
                $data_shi_item_array['mes_lis_shi_lin_fre_shipment_line_number'] = '';
                $data_shi_item_array['mes_lis_shi_lin_goo_minor_category'] = $value[105];
                $data_shi_item_array['mes_lis_shi_lin_goo_detailed_category'] = $value[106];
                $data_shi_item_array['mes_lis_shi_lin_ite_scheduled_date'] = $value[107];
                $data_shi_item_array['mes_lis_shi_lin_ite_deadline_date'] = $value[108];
                $data_shi_item_array['mes_lis_shi_lin_ite_center_delivery_instruction_code'] = $value[109];
                $data_shi_item_array['mes_lis_shi_lin_fre_interim_price_code'] = '';
                $data_shi_item_array['mes_lis_shi_lin_ite_maker_code'] = $value[110];
                $data_shi_item_array['mes_lis_shi_lin_ite_gtin'] = $value[111];
                $data_shi_item_array['mes_lis_shi_lin_ite_order_item_code'] = $value[112];
                $data_shi_item_array['mes_lis_shi_lin_ite_ord_code_type'] = $value[113];
                $data_shi_item_array['mes_lis_shi_lin_ite_supplier_item_code'] = $value[114];
                $data_shi_item_array['mes_lis_shi_lin_ite_name'] = $value[115];
                $data_shi_item_array['mes_lis_shi_lin_ite_name_sbcs'] = $value[116];
                $data_shi_item_array['mes_lis_shi_lin_fre_shipment_item_code'] = '';
                $data_shi_item_array['mes_lis_shi_lin_ite_ite_spec'] = $value[117];
                $data_shi_item_array['mes_lis_shi_lin_ite_ite_spec_sbcs'] = $value[118];
                $data_shi_item_array['mes_lis_shi_lin_ite_col_color_code'] = $value[119];
                $data_shi_item_array['mes_lis_shi_lin_ite_col_description'] = $value[120];
                $data_shi_item_array['mes_lis_shi_lin_ite_col_description_sbcs'] = $value[121];
                $data_shi_item_array['mes_lis_shi_lin_ite_siz_size_code'] = $value[122];
                $data_shi_item_array['mes_lis_shi_lin_ite_siz_description'] = $value[123];
                $data_shi_item_array['mes_lis_shi_lin_ite_siz_description_sbcs'] = $value[124];
                $data_shi_item_array['mes_lis_shi_lin_fre_packing_quantity'] = $value[125];
                $data_shi_item_array['mes_lis_shi_lin_fre_prefecture_code'] = $value[126];
                $data_shi_item_array['mes_lis_shi_lin_fre_country_code'] = $value[127];
                $data_shi_item_array['mes_lis_shi_lin_fre_field_name'] = $value[128];
                $data_shi_item_array['mes_lis_shi_lin_fre_water_area_code'] = $value[129];
                $data_shi_item_array['mes_lis_shi_lin_fre_water_area_name'] = $value[130];
                $data_shi_item_array['mes_lis_shi_lin_fre_area_of_origin'] = $value[131];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_grade'] = $value[132];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_class'] = $value[133];
                $data_shi_item_array['mes_lis_shi_lin_fre_brand'] = $value[134];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_pr'] = $value[135];
                $data_shi_item_array['mes_lis_shi_lin_fre_bio_code'] = $value[136];
                $data_shi_item_array['mes_lis_shi_lin_fre_breed_code'] = $value[137];
                $data_shi_item_array['mes_lis_shi_lin_fre_cultivation_code'] = $value[138];
                $data_shi_item_array['mes_lis_shi_lin_fre_defrost_code'] = $value[139];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_preservation_code'] = $value[140];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_shape_code'] = $value[141];
                $data_shi_item_array['mes_lis_shi_lin_fre_use'] = $value[142];
                $data_shi_item_array['mes_lis_shi_lin_sta_statutory_classification_code'] = $value[143];
                $data_shi_item_array['mes_lis_shi_lin_amo_item_net_price'] = $value[144];
                $data_shi_item_array['mes_lis_shi_lin_amo_item_net_price_unit_price'] = $value[145];
                $data_shi_item_array['mes_lis_shi_lin_amo_item_selling_price'] = $value[146];
                $data_shi_item_array['mes_lis_shi_lin_amo_item_selling_price_unit_price'] = $value[147];
                $data_shi_item_array['mes_lis_shi_lin_amo_item_tax'] = $value[148];
                $data_shi_item_array['mes_lis_shi_lin_qua_unit_multiple'] = $value[149];
                $data_shi_item_array['mes_lis_shi_lin_qua_unit_of_measure'] = $value[150];
                $data_shi_item_array['mes_lis_shi_lin_qua_package_indicator'] = $value[151];
                $data_shi_item_array['mes_lis_shi_lin_qua_ord_quantity'] = $value[152];
                $data_shi_item_array['mes_lis_shi_lin_qua_ord_num_of_order_units'] = $value[153];
                $data_shi_item_array['mes_lis_shi_lin_qua_shi_quantity'] = $value[152];
                $data_shi_item_array['mes_lis_shi_lin_qua_shi_num_of_order_units'] = $value[153];
                $data_shi_item_array['mes_lis_shi_lin_qua_sto_quantity'] = '';
                $data_shi_item_array['mes_lis_shi_lin_qua_sto_num_of_order_units'] = '';
                $data_shi_item_array['mes_lis_shi_lin_qua_sto_reason_code'] = '00';
                $data_shi_item_array['mes_lis_shi_lin_fre_unit_weight'] = $value[154];
                $data_shi_item_array['mes_lis_shi_lin_fre_unit_weight_code'] = $value[155];
                $data_shi_item_array['mes_lis_shi_lin_fre_item_weight'] = $value[156];
                $data_shi_item_array['mes_lis_shi_lin_fre_order_weight'] = $value[157];
                $data_shi_item_array['mes_lis_shi_lin_fre_shipment_weight'] = $value[157];
                // 158 done

                $data_item_array['data_order_voucher_id'] = $data_order_voucher_id;
                data_order_item::insert($data_item_array);

                $data_shi_item_array["data_shipment_voucher_id"] = $data_shipment_voucher_id;
                $data_shipment_item_id = data_shipment_item::insertGetId($data_shi_item_array);
                data_shipment_item_detail::insert(['data_shipment_item_id' => $data_shipment_item_id]);

                // format
                $data_order_array = array();
                $data_voucher_array = array();
                $data_shi_voucher_array = array();
                $data_item_array = array();
                $data_shi_item_array = array();
            }
            // DB commit
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        Log::debug(__METHOD__.':end---');
        if ($data_order_id==null) {
            return ['message' => 'Success with no data insert', 'status' => $this->success,'cmn_connect_id' => $cmn_connect_id,'data_id' => $data_order_id];
        } else {
            // FAX data send
            // Mail
            $this->sendFax($cmn_connect_id, $data_order_id);
            return ['message' => 'Success', 'status' => $this->success,'cmn_connect_id' => $cmn_connect_id,'data_id' => $data_order_id];
        }
    }

    /**
     * sendFax
     *
     * @param  mixed $cmn_connect_id
     * @param  mixed $data_order_id
     * @return void
     */
    private function sendFax($cmn_connect_id, $data_order_id)
    {
        Log::debug(__METHOD__.':start---');

        $cmn_connect_options=cmn_connect::select('optional', 'partner_code')->where('cmn_connect_id', $cmn_connect_id)->first();
        // Log::info($cmn_connect_options);
        $optional=json_decode($cmn_connect_options->optional);
        // Log::debug($optional->order->fax->exec);
        try {
            if ($optional->order->fax->exec) {
                $this->fax_number=$optional->order->fax->number;
                $partner_code=$cmn_connect_options->partner_code;
                $this->attachment_paths_all=$this->pdfGenerate($data_order_id, $partner_code);
                Log::debug($this->attachment_paths_all);

                Log::info('send mail for fax:[to:'.config('const.PDF_SEND_MAIL').',subject:'.$this->fax_number.']');
                foreach ($this->attachment_paths_all as $key => $value) {
                    $this->attachment_paths=$value;
                    Mail::send([], [], function ($message) {
                        $message->to(config('const.PDF_SEND_MAIL'))
                            ->subject($this->fax_number);
                        Log::debug('attach file:'.$this->attachment_paths['pdf_file_path']);
                        $message->attach($this->attachment_paths['pdf_file_path'])
                        ->setBody('');
                    });
                }
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage().' Or May be data font missing in database data or bad file');
            return ['message' => "May be data font missing in database data or bad file", 'status' => 0];
        }
        Log::debug(__METHOD__.':end---');
    }

    /**
     * pdfGenerate
     *
     * @param  mixed $data_order_id
     * @return void
     */
    public function pdfGenerate($data_order_id, $partner_code)
    {
        Log::debug(__METHOD__.':start---');

        $pdf_datas = $this->pdfDAta($data_order_id, $partner_code);
        $today=date('YmdHis');
        // return $pdf_datas;
        // =====================
        $pdf_file_paths=array();
        $page=0;
        $x = 0;
        $y = 0;
        $odd_even=0;
        $data_count=0;
        $file_number=1;
        $page_limit=10;
        $order_pdf_save_path=config('const.ORDER_FAX_PDF_SAVE_PATH');
        $first_page=0;

        $receipt=$this->all_functions->fpdfRet();

        foreach ($pdf_datas as $key => $sel_data) {
            if ($page!=0 && $page%$page_limit==0) {
                $pdf_file_name=$today.'_'.$file_number.'_receipt.pdf';
                $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $pdf_file_name, $order_pdf_save_path);
                array_push($pdf_file_paths, $pdf_file_path);
                $receipt=$this->all_functions->fpdfRet();
                $file_number+=1;
            }
            if ($first_page!=0) {
                $receipt->AddPage();
                $first_page+=1;
                $page+=1;
            }
            // $receipt->AddPage();
            // Log::info($page);
            foreach ($sel_data as $key => $rec_data) {
                if ($first_page==0) {
                    $receipt->AddPage();
                    $page+=1;
                }
                foreach ($rec_data as $key => $value) {
                    if ($data_count==0) {
                        $receipt=$this->all_functions->pdfHeaderData($receipt, $value, $x, $y);
                    }
                    if ($odd_even==0) {
                        if ($data_count!=0 && $data_count%2==0) {
                            if ($page%$page_limit==0) {
                                $pdf_file_name=$today.'_'.$file_number.'_receipt.pdf';
                                $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $pdf_file_name, $order_pdf_save_path);
                                array_push($pdf_file_paths, $pdf_file_path);
                                $receipt=$this->all_functions->fpdfRet();
                                $file_number+=1;
                            }
                            $receipt->AddPage();
                            $page+=1;
                            $receipt=$this->all_functions->pdfHeaderData($receipt, $value, $x, $y);
                        }
                        $this->all_functions->coordinateText($receipt, $value, 0, 50.7, 103.4);
                    } else {
                        $this->all_functions->coordinateText($receipt, $value, 0, 117, 170);
                    }

                    if ($odd_even==0) {
                        $odd_even=1;
                    } else {
                        $odd_even=0;
                    }
                    $data_count+=1;
                }

                $data_count=0;
                $odd_even=0;
            }
            $first_page=0;
        }
        if ($page%$page_limit!=0) {
            $pdf_file_name=$today.'_'.$file_number.'_receipt.pdf';
            $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $pdf_file_name, $order_pdf_save_path);
            array_push($pdf_file_paths, $pdf_file_path);
            $receipt=$this->all_functions->fpdfRet();
            $file_number+=1;
        }
        Log::debug(__METHOD__.':end---');
        return $pdf_file_paths;
        // =====================
    }
    // OLD OK Function
    // public function pdfGenerate($data_order_id,$partner_code)
    // {
    //     Log::debug(__METHOD__.':start---');

    //     $pdf_datas = $this->pdfDAta($data_order_id,$partner_code);
    //     // return $pdf_datas;
    //     // =====================
    //     $pdf_file_paths=array();
    //     $page=0;
    //     $x = 0;
    //     $y = 0;
    //     $odd_even=0;
    //     $data_count=0;
    //     $file_number=1;
    //     $page_limit=10;
    //     $order_pdf_save_path=config('const.ORDER_PDF_SAVE_PATH');

    //     $receipt=$this->all_functions->fpdfRet();
    //     foreach ($pdf_datas as $key => $pdf_data) {
    //         if ($page!=0 && $page%$page_limit==0) {
    //             $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $file_number,$order_pdf_save_path);
    //             array_push($pdf_file_paths, $pdf_file_path);
    //             $receipt=$this->all_functions->fpdfRet();
    //             $file_number+=1;
    //         }
    //         $receipt->AddPage();
    //         Log::info($page);
    //         $page+=1;
    //         foreach ($pdf_data as $key => $value) {
    //             if ($data_count==0) {
    //                 $receipt=$this->all_functions->pdfHeaderData($receipt, $value, $x, $y);
    //             }
    //             if ($odd_even==0) {
    //                 if ($data_count!=0 && $data_count%2==0) {
    //                     if ($page%$page_limit==0) {
    //                         $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $file_number,$order_pdf_save_path);
    //                         array_push($pdf_file_paths, $pdf_file_path);
    //                         $receipt=$this->all_functions->fpdfRet();
    //                         $file_number+=1;
    //                     }
    //                     $receipt->AddPage();
    //                     $page+=1;
    //                     $receipt=$this->all_functions->pdfHeaderData($receipt, $value, $x, $y);
    //                 }
    //                 $this->all_functions->coordinateText($receipt, $value, 0, 50.7, 103.4);
    //             }else{
    //                 $this->all_functions->coordinateText($receipt, $value, 0, 117, 170);
    //             }

    //             if ($odd_even==0) {
    //                 $odd_even=1;
    //             }else{
    //                 $odd_even=0;
    //             }
    //             $data_count+=1;
    //         }

    //         $data_count=0;
    //         $odd_even=0;
    //     }
    //     if ($page%$page_limit!=0) {
    //         $pdf_file_path = $this->all_functions->pdfFileSave($receipt, $file_number,$order_pdf_save_path);
    //         array_push($pdf_file_paths, $pdf_file_path);
    //         $receipt=$this->all_functions->fpdfRet();
    //         $file_number+=1;
    //     }
    //     Log::debug(__METHOD__.':end---');
    //     return $pdf_file_paths;
    //     // =====================
    // }
    public function pdfDAta($data_order_id, $partner_code)
    {
        Log::debug(__METHOD__.':start---');

        // $line_per_page=$request->line_per_page;
        // $data_order_id=$request->data_order_id;
        $report_arr_final = array();
        $order_query_data = data_order::select(
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
            'data_order_items.mes_lis_ord_lin_lin_line_number'
            // DB::raw('substr(data_order_vouchers.mes_lis_ord_par_sel_code, -6) as sel_code')
        )
            ->join('data_order_vouchers', 'data_order_vouchers.data_order_id', '=', 'data_orders.data_order_id')
            ->join('data_order_items', 'data_order_items.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'data_orders.cmn_connect_id')
            ->where(DB::raw('substr(data_order_vouchers.mes_lis_ord_par_sel_code, -6)'), '=', $partner_code)
            ->where('data_orders.data_order_id', $data_order_id)
            ->get();
        // ==================
        // $recs = new \Illuminate\Database\Eloquent\Collection($order_data);
        // $grouped = $recs->groupBy('mes_lis_ord_par_rec_code')->transform(function($item, $k) {
        //     return $item->groupBy('mes_lis_ord_tra_trade_number');
        // });
        $recs = new \Illuminate\Database\Eloquent\Collection($order_query_data);
        $grouped = $recs->groupBy(['mes_lis_ord_par_sel_code','mes_lis_ord_par_rec_code','mes_lis_ord_tra_trade_number']);
        // ===================
        // $all_shipment_data = $grouped->all();
        // return $grouped;
        // ===============
        $order_raw_data = $grouped->all();
        $report_arr_final=array();
        foreach ($order_raw_data as $key => $value) {
            $tmp_array1=array();
            foreach ($value as $key1 => $value1) {
                $tmp_array2=array();
                foreach ($value1 as $key2 => $value2) {
                    $tmp_array3=array();
                    foreach ($value2 as $key => $value3) {
                        $value3->fax_number = json_decode($value3->optional)->order->fax->number;
                        unset($value3->optional);
                        $tmp_array3[]=$value3;
                    }
                    $tmp_array2[]=$tmp_array3;
                }
                $tmp_array1[]=$tmp_array2;
            }
            $report_arr_final[]=$tmp_array1;
        }
        // foreach ($order_raw_data as $key => $value) {
        //     $tmp_array1=array();
        //     foreach ($value as $key1 => $value1) {
        //     $tmp_array2=array();
        //         foreach ($value1 as $key2 => $value2) {
        //             $value2->fax_number = json_decode($value2->optional)->order->fax->number;
        //             unset($value2->optional);
        //             $tmp_array2[]=$value2;
        //         }
        //         $tmp_array1[]=$tmp_array2;
        //     }
        //     $report_arr_final[]=$tmp_array1;
        // }
        Log::debug(__METHOD__.':end---');
        return $report_arr_final;
        // ==================
        // $collection = collect($order_data);

        // $grouped = $collection->groupBy('mes_lis_ord_tra_trade_number');

        // $aaa = $grouped->all();
        // $report_arr_count = count($aaa);
        // for ($i = 0; $i < $report_arr_count; $i++) {
        //     $step0 = array_keys($aaa)[$i];
        //     // =====
        //     for ($k = 0; $k < count($aaa[$step0]); $k++) {
        //         $aaa[$step0][$k]['fax_number'] = json_decode($aaa[$step0][$k]['optional'])->order->fax->number;
        //         unset($aaa[$step0][$k]['optional']);
        //     }
        //     // =====
        //     $step0_data_array = $aaa[$step0];

        //     $report_arr_final[] = $step0_data_array;
        // }
        // Log::debug(__METHOD__.':end---');
        // return $report_arr_final;
        // return ['status'=>1,'new_report_array'=>$report_arr_final];
    }
}
