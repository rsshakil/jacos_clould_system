<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\ScenarioBase;

use App\Scenarios\Common;
use Illuminate\Database\Eloquent\Model;
use App\Models\DATA\RCV\data_receive;
use App\Models\DATA\RCV\data_receive_voucher;
use App\Models\DATA\RCV\data_receive_item;
use App\Models\DATA\CRCV\data_corrected_receive;
use App\Models\DATA\CRCV\data_corrected_receive_voucher;
use App\Models\DATA\CRCV\data_corrected_receive_item;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class data_csv_receive extends ScenarioBase
{
    private $all_functions;
    public function __construct()
    {
        parent::__construct();
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request, $sc)
    {
        Log::debug(__METHOD__.':start---');

        // file save
        $file_info = $this->upfileSave($request, config('const.RECEIVE_DATA_PATH') . date('Y-m'));
        $cmn_connect_id = $file_info['cmn_connect_id'];

        // csv
        $dataArr = $this->all_functions->csvReader($file_info['save_path'], 1);

        // data check
        $this->checkCsvData($dataArr, 172);
        $cur_date = date('Y-m-d H:i:s');
        $rcv_flg = true;
        $trade_number = '';
        $insert_flag=0;

        $data_receive_array=array();
        DB::beginTransaction();
        try {
            foreach ($dataArr as $key => $value) {
                // empty check
                if (count($value) === 1) {
                    // 空であればcontinue
                    continue;
                }
                // exist data check
                $exists_data = data_receive_voucher::join('data_receives', 'data_receives.data_receive_id', '=', 'data_receive_vouchers.data_receive_id')
                ->join('data_receive_items', 'data_receive_vouchers.data_receive_voucher_id', '=', 'data_receive_items.data_receive_voucher_id')
                ->where('data_receive_vouchers.mes_lis_acc_tra_dat_order_date', '=', $value[76])
                ->where('data_receive_vouchers.mes_lis_acc_tra_trade_number', '=', $value[31])
                ->where('data_receives.cmn_connect_id', '=', $cmn_connect_id)
                ->where('mes_lis_acc_lin_lin_line_number', '=', $value[106])
                ->select('data_receives.data_receive_id', 'data_receive_vouchers.data_receive_voucher_id', 'data_receive_items.data_receive_item_id')
                ->first();

                if ($rcv_flg) {
                    $data_receive_array['sta_sen_identifier']=$value[0];
                    $data_receive_array['sta_sen_ide_authority']=$value[1];
                    $data_receive_array['sta_rec_identifier']=$value[2];
                    $data_receive_array['sta_rec_ide_authority']=$value[3];
                    $data_receive_array['sta_doc_standard']=$value[4];
                    $data_receive_array['sta_doc_type_version']=$value[5];
                    $data_receive_array['sta_doc_instance_identifier']=$value[6];
                    $data_receive_array['sta_doc_type']=$value[7];
                    $data_receive_array['sta_doc_creation_date_and_time']=$value[8];
                    $data_receive_array['sta_bus_scope_type']=$value[9];
                    $data_receive_array['sta_bus_scope_instance_identifier']=$value[10];
                    $data_receive_array['sta_bus_scope_identifier']=$value[11];
                    $data_receive_array['mes_ent_unique_creator_identification']=$value[12];
                    $data_receive_array['mes_mes_sender_station_address']=$value[13];
                    $data_receive_array['mes_mes_ultimate_receiver_station_address']=$value[14];
                    $data_receive_array['mes_mes_immediate_receiver_station_addres']=$value[15];
                    $data_receive_array['mes_mes_number_of_trading_documents']=$value[16];
                    $data_receive_array['mes_mes_sys_key']=$value[17];
                    $data_receive_array['mes_mes_sys_value']=$value[18];
                    $data_receive_array['mes_lis_con_version']=$value[19];
                    $data_receive_array['mes_lis_doc_version']=$value[20];
                    $data_receive_array['mes_lis_ext_namespace']=$value[21];
                    $data_receive_array['mes_lis_ext_version']=$value[22];
                    $data_receive_array['mes_lis_pay_code']=$value[23];
                    $data_receive_array['mes_lis_pay_gln']=$value[24];
                    $data_receive_array['mes_lis_pay_name']=$value[25];
                    $data_receive_array['mes_lis_pay_name_sbcs']=$value[26];
                    $data_receive_array['mes_lis_buy_code']=$value[27];
                    $data_receive_array['mes_lis_buy_gln']=$value[28];
                    $data_receive_array['mes_lis_buy_name']=$value[29];
                    $data_receive_array['mes_lis_buy_name_sbcs']=$value[30];

                    // receive
                    $data_receive_array['receive_datetime']=$cur_date;
                    $data_receive_array['receive_file_path']=$file_info['file_name'];
                    $data_receive_array['cmn_connect_id']=$cmn_connect_id;


                    if ($exists_data) {
                        $data_receive_id = $exists_data->data_receive_id;
                        // data_receive::where('data_receive_id', $data_receive_id)->update($data_receive_array);
                    } else {
                        $data_receive_id = data_receive::insertGetId($data_receive_array);
                    }

                    $rcv_flg =false;
                }

                if ($trade_number !=$value[31].'-'.$value[32]) {
                    $trade_number = $value[31].'-'.$value[32];
                    $data_receive_voucher_array['mes_lis_acc_tra_trade_number']=$value[31];
                    $data_receive_voucher_array['mes_lis_acc_tra_additional_trade_number']=$value[32];
                    $data_receive_voucher_array['mes_lis_acc_fre_shipment_number']=$value[33]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_par_shi_code']=$value[34];
                    $data_receive_voucher_array['mes_lis_acc_par_shi_gln']=$value[35];
                    $data_receive_voucher_array['mes_lis_acc_par_shi_name']=$value[36];
                    $data_receive_voucher_array['mes_lis_acc_par_shi_name_sbcs']=$value[37];
                    $data_receive_voucher_array['mes_lis_acc_par_rec_code']=$value[38];
                    $data_receive_voucher_array['mes_lis_acc_par_rec_gln']=$value[39];
                    $data_receive_voucher_array['mes_lis_acc_par_rec_name']=$value[40];
                    $data_receive_voucher_array['mes_lis_acc_par_rec_name_sbcs']=$value[41];
                    $data_receive_voucher_array['mes_lis_acc_par_tra_code']=$value[42];
                    $data_receive_voucher_array['mes_lis_acc_par_tra_gln']=$value[43];
                    $data_receive_voucher_array['mes_lis_acc_par_tra_name']=$value[44];
                    $data_receive_voucher_array['mes_lis_acc_par_tra_name_sbcs']=$value[45];
                    $data_receive_voucher_array['mes_lis_acc_par_dis_code']=$value[46];
                    $data_receive_voucher_array['mes_lis_acc_par_dis_name']=$value[47];
                    $data_receive_voucher_array['mes_lis_acc_par_dis_name_sbcs']=$value[48];
                    $data_receive_voucher_array['mes_lis_acc_par_pay_code']=$value[49];
                    $data_receive_voucher_array['mes_lis_acc_par_pay_gln']=$value[50];
                    $data_receive_voucher_array['mes_lis_acc_par_pay_name']=$value[51];
                    $data_receive_voucher_array['mes_lis_acc_par_pay_name_sbcs']=$value[52];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_code']=$value[53];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_gln']=$value[54];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_name']=$value[55];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_name_sbcs']=$value[56];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_branch_number']=$value[57];
                    $data_receive_voucher_array['mes_lis_acc_par_sel_ship_location_code']=$value[58];
                    $data_receive_voucher_array['mes_lis_acc_log_shi_gln']=$value[59];
                    $data_receive_voucher_array['mes_lis_acc_log_del_route_code']=$value[60];
                    $data_receive_voucher_array['mes_lis_acc_log_del_delivery_service_code']=$value[61];
                    $data_receive_voucher_array['mes_lis_acc_log_del_stock_transfer_code']=$value[62];
                    $data_receive_voucher_array['mes_lis_acc_log_del_delivery_code']=$value[63];
                    $data_receive_voucher_array['mes_lis_acc_log_del_delivery_time']=$value[64];
                    $data_receive_voucher_array['mes_lis_acc_log_del_transportation_code']=$value[65];
                    $data_receive_voucher_array['mes_lis_acc_log_log_barcode_print']=$value[66];
                    $data_receive_voucher_array['mes_lis_acc_log_log_category_name_print1']=$value[67];
                    $data_receive_voucher_array['mes_lis_acc_log_log_category_name_print2']=$value[68];
                    $data_receive_voucher_array['mes_lis_acc_log_log_receiver_abbr_name']=$value[69];
                    $data_receive_voucher_array['mes_lis_acc_log_log_text']=$value[70];
                    $data_receive_voucher_array['mes_lis_acc_log_log_text_sbcs']=$value[71];
                    $data_receive_voucher_array['mes_lis_acc_log_maker_code_for_receiving']=$value[72]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_log_delivery_slip_number']=$value[73]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_tra_goo_major_category']=$value[74];
                    $data_receive_voucher_array['mes_lis_acc_tra_goo_sub_major_category']=$value[75];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_order_date']=$value[76];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_delivery_date']=$value[77];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_delivery_date_to_receiver']=$value[78];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_revised_delivery_date']=$value[79]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_revised_delivery_date_to_receiver']=$value[80]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_transfer_of_ownership_date']=$value[81];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_campaign_start_date']=$value[82];
                    $data_receive_voucher_array['mes_lis_acc_tra_dat_campaign_end_date']=$value[83];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_goods_classification_code']=$value[84];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_order_classification_code']=$value[85];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_ship_notification_request_code']=$value[86];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_eos_code']=$value[87]; //New Added
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_private_brand_code']=$value[88];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_temperature_code']=$value[89];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_liquor_code']=$value[90];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_trade_type_code']=$value[91];
                    $data_receive_voucher_array['mes_lis_acc_tra_ins_paper_form_less_code']=$value[92];
                    $data_receive_voucher_array['mes_lis_acc_tra_fre_trade_number_request_code']=$value[93];
                    $data_receive_voucher_array['mes_lis_acc_tra_fre_package_code']=$value[94];
                    $data_receive_voucher_array['mes_lis_acc_tra_fre_variable_measure_item_code']=$value[95];
                    $data_receive_voucher_array['mes_lis_acc_tra_tax_tax_type_code']=$value[96];
                    $data_receive_voucher_array['mes_lis_acc_tra_tax_tax_rate']=$value[97];
                    $data_receive_voucher_array['mes_lis_acc_tra_not_text']=$value[98];
                    $data_receive_voucher_array['mes_lis_acc_tra_not_text_sbcs']=$value[99];
                    $data_receive_voucher_array['mes_lis_acc_tot_tot_net_price_total']=$value[100];
                    $data_receive_voucher_array['mes_lis_acc_tot_tot_selling_price_total']=$value[101];
                    $data_receive_voucher_array['mes_lis_acc_tot_tot_tax_total']=$value[102];
                    $data_receive_voucher_array['mes_lis_acc_tot_tot_item_total']=$value[103];
                    $data_receive_voucher_array['mes_lis_acc_tot_tot_unit_total']=$value[104];
                    $data_receive_voucher_array['mes_lis_acc_tot_fre_unit_weight_total']=$value[105];
                    $data_receive_voucher_array['data_receive_id']=$data_receive_id;

                    if ($exists_data) {
                        $data_receive_voucher_id = $exists_data->data_receive_voucher_id;
                        $data_receive_voucher_array['update_datetime']=$cur_date;

                        if ($insert_flag==0) {
                            $data_receive_id = data_receive::insertGetId($data_receive_array);
                            $data_receive_voucher_array['data_receive_id']=$data_receive_id;
                            $insert_flag=1;
                        }

                        data_receive_voucher::where('data_receive_voucher_id', $data_receive_voucher_id)->update($data_receive_voucher_array);
                    } else {
                        $data_receive_voucher_id = data_receive_voucher::insertGetId($data_receive_voucher_array);
                    }
                }
                $data_receive_item_array['mes_lis_acc_lin_lin_line_number']=$value[106];
                $data_receive_item_array['mes_lis_acc_lin_lin_additional_line_number']=$value[107];
                $data_receive_item_array['mes_lis_acc_lin_fre_trade_number']=$value[108];
                $data_receive_item_array['mes_lis_acc_lin_fre_line_number']=$value[109];
                $data_receive_item_array['mes_lis_acc_lin_fre_shipment_line_number']=$value[110]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_goo_minor_category']=$value[111];
                $data_receive_item_array['mes_lis_acc_lin_goo_detailed_category']=$value[112];
                $data_receive_item_array['mes_lis_acc_lin_ite_scheduled_date']=$value[113];
                $data_receive_item_array['mes_lis_acc_lin_ite_deadline_date']=$value[114];
                $data_receive_item_array['mes_lis_acc_lin_ite_center_delivery_instruction_code']=$value[115];
                $data_receive_item_array['mes_lis_acc_lin_ite_maker_code']=$value[116];
                $data_receive_item_array['mes_lis_acc_lin_ite_gtin']=$value[117];
                $data_receive_item_array['mes_lis_acc_lin_ite_order_item_code']=$value[118];
                $data_receive_item_array['mes_lis_acc_lin_ite_ord_code_type']=$value[119];
                $data_receive_item_array['mes_lis_acc_lin_ite_supplier_item_code']=$value[120];
                $data_receive_item_array['mes_lis_acc_lin_ite_name']=$value[121];
                $data_receive_item_array['mes_lis_acc_lin_ite_name_sbcs']=$value[122];
                $data_receive_item_array['mes_lis_acc_lin_fre_shipment_item_code']=$value[123]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_ite_ite_spec']=$value[124];
                $data_receive_item_array['mes_lis_acc_lin_ite_ite_spec_sbcs']=$value[125];
                $data_receive_item_array['mes_lis_acc_lin_ite_col_color_code']=$value[126];
                $data_receive_item_array['mes_lis_acc_lin_ite_col_description']=$value[127];
                $data_receive_item_array['mes_lis_acc_lin_ite_col_description_sbcs']=$value[128];
                $data_receive_item_array['mes_lis_acc_lin_ite_siz_size_code']=$value[129];
                $data_receive_item_array['mes_lis_acc_lin_ite_siz_description']=$value[130];
                $data_receive_item_array['mes_lis_acc_lin_ite_siz_description_sbcs']=$value[131];
                $data_receive_item_array['mes_lis_acc_lin_fre_packing_quantity']=$value[132];
                $data_receive_item_array['mes_lis_acc_lin_fre_prefecture_code']=$value[133];
                $data_receive_item_array['mes_lis_acc_lin_fre_country_code']=$value[134];
                $data_receive_item_array['mes_lis_acc_lin_fre_field_name']=$value[135];
                $data_receive_item_array['mes_lis_acc_lin_fre_water_area_code']=$value[136];
                $data_receive_item_array['mes_lis_acc_lin_fre_water_area_name']=$value[137];
                $data_receive_item_array['mes_lis_acc_lin_fre_area_of_origin']=$value[138];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_grade']=$value[139];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_class']=$value[140];
                $data_receive_item_array['mes_lis_acc_lin_fre_brand']=$value[141];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_pr']=$value[142];
                $data_receive_item_array['mes_lis_acc_lin_fre_bio_code']=$value[143];
                $data_receive_item_array['mes_lis_acc_lin_fre_breed_code']=$value[144];
                $data_receive_item_array['mes_lis_acc_lin_fre_cultivation_code']=$value[145];
                $data_receive_item_array['mes_lis_acc_lin_fre_defrost_code']=$value[146];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_preservation_code']=$value[147];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_shape_code']=$value[148];
                $data_receive_item_array['mes_lis_acc_lin_fre_use']=$value[149];
                $data_receive_item_array['mes_lis_acc_lin_sta_statutory_classification_code']=$value[150];
                $data_receive_item_array['mes_lis_acc_lin_amo_item_net_price']=$value[151];
                $data_receive_item_array['mes_lis_acc_lin_amo_item_net_price_unit_price']=$value[152];
                $data_receive_item_array['mes_lis_acc_lin_amo_item_selling_price']=$value[153];
                $data_receive_item_array['mes_lis_acc_lin_amo_item_selling_price_unit_price']=$value[154];
                $data_receive_item_array['mes_lis_acc_lin_amo_item_tax']=$value[155];
                $data_receive_item_array['mes_lis_acc_lin_qua_unit_multiple']=$value[156];
                $data_receive_item_array['mes_lis_acc_lin_qua_unit_of_measure']=$value[157];
                $data_receive_item_array['mes_lis_acc_lin_qua_package_indicator']=$value[158];
                $data_receive_item_array['mes_lis_acc_lin_qua_ord_quantity']=$value[159];
                $data_receive_item_array['mes_lis_acc_lin_qua_ord_num_of_order_units']=$value[160];
                $data_receive_item_array['mes_lis_acc_lin_qua_shi_quantity']=$value[161]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_qua_shi_num_of_order_units']=$value[162]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_qua_rec_quantity']=$value[163]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_qua_rec_num_of_order_units']=$value[164]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_qua_rec_reason_code']=$value[165]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_fre_unit_weight']=$value[166];
                $data_receive_item_array['mes_lis_acc_lin_fre_unit_weight_code']=$value[167];
                $data_receive_item_array['mes_lis_acc_lin_fre_item_weight']=$value[168];
                $data_receive_item_array['mes_lis_acc_lin_fre_order_weight']=$value[169];
                $data_receive_item_array['mes_lis_acc_lin_fre_shipment_weight']=$value[170]; //New Added
                $data_receive_item_array['mes_lis_acc_lin_fre_received_weight']=$value[171]; //New Added
                // 172 done

                if ($exists_data) {
                    // \Log::info($exists_item);
                    $data_receive_item_id = $exists_data->data_receive_item_id;
                    data_receive_item::where('data_receive_item_id', $data_receive_item_id)->update($data_receive_item_array);
                } else {
                    $data_receive_item_array['data_receive_voucher_id']=$data_receive_voucher_id;
                    data_receive_item::insert($data_receive_item_array);
                }
                // format
                // $data_receive_array=array();
                $data_receive_voucher_array=array();
                $data_receive_item_array=array();

                $exists_data=null;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        Log::debug(__METHOD__.':end---');

        return ['message' => '', 'status' => $this->success,'cmn_connect_id' => $cmn_connect_id,'data_id' => $data_receive_id];
    }
}
