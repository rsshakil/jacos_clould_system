<?php

namespace App\Scenarios\LV3\SLR;

use App\Scenarios\ScenarioBase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\CMN\cmn_scenario;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use Illuminate\Support\Facades\Auth;

class OrderData extends ScenarioBase
{
    private $all_functions;
    public function __construct()
    {
        $this->all_functions = new AllUsedFunction();
    }
    public static function get_shipment_data($request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $scenario_id=$request->scenario_id;
        $scenario_info=cmn_scenario::select('byr_buyer_id')->where('cmn_scenario_id',$scenario_id)->first();
        // 対象データ取得
        $byr_buyer_id=$scenario_info->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $csv_data=data_shipment_voucher::select(
            // data_shipments
            'ds.sta_sen_identifier', //0
            'ds.sta_sen_ide_authority', //1
            'ds.sta_rec_identifier', //2
            'ds.sta_rec_ide_authority', //3
            'ds.sta_doc_standard', //4
            'ds.sta_doc_type_version', //5
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
            // data_shipment_vouchers
            'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
            'data_shipment_vouchers.mes_lis_shi_tra_additional_trade_number',
            'data_shipment_vouchers.mes_lis_shi_fre_shipment_number',
            'data_shipment_vouchers.mes_lis_shi_par_shi_code',
            'data_shipment_vouchers.mes_lis_shi_par_shi_gln',
            'data_shipment_vouchers.mes_lis_shi_par_shi_name',
            'data_shipment_vouchers.mes_lis_shi_par_shi_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_rec_code',
            'data_shipment_vouchers.mes_lis_shi_par_rec_gln',
            'data_shipment_vouchers.mes_lis_shi_par_rec_name',
            'data_shipment_vouchers.mes_lis_shi_par_rec_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_tra_code',
            'data_shipment_vouchers.mes_lis_shi_par_tra_gln',
            'data_shipment_vouchers.mes_lis_shi_par_tra_name',
            'data_shipment_vouchers.mes_lis_shi_par_tra_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_dis_code',
            'data_shipment_vouchers.mes_lis_shi_par_dis_name',
            'data_shipment_vouchers.mes_lis_shi_par_dis_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_pay_code',
            'data_shipment_vouchers.mes_lis_shi_par_pay_gln',
            'data_shipment_vouchers.mes_lis_shi_par_pay_name',
            'data_shipment_vouchers.mes_lis_shi_par_pay_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_sel_code',
            'data_shipment_vouchers.mes_lis_shi_par_sel_gln',
            'data_shipment_vouchers.mes_lis_shi_par_sel_name',
            'data_shipment_vouchers.mes_lis_shi_par_sel_name_sbcs',
            'data_shipment_vouchers.mes_lis_shi_par_sel_branch_number',
            'data_shipment_vouchers.mes_lis_shi_par_sel_ship_location_code',
            'data_shipment_vouchers.mes_lis_shi_log_shi_gln',
            'data_shipment_vouchers.mes_lis_shi_log_del_route_code',
            'data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code',
            'data_shipment_vouchers.mes_lis_shi_log_del_stock_transfer_code',
            'data_shipment_vouchers.mes_lis_shi_log_del_delivery_code',
            'data_shipment_vouchers.mes_lis_shi_log_del_delivery_time',
            'data_shipment_vouchers.mes_lis_shi_log_del_transportation_code',
            'data_shipment_vouchers.mes_lis_shi_log_log_barcode_print',
            'data_shipment_vouchers.mes_lis_shi_log_log_category_name_print1',
            'data_shipment_vouchers.mes_lis_shi_log_log_category_name_print2',
            'data_shipment_vouchers.mes_lis_shi_log_log_receiver_abbr_name',
            'data_shipment_vouchers.mes_lis_shi_log_log_text',
            'data_shipment_vouchers.mes_lis_shi_log_log_text_sbcs',
            'data_shipment_vouchers.mes_lis_shi_log_maker_code_for_receiving',
            'data_shipment_vouchers.mes_lis_shi_log_delivery_slip_number',
            'data_shipment_vouchers.mes_lis_shi_tra_goo_major_category',
            'data_shipment_vouchers.mes_lis_shi_tra_goo_sub_major_category',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_order_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_order_date  END as mes_lis_shi_tra_dat_order_date'), //58
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date  END as mes_lis_shi_tra_dat_delivery_date'), //59
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver  END as mes_lis_shi_tra_dat_delivery_date_to_receiver'), //60
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date  END as mes_lis_shi_tra_dat_revised_delivery_date'), //61
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_transfer_of_ownership_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_transfer_of_ownership_date  END as mes_lis_shi_tra_dat_transfer_of_ownership_date'), //62
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_campaign_start_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_campaign_start_date  END as mes_lis_shi_tra_dat_campaign_start_date'), //63
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_campaign_end_date="0000-00-00" THEN "" ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_campaign_end_date  END as mes_lis_shi_tra_dat_campaign_end_date'), //64
            'data_shipment_vouchers.mes_lis_shi_tra_ins_goods_classification_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_order_classification_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_ship_notification_request_code',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_ins_eos_code IS NULL THEN "01" WHEN data_shipment_vouchers.mes_lis_shi_tra_ins_eos_code="" THEN "01" ELSE data_shipment_vouchers.mes_lis_shi_tra_ins_eos_code  END as mes_lis_shi_tra_ins_eos_code'), //68
            'data_shipment_vouchers.mes_lis_shi_tra_ins_private_brand_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_liquor_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_trade_type_code',
            'data_shipment_vouchers.mes_lis_shi_tra_ins_paper_form_less_code',
            'data_shipment_vouchers.mes_lis_shi_tra_fre_trade_number_request_code',
            'data_shipment_vouchers.mes_lis_shi_tra_fre_package_code',
            'data_shipment_vouchers.mes_lis_shi_tra_fre_variable_measure_item_code',
            'data_shipment_vouchers.mes_lis_shi_tra_tax_tax_type_code',
            'data_shipment_vouchers.mes_lis_shi_tra_tax_tax_rate',
            'data_shipment_vouchers.mes_lis_shi_tra_not_text',
            'data_shipment_vouchers.mes_lis_shi_tra_not_text_sbcs',
            'data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total',
            'data_shipment_vouchers.mes_lis_shi_tot_tot_selling_price_total',
            'data_shipment_vouchers.mes_lis_shi_tot_tot_tax_total',
            'data_shipment_vouchers.mes_lis_shi_tot_tot_item_total',
            'data_shipment_vouchers.mes_lis_shi_tot_tot_unit_total',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tot_fre_unit_weight_total=null THEN "0" WHEN data_shipment_vouchers.mes_lis_shi_tot_fre_unit_weight_total="" THEN "0" ELSE data_shipment_vouchers.mes_lis_shi_tot_fre_unit_weight_total  END as mes_lis_shi_tot_fre_unit_weight_total'), //86
            // data_shipment_items
            'dsi.mes_lis_shi_lin_lin_line_number',
            'dsi.mes_lis_shi_lin_lin_additional_line_number',
            'dsi.mes_lis_shi_lin_fre_trade_number',
            'dsi.mes_lis_shi_lin_fre_line_number',
            'dsi.mes_lis_shi_lin_fre_shipment_line_number',
            'dsi.mes_lis_shi_lin_goo_minor_category',
            'dsi.mes_lis_shi_lin_goo_detailed_category',
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_ite_scheduled_date="0000-00-00" THEN "" ELSE dsi.mes_lis_shi_lin_ite_scheduled_date  END as mes_lis_shi_lin_ite_scheduled_date'), //94
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_ite_deadline_date="0000-00-00" THEN "" ELSE dsi.mes_lis_shi_lin_ite_deadline_date  END as mes_lis_shi_lin_ite_deadline_date'),   //95
            'dsi.mes_lis_shi_lin_ite_center_delivery_instruction_code',
            'dsi.mes_lis_shi_lin_fre_interim_price_code',
            'dsi.mes_lis_shi_lin_ite_maker_code',
            'dsi.mes_lis_shi_lin_ite_gtin',
            'dsi.mes_lis_shi_lin_ite_order_item_code',
            'dsi.mes_lis_shi_lin_ite_ord_code_type',
            'dsi.mes_lis_shi_lin_ite_supplier_item_code',
            'dsi.mes_lis_shi_lin_ite_name',
            'dsi.mes_lis_shi_lin_ite_name_sbcs',
            'dsi.mes_lis_shi_lin_fre_shipment_item_code',
            'dsi.mes_lis_shi_lin_ite_ite_spec',
            'dsi.mes_lis_shi_lin_ite_ite_spec_sbcs',
            'dsi.mes_lis_shi_lin_ite_col_color_code',
            'dsi.mes_lis_shi_lin_ite_col_description',
            'dsi.mes_lis_shi_lin_ite_col_description_sbcs',
            'dsi.mes_lis_shi_lin_ite_siz_size_code',
            'dsi.mes_lis_shi_lin_ite_siz_description',
            'dsi.mes_lis_shi_lin_ite_siz_description_sbcs',
            'dsi.mes_lis_shi_lin_fre_packing_quantity',
            'dsi.mes_lis_shi_lin_fre_prefecture_code',
            'dsi.mes_lis_shi_lin_fre_country_code',
            'dsi.mes_lis_shi_lin_fre_field_name',
            'dsi.mes_lis_shi_lin_fre_water_area_code',
            'dsi.mes_lis_shi_lin_fre_water_area_name',
            'dsi.mes_lis_shi_lin_fre_area_of_origin',
            'dsi.mes_lis_shi_lin_fre_item_grade',
            'dsi.mes_lis_shi_lin_fre_item_class',
            'dsi.mes_lis_shi_lin_fre_brand',
            'dsi.mes_lis_shi_lin_fre_item_pr',
            'dsi.mes_lis_shi_lin_fre_bio_code',
            'dsi.mes_lis_shi_lin_fre_breed_code',
            'dsi.mes_lis_shi_lin_fre_cultivation_code',
            'dsi.mes_lis_shi_lin_fre_defrost_code',
            'dsi.mes_lis_shi_lin_fre_item_preservation_code',
            'dsi.mes_lis_shi_lin_fre_item_shape_code',
            'dsi.mes_lis_shi_lin_fre_use',
            'dsi.mes_lis_shi_lin_sta_statutory_classification_code',
            'dsi.mes_lis_shi_lin_amo_item_net_price',
            'dsi.mes_lis_shi_lin_amo_item_net_price_unit_price',
            'dsi.mes_lis_shi_lin_amo_item_selling_price',
            'dsi.mes_lis_shi_lin_amo_item_selling_price_unit_price',
            'dsi.mes_lis_shi_lin_amo_item_tax',
            'dsi.mes_lis_shi_lin_qua_unit_multiple',
            'dsi.mes_lis_shi_lin_qua_unit_of_measure',
            'dsi.mes_lis_shi_lin_qua_package_indicator',
            'dsi.mes_lis_shi_lin_qua_ord_quantity',
            'dsi.mes_lis_shi_lin_qua_ord_num_of_order_units',
            'dsi.mes_lis_shi_lin_qua_shi_quantity',
            'dsi.mes_lis_shi_lin_qua_shi_num_of_order_units',
            'dsi.mes_lis_shi_lin_qua_sto_quantity',
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_qua_sto_num_of_order_units=null THEN "0" WHEN dsi.mes_lis_shi_lin_qua_sto_num_of_order_units="" THEN "0" ELSE dsi.mes_lis_shi_lin_qua_sto_num_of_order_units  END as mes_lis_shi_lin_qua_sto_num_of_order_units'), //146
            'dsi.mes_lis_shi_lin_qua_sto_reason_code',
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_fre_unit_weight=null THEN "0" WHEN dsi.mes_lis_shi_lin_fre_unit_weight="" THEN "0" ELSE dsi.mes_lis_shi_lin_fre_unit_weight  END as mes_lis_shi_lin_fre_unit_weight'), //148
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_fre_unit_weight_code=null THEN "0" WHEN dsi.mes_lis_shi_lin_fre_unit_weight_code="" THEN "0" ELSE dsi.mes_lis_shi_lin_fre_unit_weight_code  END as mes_lis_shi_lin_fre_unit_weight_code'), //149
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_fre_item_weight=null THEN "0" WHEN dsi.mes_lis_shi_lin_fre_item_weight="" THEN "0" ELSE dsi.mes_lis_shi_lin_fre_item_weight  END as mes_lis_shi_lin_fre_item_weight'), //150
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_fre_order_weight=null THEN "0" WHEN dsi.mes_lis_shi_lin_fre_order_weight="" THEN "0" ELSE dsi.mes_lis_shi_lin_fre_order_weight  END as mes_lis_shi_lin_fre_order_weight'), //151
            DB::raw('CASE WHEN dsi.mes_lis_shi_lin_fre_shipment_weight=null THEN "0" WHEN dsi.mes_lis_shi_lin_fre_shipment_weight="" THEN "0" ELSE dsi.mes_lis_shi_lin_fre_shipment_weight  END as mes_lis_shi_lin_fre_shipment_weight'), //152
            // data_shipment_item_details
            // DB::raw('CASE WHEN dsid.mes_lis_shi_lin_pac_itf_code=null THEN "0" WHEN dsid.mes_lis_shi_lin_pac_itf_code="" THEN "0" ELSE dsid.mes_lis_shi_lin_pac_itf_code  END as mes_lis_shi_lin_pac_itf_code'), //153
            // DB::raw('CASE WHEN dsid.mes_lis_shi_lin_pac_package_indicator=null THEN "00" WHEN dsid.mes_lis_shi_lin_pac_package_indicator="" THEN "00" ELSE dsid.mes_lis_shi_lin_pac_package_indicator  END as mes_lis_shi_lin_pac_package_indicator'), //154
            // DB::raw('CASE WHEN dsid.mes_lis_shi_lin_pac_number_of_packages=null THEN "0" WHEN dsid.mes_lis_shi_lin_pac_number_of_packages="" THEN "0" ELSE dsid.mes_lis_shi_lin_pac_number_of_packages  END as mes_lis_shi_lin_pac_number_of_packages'), //155
            'dsid.mes_lis_shi_lin_pac_itf_code',
            'dsid.mes_lis_shi_lin_pac_package_indicator',
            'dsid.mes_lis_shi_lin_pac_number_of_packages',
            DB::raw('CASE WHEN dsid.mes_lis_shi_lin_pac_con_sell_by_date="0000-00-00" THEN "" ELSE dsid.mes_lis_shi_lin_pac_con_sell_by_date  END as mes_lis_shi_lin_pac_con_sell_by_date'),  //156
            DB::raw('CASE WHEN dsid.mes_lis_shi_lin_pac_con_production_date="0000-00-00" THEN "" ELSE dsid.mes_lis_shi_lin_pac_con_production_date  END as mes_lis_shi_lin_pac_con_production_date'),   //157
            'dsid.mes_lis_shi_lin_pac_con_lot_number',
            'dov.data_order_voucher_id',
            // DB::raw('CASE WHEN dsv.mes_lis_shi_tra_dat_revised_delivery_date IS NULL THEN dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver ELSE dsv.mes_lis_shi_tra_dat_revised_delivery_date  END as delivery_date'), //for picking pdf
        )
        ->leftJoin('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->leftJoin('data_shipment_items as dsi', 'data_shipment_vouchers.data_shipment_voucher_id', '=', 'dsi.data_shipment_voucher_id')
        ->leftJoin('data_shipment_item_details as dsid', 'dsi.data_shipment_item_id', '=', 'dsid.data_shipment_item_id')
        ->join('data_order_vouchers as dov', 'dov.data_order_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_orders as dor', 'dor.data_order_id', '=', 'dov.data_order_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dor.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereNull('dov.check_datetime');
        // ->where('data_shipments.cmn_connect_id', $cmn_connect_id);

        $csv_data=$csv_data->orderBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number', "ASC");
        $csv_data=$csv_data->orderBy('dsi.mes_lis_shi_lin_lin_line_number', "ASC")
        ->take(config('const.DOWNLOAD_DATA_LIMIT'));
        // 検索
        $shipment_datas = $csv_data->get()->toArray();

        $shipment_data=array();
        $voucher_id_array=array();
        foreach ($shipment_datas as $key => $value) {
            //
            $voucher_id_array[]=$value['data_order_voucher_id'];
            unset($value['data_order_voucher_id']);
            $shipment_data[]=$value;
        }

        Log::debug(__METHOD__.':end---');
        $voucher_id_array=array_values(array_unique($voucher_id_array));
        // Log::info($voucher_id_array);
        // Log::info($csv_data->get()->toArray());
        // self::checkDateTimeChange($voucher_id_array);
        return ['shipment_data'=>$shipment_data,'total_data'=>$csv_data->count(),'voucher_id_array'=>$voucher_id_array];
        // return $csv_data;
    }

    public static function getOrderPdfData($request)
    {
        Log::debug(__METHOD__.':start---');
        $scenario_id=$request->scenario_id;
        $scenario_info=cmn_scenario::select('byr_buyer_id')->where('cmn_scenario_id',$scenario_id)->first();
        // 対象データ取得
        $byr_buyer_id=$scenario_info->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;

        $report_arr_final = array();
        $csv_data = data_order_voucher::select(
            'cc.optional',
            'data_order_vouchers.data_order_voucher_id',
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
            'doi.mes_lis_ord_lin_ite_name_sbcs',
            'doi.mes_lis_ord_lin_ite_order_item_code',
            'doi.mes_lis_ord_lin_qua_ord_quantity',
            'doi.mes_lis_ord_lin_amo_item_selling_price',
            'doi.mes_lis_ord_lin_amo_item_selling_price_unit_price',
            'doi.mes_lis_ord_lin_amo_item_net_price',
            'doi.mes_lis_ord_lin_amo_item_net_price_unit_price',
            'doi.mes_lis_ord_lin_lin_line_number'
        )
        ->join('data_orders as dor', 'dor.data_order_id', '=', 'data_order_vouchers.data_order_id')
        ->join('data_order_items as doi', 'doi.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
        ->join('data_shipment_vouchers as dsv', 'dsv.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dor.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereNull('data_order_vouchers.check_datetime');

        $csv_data=$csv_data->orderBy('dsv.mes_lis_shi_tra_trade_number', "ASC");
        $csv_data=$csv_data->orderBy('doi.mes_lis_ord_lin_lin_line_number', "ASC");
        $shipment_data=$csv_data->take(config('const.DOWNLOAD_DATA_LIMIT'))->get();
        // ==================================
        // return $shipment_data;
        // ===================
        $recs = new \Illuminate\Database\Eloquent\Collection($shipment_data);
        $grouped = $recs->groupBy(['mes_lis_ord_par_sel_code','mes_lis_ord_par_rec_code','mes_lis_ord_tra_trade_number']);
        $all_shipment_data = $grouped->all();
        // return $all_shipment_data;
        $report_arr_final=array();
        foreach ($all_shipment_data as $key => $value) {
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

        $data_collection = collect($shipment_data);
        $data_grouped = $data_collection->groupBy('data_order_voucher_id');
        $data_grouped=$data_grouped->all();
        $voucher_id_arr=array();
        for ($i=0; $i <count($data_grouped) ; $i++) {
            $step0=array_keys($data_grouped)[$i];
            $voucher_id_arr[]=$step0;
        }
        // Log::info($voucher_id_arr);
        // self::checkDateTimeChange($voucher_id_arr);
        // ==========For Voucher Id End==============
        Log::debug(__METHOD__.':end---');
        return ['report_arr_final'=>$report_arr_final,'voucher_id_array'=>$voucher_id_arr,'raw_shipment_data'=>$shipment_data];
    }

    public static function shipmentCsvHeading()
    {
        return [
            '送信者ＩＤ',
            '送信者ＩＤ発行元',
            '受信者ＩＤ',
            '受信者ＩＤ発行元',
            '標準名称',
            'バージョン',
            'インスタンスＩＤ',
            'メッセージ種',
            '作成日時',
            'タイプ',
            'テスト区分ＩＤ',
            '最終送信先ＩＤ',
            'メッセージ識別ＩＤ',
            '送信者ステーションアドレス',
            '最終受信者ステーションアドレス',
            '直接受信者ステーションアドレス',
            '取引数',
            'システム情報キー',
            'システム情報値',
            'バージョン番号',
            'バージョン番号',
            '名前空間',
            'バージョン',
            '支払法人コード',
            '支払法人GLN',
            '支払法人名称',
            '支払法人名称カナ',
            '発注者コード',
            '発注者GLN',
            '発注者名称',
            '発注者名称カナ',
            '取引番号（発注・返品）',
            '取引付属番号',
            '出荷者管理番号',
            '直接納品先コード',
            '直接納品先GLN',
            '直接納品先名称',
            '直接納品先名称カナ',
            '最終納品先コード',
            '最終納品先GLN',
            '最終納品先名称',
            '最終納品先名称カナ',
            '計上部署コード',
            '計上部署GLN',
            '計上部署名称',
            '計上部署名称（カナ）',
            '陳列場所コード',
            '陳列場所名称',
            '陳列場所名称カナ',
            '請求取引先コード',
            '請求取引先GLN',
            '請求取引先名',
            '請求取引先名カナ',
            '取引先コード',
            '取引先GLN',
            '取引先名称',
            '取引先名称カナ',
            '枝番',
            '出荷先コード',
            '出荷場所GLN',
            '納品経路',
            '便No',
            '通過在庫区分',
            '納品区分',
            '指定納品時刻',
            '輸送手段',
            'バーコード情報',
            'カテゴリー名称1（印字用）',
            'カテゴリー名称2（印字用）',
            '最終納品先略称（印字用）',
            'ラベル自由使用欄（印字用）',
            'ラベル自由使用欄半角カナ（印字用）',
            '入荷管理用メーカーコード',
            'センター納品書番号',
            '商品分類（大）',
            '商品分類（中）',
            '発注日',
            '直接納品先納品日',
            '最終納品先納品日',
            '訂正後直接納品先納品日',
            '計上日',
            '販促開始日',
            '販促終了日',
            '商品区分',
            '発注区分',
            '出荷データ有無区分',
            'EOS区分',
            'PB区分',
            '配送温度区分',
            '酒区分',
            '処理種別',
            '伝票レス区分',
            '取引番号区分',
            'パック区分',
            '不定貫区分',
            '税区分',
            '税率',
            '自由使用欄',
            '自由使用欄半角カナ',
            '原価金額合計',
            '売価金額合計',
            '税額合計金額',
            '数量合計',
            '発注単位数量合計',
            '重量合計',
            '取引明細番号（発注・返品）',
            '取引付属明細番号',
            '元取引番号',
            '元取引明細番号',
            '出荷者管理明細番号',
            '商品分類（小）',
            '商品分類（細）',
            '配達予定日',
            '納品期限',
            'センター納品詳細指示',
            '仮伝フラグ',
            'メーカーコード',
            '商品コード（GTIN）',
            '商品コード（発注用）',
            '商品コード区分',
            '商品コード（取引先）',
            '商品名',
            '商品名カナ',
            '商品コード（出荷元）',
            '規格',
            '規格カナ',
            'カラーコード',
            'カラー名称',
            'カラー名称カナ',
            'サイズコード',
            'サイズ名称',
            'サイズ名称カナ',
            '入数',
            '都道府県コード',
            '国コード',
            '産地名',
            '水域コード',
            '水域名',
            '原産エリア',
            '等級',
            '階級',
            '銘柄',
            '商品ＰＲ',
            'バイオ区分',
            '品種コード',
            '養殖区分',
            '解凍区分',
            '商品状態区分',
            '形状・部位',
            '用途',
            '法定管理義務商材区分',
            '原価金額',
            '原単価',
            '売価金額',
            '売単価',
            '税額',
            '発注単位',
            '発注単位コード',
            '発注荷姿コード',
            '発注数量（バラ）',
            '発注数量（発注単位数）',
            '出荷数量（バラ）',
            '出荷数量（発注単位数）',
            '欠品数量(バラ数)',
            '欠品数量(発注単位数)',
            '欠品区分',
            '取引単位重量',
            '単価登録単位',
            '商品重量',
            '発注重量',
            '出荷重量',
            'ITFコード(集合包装GTIN)',
            '出荷荷姿コード',
            '出荷数量（出荷荷姿数）',
            '賞味期限日',
            '製造日',
            '製造番号'
        ];
    }

    public static function checkDateTimeChange($voucher_id_array){
        $today=date('y-m-d H:i:s');
        foreach ($voucher_id_array as $key => $voucher_id) {
            data_order_voucher::where('data_order_voucher_id',$voucher_id)
            ->whereNull('check_datetime')
            ->update(['check_datetime'=>$today]);
        }
    }
}
