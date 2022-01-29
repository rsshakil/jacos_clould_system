<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\ScenarioBase;

use App\Scenarios\Common;
use App\Models\DATA\RTN\data_return;
use App\Models\DATA\RTN\data_return_voucher;
use App\Models\DATA\RTN\data_return_item;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class data_csv_return extends ScenarioBase
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
        $file_info = $this->upfileSave($request, config('const.RETURN_DATA_PATH') . date('Y-m'));
        $cmn_connect_id = $file_info['cmn_connect_id'];

        // csv
        $dataArr = $this->all_functions->csvReader($file_info['save_path'], 1);

        // data check
        $this->checkCsvData($dataArr, 131);
        // $datetime = new \DateTime( "now", new \DateTimeZone(config('app.timezone')) );
        // $cur_date = $datetime->format( 'Y-m-d H:i:s' );
        $cur_date = date( 'Y-m-d H:i:s' );
        $rtn_flg = true;
        $trade_number = '';
        $voucher_key='';
        DB::beginTransaction();
        try {
            foreach ($dataArr as $key => $value) {
                if (count($value) === 1) {
                    // 空であればcontinue
                    continue;
                }

                if ($rtn_flg) {
                    $data_return_array['sta_sen_identifier']=$value[0];
                    $data_return_array['sta_sen_ide_authority']=$value[1];
                    $data_return_array['sta_rec_identifier']=$value[2];
                    $data_return_array['sta_rec_ide_authority']=$value[3];
                    $data_return_array['sta_doc_standard']=$value[4];
                    $data_return_array['sta_doc_type_version']=$value[5];
                    $data_return_array['sta_doc_instance_identifier']=$value[6];
                    $data_return_array['sta_doc_type']=$value[7];
                    $data_return_array['sta_doc_creation_date_and_time']=$value[8];
                    $data_return_array['sta_bus_scope_type']=$value[9];
                    $data_return_array['sta_bus_scope_instance_identifier']=$value[10];
                    $data_return_array['sta_bus_scope_identifier']=$value[11];
                    $data_return_array['mes_ent_unique_creator_identification']=$value[12];
                    $data_return_array['mes_mes_sender_station_address']=$value[13];
                    $data_return_array['mes_mes_ultimate_receiver_station_address']=$value[14];
                    $data_return_array['mes_mes_immediate_receiver_station_addres']=$value[15];
                    $data_return_array['mes_mes_number_of_trading_documents']=$value[16];
                    $data_return_array['mes_mes_sys_key']=$value[17];
                    $data_return_array['mes_mes_sys_value']=$value[18];
                    $data_return_array['mes_lis_con_version']=$value[19];
                    $data_return_array['mes_lis_doc_version']=$value[20];
                    $data_return_array['mes_lis_ext_namespace']=$value[21];
                    $data_return_array['mes_lis_ext_version']=$value[22];
                    $data_return_array['mes_lis_pay_code']=$value[23];
                    $data_return_array['mes_lis_pay_gln']=$value[24];
                    $data_return_array['mes_lis_pay_name']=$value[25];
                    $data_return_array['mes_lis_pay_name_sbcs']=$value[26];
                    // New Added
                    $data_return_array['mes_lis_ret_code']=$value[27];
                    $data_return_array['mes_lis_ret_gln']=$value[28];
                    $data_return_array['mes_lis_ret_name']=$value[29];
                    $data_return_array['mes_lis_ret_name_sbcs']=$value[30];
                    // New Added

                    // return
                    $data_return_array['receive_datetime']=$cur_date;
                    $data_return_array['receive_file_path']=$file_info['file_name'];
                    $data_return_array['cmn_connect_id']=$cmn_connect_id;

                    $data_return_id = data_return::insertGetId($data_return_array);

                    $rtn_flg =false;
                }

                if ($voucher_key != $value[31].'-'.$value[59]) {
                    $voucher_key = $value[31].'-'.$value[59];
                    $data_return_voucher_array['mes_lis_ret_tra_trade_number']=$value[31];
                    $data_return_voucher_array['mes_lis_ret_tra_additional_trade_number']=$value[32];
                    $data_return_voucher_array['mes_lis_ret_fre_shipment_number']=$value[33];
                    // New Added
                    $data_return_voucher_array['mes_lis_ret_par_return_receive_from_code']=$value[34];
                    $data_return_voucher_array['mes_lis_ret_par_return_receive_from_gln']=$value[35];
                    $data_return_voucher_array['mes_lis_ret_par_return_receive_from_name']=$value[36];
                    $data_return_voucher_array['mes_lis_ret_par_return_receive_from_name_sbcs']=$value[37];
                    $data_return_voucher_array['mes_lis_ret_par_return_from_code']=$value[38];
                    $data_return_voucher_array['mes_lis_ret_par_return_from_gln']=$value[39];
                    $data_return_voucher_array['mes_lis_ret_par_return_from_name']=$value[40];
                    $data_return_voucher_array['mes_lis_ret_par_return_from_name_sbcs']=$value[41];
                    // New Added
                    $data_return_voucher_array['mes_lis_ret_par_tra_code']=$value[42];
                    $data_return_voucher_array['mes_lis_ret_par_tra_gln']=$value[43];
                    $data_return_voucher_array['mes_lis_ret_par_tra_name']=$value[44];
                    $data_return_voucher_array['mes_lis_ret_par_tra_name_sbcs']=$value[45];
                    $data_return_voucher_array['mes_lis_ret_par_pay_code']=$value[46];
                    $data_return_voucher_array['mes_lis_ret_par_pay_gln']=$value[47];
                    $data_return_voucher_array['mes_lis_ret_par_pay_name']=$value[48];
                    $data_return_voucher_array['mes_lis_ret_par_pay_name_sbcs']=$value[49];
                    $data_return_voucher_array['mes_lis_ret_par_sel_code']=$value[50];
                    $data_return_voucher_array['mes_lis_ret_par_sel_gln']=$value[51];
                    $data_return_voucher_array['mes_lis_ret_par_sel_name']=$value[52];
                    $data_return_voucher_array['mes_lis_ret_par_sel_name_sbcs']=$value[53];
                    $data_return_voucher_array['mes_lis_ret_par_sel_branch_number']=$value[54];
                    $data_return_voucher_array['mes_lis_ret_par_sel_ship_location_code']=$value[55];
                    $data_return_voucher_array['mes_lis_ret_log_return_goods_transfer_type']=$value[56]; //New Added
                    $data_return_voucher_array['mes_lis_ret_tra_goo_major_category']=$value[57];
                    $data_return_voucher_array['mes_lis_ret_tra_goo_sub_major_category']=$value[58];
                    $data_return_voucher_array['mes_lis_ret_tra_dat_transfer_of_ownership_date']=$value[59];
                    $data_return_voucher_array['mes_lis_ret_tra_dat_checking_date']=$value[60]; //New Added
                    $data_return_voucher_array['mes_lis_ret_tra_dat_checking_date_code']=$value[61]; //New Added
                    $data_return_voucher_array['mes_lis_ret_tra_ins_goods_classification_code']=$value[62];
                    $data_return_voucher_array['mes_lis_ret_tra_ins_trade_type_code']=$value[63];
                    $data_return_voucher_array['mes_lis_ret_tra_ins_delivery_fee_exemption_code']=$value[64]; //New Added
                    $data_return_voucher_array['mes_lis_ret_tra_ins_paper_form_less_code']=$value[65];
                    $data_return_voucher_array['mes_lis_ret_tra_fre_trade_number_request_code']=$value[66];
                    $data_return_voucher_array['mes_lis_ret_tra_fre_package_code']=$value[67];
                    $data_return_voucher_array['mes_lis_ret_tra_fre_variable_measure_item_code']=$value[68];
                    $data_return_voucher_array['mes_lis_ret_tra_tax_tax_type_code']=$value[69];
                    $data_return_voucher_array['mes_lis_ret_tra_tax_tax_rate']=$value[70];
                    $data_return_voucher_array['mes_lis_ret_tra_package_number']=$value[71]; //New Added
                    $data_return_voucher_array['mes_lis_ret_tra_not_text']=$value[72];
                    $data_return_voucher_array['mes_lis_ret_tra_not_text_sbcs']=$value[73];
                    $data_return_voucher_array['mes_lis_ret_tot_tot_net_price_total']=$value[74];
                    $data_return_voucher_array['mes_lis_ret_tot_tot_selling_price_total']=$value[75];
                    $data_return_voucher_array['mes_lis_ret_tot_tot_tax_total']=$value[76];
                    $data_return_voucher_array['mes_lis_ret_tot_tot_item_total']=$value[77];
                    $data_return_voucher_array['mes_lis_ret_tot_fre_unit_weight_total']=$value[78];

                    $data_return_voucher_array['data_return_id']=$data_return_id;
                    $data_return_voucher_id = data_return_voucher::insertGetId($data_return_voucher_array);
                }
                $data_return_item_array['mes_lis_ret_lin_lin_line_number']=$value[79];
                $data_return_item_array['mes_lis_ret_lin_lin_additional_line_number']=$value[80];
                $data_return_item_array['mes_lis_ret_lin_fre_trade_number']=$value[81];
                $data_return_item_array['mes_lis_ret_lin_fre_line_number']=$value[82];
                $data_return_item_array['mes_lis_ret_lin_fre_shipment_line_number']=$value[83]; //New Added
                $data_return_item_array['mes_lis_ret_lin_goo_minor_category']=$value[84];
                $data_return_item_array['mes_lis_ret_lin_goo_detailed_category']=$value[85];
                $data_return_item_array['mes_lis_ret_lin_reason_code']=$value[86]; //New Added
                $data_return_item_array['mes_lis_ret_lin_ite_maker_code']=$value[87];
                $data_return_item_array['mes_lis_ret_lin_ite_gtin']=$value[88];
                $data_return_item_array['mes_lis_ret_lin_ite_order_item_code']=$value[89];
                $data_return_item_array['mes_lis_ret_lin_ite_code_type']=$value[90];
                $data_return_item_array['mes_lis_ret_lin_ite_supplier_item_code']=$value[91];
                $data_return_item_array['mes_lis_ret_lin_ite_name']=$value[92];
                $data_return_item_array['mes_lis_ret_lin_ite_name_sbcs']=$value[93];
                $data_return_item_array['mes_lis_ret_lin_fre_shipment_item_code']=$value[94]; //New Added
                $data_return_item_array['mes_lis_ret_lin_ite_ite_spec']=$value[95];
                $data_return_item_array['mes_lis_ret_lin_ite_ite_spec_sbcs']=$value[96];
                $data_return_item_array['mes_lis_ret_lin_ite_col_color_code']=$value[97];
                $data_return_item_array['mes_lis_ret_lin_ite_col_description']=$value[98];
                $data_return_item_array['mes_lis_ret_lin_ite_col_description_sbcs']=$value[99];
                $data_return_item_array['mes_lis_ret_lin_ite_siz_size_code']=$value[100];
                $data_return_item_array['mes_lis_ret_lin_ite_siz_description']=$value[101];
                $data_return_item_array['mes_lis_ret_lin_ite_siz_description_sbcs']=$value[102];

                $data_return_item_array['mes_lis_ret_lin_fre_packing_quantity']=$value[103];
                $data_return_item_array['mes_lis_ret_lin_fre_prefecture_code']=$value[104];
                $data_return_item_array['mes_lis_ret_lin_fre_country_code']=$value[105];
                $data_return_item_array['mes_lis_ret_lin_fre_field_name']=$value[106];
                $data_return_item_array['mes_lis_ret_lin_fre_water_area_code']=$value[107];
                $data_return_item_array['mes_lis_ret_lin_fre_water_area_name']=$value[108];
                $data_return_item_array['mes_lis_ret_lin_fre_area_of_origin']=$value[109];
                $data_return_item_array['mes_lis_ret_lin_fre_item_grade']=$value[110];
                $data_return_item_array['mes_lis_ret_lin_fre_item_class']=$value[111];
                $data_return_item_array['mes_lis_ret_lin_fre_brand']=$value[112];
                $data_return_item_array['mes_lis_ret_lin_fre_item_pr']=$value[113];
                $data_return_item_array['mes_lis_ret_lin_fre_bio_code']=$value[114];
                $data_return_item_array['mes_lis_ret_lin_fre_breed_code']=$value[115];
                $data_return_item_array['mes_lis_ret_lin_fre_cultivation_code']=$value[116];
                $data_return_item_array['mes_lis_ret_lin_fre_defrost_code']=$value[117];
                $data_return_item_array['mes_lis_ret_lin_fre_item_preservation_code']=$value[118];
                $data_return_item_array['mes_lis_ret_lin_fre_item_shape_code']=$value[119];
                $data_return_item_array['mes_lis_ret_lin_fre_use']=$value[120];
                $data_return_item_array['mes_lis_ret_lin_sta_statutory_classification_code']=$value[121];
                $data_return_item_array['mes_lis_ret_lin_amo_item_net_price']=$value[122];
                $data_return_item_array['mes_lis_ret_lin_amo_item_net_price_unit_price']=$value[123];
                $data_return_item_array['mes_lis_ret_lin_amo_item_selling_price']=$value[124];
                $data_return_item_array['mes_lis_ret_lin_amo_item_selling_price_unit_price']=$value[125];
                $data_return_item_array['mes_lis_ret_lin_amo_item_tax']=$value[126];
                // New Added
                $data_return_item_array['mes_lis_ret_lin_qua_quantity']=$value[127];
                $data_return_item_array['mes_lis_ret_lin_fre_unit_weight_code']=$value[128];
                $data_return_item_array['mes_lis_ret_lin_fre_item_weight']=$value[129];
                $data_return_item_array['mes_lis_ret_lin_fre_return_weight']=$value[130];
                // New Added

                $data_return_item_array['data_return_voucher_id']=$data_return_voucher_id;
                data_return_item::insert($data_return_item_array);


                // format
                $data_return_array=array();
                $data_return_voucher_array=array();
                $data_return_item_array=array();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        Log::debug(__METHOD__.':end---');
        return ['message' => '', 'status' => $this->success,'cmn_connect_id' => $cmn_connect_id,'data_id' => $data_return_id];
    }
}
