<?php

namespace App\Http\Controllers\API\DATA;

// use DB;
use Session;
use App\Http\Controllers\Controller;
use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\SHIPMENT\data_shipment;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Data_Controller extends Controller
{
    private $all_functions;
    public function __construct()
    {
        $this->all_functions = new AllUsedFunction();
    }

    public static function get_order_data($request)
    {
        Log::debug(__METHOD__.':start---');

        // 対象データ取得
        $do = data_order_voucher::select(
            'sta_sen_identifier',
            'sta_sen_ide_authority',
            'sta_rec_identifier',
            'sta_rec_ide_authority',
            'sta_doc_standard',
            'sta_doc_type_version',
            'sta_doc_instance_identifier',
            'sta_doc_type',
            'sta_doc_creation_date_and_time',
            'sta_bus_scope_type',
            'sta_bus_scope_instance_identifier',
            'sta_bus_scope_identifier',
            'mes_ent_unique_creator_identification',
            'mes_mes_sender_station_address',
            'mes_mes_ultimate_receiver_station_address',
            'mes_mes_immediate_receiver_station_addres',
            'mes_mes_number_of_trading_documents',
            'mes_mes_sys_key',
            'mes_mes_sys_value',
            'mes_lis_con_version',
            'mes_lis_doc_version',
            'mes_lis_ext_namespace',
            'mes_lis_ext_version',
            'mes_lis_pay_code',
            'mes_lis_pay_gln',
            'mes_lis_pay_name',
            'mes_lis_pay_name_sbcs',
            'mes_lis_buy_code',
            'mes_lis_buy_gln',
            'mes_lis_buy_name',
            'mes_lis_buy_name_sbcs',
            'mes_lis_ord_tra_trade_number',
            'mes_lis_ord_tra_additional_trade_number',
            'mes_lis_ord_par_shi_code',
            'mes_lis_ord_par_shi_gln',
            'mes_lis_ord_par_shi_name',
            'mes_lis_ord_par_shi_name_sbcs',
            'mes_lis_ord_par_rec_code',
            'mes_lis_ord_par_rec_gln',
            'mes_lis_ord_par_rec_name',
            'mes_lis_ord_par_rec_name_sbcs',
            'mes_lis_ord_par_tra_code',
            'mes_lis_ord_par_tra_gln',
            'mes_lis_ord_par_tra_name',
            'mes_lis_ord_par_tra_name_sbcs',
            'mes_lis_ord_par_dis_code',
            'mes_lis_ord_par_dis_name',
            'mes_lis_ord_par_dis_name_sbcs',
            'mes_lis_ord_par_pay_code',
            'mes_lis_ord_par_pay_gln',
            'mes_lis_ord_par_pay_name',
            'mes_lis_ord_par_pay_name_sbcs',
            'mes_lis_ord_par_sel_code',
            'mes_lis_ord_par_sel_gln',
            'mes_lis_ord_par_sel_name',
            'mes_lis_ord_par_sel_name_sbcs',
            'mes_lis_ord_par_sel_branch_number',
            'mes_lis_ord_par_sel_ship_location_code',
            'mes_lis_ord_log_shi_gln',
            'mes_lis_ord_log_del_route_code',
            'mes_lis_ord_log_del_delivery_service_code',
            'mes_lis_ord_log_del_stock_transfer_code',
            'mes_lis_ord_log_del_delivery_code',
            'mes_lis_ord_log_del_delivery_time',
            'mes_lis_ord_log_del_transportation_code',
            'mes_lis_ord_log_log_barcode_print',
            'mes_lis_ord_log_log_category_name_print1',
            'mes_lis_ord_log_log_category_name_print2',
            'mes_lis_ord_log_log_receiver_abbr_name',
            'mes_lis_ord_log_log_text',
            'mes_lis_ord_log_log_text_sbcs',
            'mes_lis_ord_tra_goo_major_category',
            'mes_lis_ord_tra_goo_sub_major_category',
            'mes_lis_ord_tra_dat_order_date',
            'mes_lis_ord_tra_dat_delivery_date',
            'mes_lis_ord_tra_dat_delivery_date_to_receiver',
            'mes_lis_ord_tra_dat_transfer_of_ownership_date',
            'mes_lis_ord_tra_dat_campaign_start_date',
            'mes_lis_ord_tra_dat_campaign_end_date',
            'mes_lis_ord_tra_dat_valid_until_date',
            'mes_lis_ord_tra_ins_goods_classification_code',
            'mes_lis_ord_tra_ins_order_classification_code',
            'mes_lis_ord_tra_ins_ship_notification_request_code',
            'mes_lis_ord_tra_ins_private_brand_code',
            'mes_lis_ord_tra_ins_temperature_code',
            'mes_lis_ord_tra_ins_liquor_code',
            'mes_lis_ord_tra_ins_trade_type_code',
            'mes_lis_ord_tra_ins_paper_form_less_code',
            'mes_lis_ord_tra_fre_trade_number_request_code',
            'mes_lis_ord_tra_fre_package_code',
            'mes_lis_ord_tra_fre_variable_measure_item_code',
            'mes_lis_ord_tra_tax_tax_type_code',
            'mes_lis_ord_tra_tax_tax_rate',
            'mes_lis_ord_tra_not_text',
            'mes_lis_ord_tra_not_text_sbcs',
            'mes_lis_ord_tot_tot_net_price_total',
            'mes_lis_ord_tot_tot_selling_price_total',
            'mes_lis_ord_tot_tot_tax_total',
            'mes_lis_ord_tot_tot_item_total',
            'mes_lis_ord_tot_tot_unit_total',
            'mes_lis_ord_tot_fre_unit_weight_total',
            'mes_lis_ord_lin_lin_line_number',
            'mes_lis_ord_lin_lin_additional_line_number',
            'mes_lis_ord_lin_fre_trade_number',
            'mes_lis_ord_lin_fre_line_number',
            'mes_lis_ord_lin_goo_minor_category',
            'mes_lis_ord_lin_goo_detailed_category',
            'mes_lis_ord_lin_ite_scheduled_date',
            'mes_lis_ord_lin_ite_deadline_date',
            'mes_lis_ord_lin_ite_center_delivery_instruction_code',
            'mes_lis_ord_lin_ite_maker_code',
            'mes_lis_ord_lin_ite_gtin',
            'mes_lis_ord_lin_ite_order_item_code',
            'mes_lis_ord_lin_ite_ord_code_type',
            'mes_lis_ord_lin_ite_supplier_item_code',
            'mes_lis_ord_lin_ite_name',
            'mes_lis_ord_lin_ite_name_sbcs',
            'mes_lis_ord_lin_ite_ite_spec',
            'mes_lis_ord_lin_ite_ite_spec_sbcs',
            'mes_lis_ord_lin_ite_col_color_code',
            'mes_lis_ord_lin_ite_col_description',
            'mes_lis_ord_lin_ite_col_description_sbcs',
            'mes_lis_ord_lin_ite_siz_size_code',
            'mes_lis_ord_lin_ite_siz_description',
            'mes_lis_ord_lin_ite_siz_description_sbcs',
            'mes_lis_ord_lin_fre_packing_quantity',
            'mes_lis_ord_lin_fre_prefecture_code',
            'mes_lis_ord_lin_fre_country_code',
            'mes_lis_ord_lin_fre_field_name',
            'mes_lis_ord_lin_fre_water_area_code',
            'mes_lis_ord_lin_fre_water_area_name',
            'mes_lis_ord_lin_fre_area_of_origin',
            'mes_lis_ord_lin_fre_item_grade',
            'mes_lis_ord_lin_fre_item_class',
            'mes_lis_ord_lin_fre_brand',
            'mes_lis_ord_lin_fre_item_pr',
            'mes_lis_ord_lin_fre_bio_code',
            'mes_lis_ord_lin_fre_breed_code',
            'mes_lis_ord_lin_fre_cultivation_code',
            'mes_lis_ord_lin_fre_defrost_code',
            'mes_lis_ord_lin_fre_item_preservation_code',
            'mes_lis_ord_lin_fre_item_shape_code',
            'mes_lis_ord_lin_fre_use',
            'mes_lis_ord_lin_sta_statutory_classification_code',
            'mes_lis_ord_lin_amo_item_net_price',
            'mes_lis_ord_lin_amo_item_net_price_unit_price',
            'mes_lis_ord_lin_amo_item_selling_price',
            'mes_lis_ord_lin_amo_item_selling_price_unit_price',
            'mes_lis_ord_lin_amo_item_tax',
            'mes_lis_ord_lin_qua_unit_multiple',
            'mes_lis_ord_lin_qua_unit_of_measure',
            'mes_lis_ord_lin_qua_package_indicator',
            'mes_lis_ord_lin_qua_ord_quantity',
            'mes_lis_ord_lin_qua_ord_num_of_order_units',
            'mes_lis_ord_lin_fre_unit_weight',
            'mes_lis_ord_lin_fre_unit_weight_code',
            'mes_lis_ord_lin_fre_item_weight',
            'mes_lis_ord_lin_fre_order_weight'
        )
            ->join('data_orders', 'data_orders.data_order_id', '=', 'data_order_vouchers.data_order_id')
            ->join('data_order_items', 'data_order_items.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id')
            ->join('data_shipment_vouchers', 'data_shipment_vouchers.data_order_voucher_id', '=', 'data_order_vouchers.data_order_voucher_id');

        // 受信日(開始)
        $receive_date_from = $request->get('receive_date_from');
        if ($receive_date_from) {
            $do->where('data_orders.receive_datetime', '>=', $receive_date_from);
        }
        // 受信日(終了)
        $receive_date_to = $request->get('receive_date_to');
        if ($receive_date_to) {
            $do->where('data_orders.receive_datetime', '<=', $receive_date_to);
        }
        // 納品日(開始)
        $delivery_date_from = $request->get('delivery_date_from');
        if ($delivery_date_from) {
            $do->where('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver', '>=', $delivery_date_from);
        }
        // 納品日(終了)
        $delivery_date_to = $request->get('delivery_date_to');
        if ($delivery_date_to) {
            $do->where('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver', '<=', $delivery_date_to);
        }
        // 便
        $delivery_service_code = $request->get('delivery_service_code');
        if ($delivery_service_code) {
            $do->where('data_order_vouchers.mes_lis_ord_log_del_delivery_service_code', $delivery_service_code);
        }
        // 配達温度区分
        $temperature = $request->get('temperature');
        if ($temperature) {
            $do->where('data_order_vouchers.mes_lis_ord_tra_ins_temperature_code', $temperature);
        }

        // TODO 取引先コード
        // TODO 部門

        // 参照状況
        $confirmation_status = $request->get('confirmation_status');
        if ($confirmation_status) {
            $do->whereNotNull('data_order_vouchers.check_datetime');
        }
        // 確定状況
        $decision_status = $request->get('decision_status');
        if ($decision_status) {
            $do->whereNotNull('data_shipment_vouchers.decision_datetime');
        }
        // 印刷状況
        $print_status = $request->get('print_status');
        if ($print_status) {
            $do->whereNotNull('data_shipment_vouchers.print_datetime');
        }
        // 送信状況
        $send_status = $request->get('send_status');
        if ($send_status) {
            $do->whereNotNull('data_shipment_vouchers.print_datetime');
        }

        // 検索
        $do = $do->limit(config('const.DOWNLOAD_DATA_LIMIT'))->get()->toArray();

        Log::debug(__METHOD__.':end---');

        return $do;
    }
    public static function get_shipment_data($request)
    {
        Log::debug(__METHOD__.':start---');

        // 対象データ取得
        $byr_buyer_id=$request->byr_buyer_id;
        $data_order_id=$request->data_order_id;
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
        ->where('cc.slr_seller_id', $slr_seller_id);
        // ->where('data_shipments.cmn_connect_id', $cmn_connect_id);


        // filtering
        if ($request->page_title=='order_list') {
            $receive_date_from = $request->receive_date_from;
            $receive_date_to = $request->receive_date_to;
            $delivery_date_from = $request->delivery_date_from;
            $delivery_date_to = $request->delivery_date_to;
            $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $delivery_date_from = $delivery_date_from!=null? date('Y-m-d 00:00:00', strtotime($delivery_date_from)):config('const.FROM_DATETIME'); // 納品日開始
            $delivery_date_to =$delivery_date_to!=null? date('Y-m-d 23:59:59', strtotime($delivery_date_to)):config('const.TO_DATETIME'); // 納品日終了
            // 納品日終了
            $delivery_service_code = $request->delivery_service_code; // 便
            $temperature = $request->temperature; // 配送温度区分
            $check_datetime = $request->check_datetime;
            // $check_datetime=$request->check_datetime;
            $confirmation_status = $request->confirmation_status; // 参照
            $decission_cnt = $request->decission_cnt; // 確定
            $send_cnt = $request->send_cnt; // 印刷
            $byr_category_code = $request->category_code; // 印刷
            $mes_lis_ord_par_sel_code = $request->mes_lis_ord_par_sel_code; // 印刷
            $trade_number = $request->trade_number;


            $byr_category_code = $byr_category_code['category_code'];

            $csv_data= $csv_data->whereBetween('dor.receive_datetime', [$receive_date_from, $receive_date_to])
            ->whereBetween('dov.mes_lis_ord_tra_dat_delivery_date_to_receiver', [$delivery_date_from, $delivery_date_to]);
            if ($trade_number!=null) {
                $csv_data= $csv_data->where('dov.mes_lis_ord_tra_trade_number', $trade_number);
            }
            if ($delivery_service_code!='*') {
                $csv_data=$csv_data->where('dov.mes_lis_ord_log_del_delivery_service_code', $delivery_service_code);
            }
            if ($mes_lis_ord_par_sel_code!='') {
                $csv_data=$csv_data->where('dov.mes_lis_ord_par_sel_code', $mes_lis_ord_par_sel_code);
            }

            if ($temperature!='*') {
                $csv_data=$csv_data->where('dov.mes_lis_ord_tra_ins_temperature_code', $temperature);
            }

            if ($byr_category_code!='*') {
                $csv_data=$csv_data->where('dov.mes_lis_ord_tra_goo_major_category', $byr_category_code);
            }

            // check datetime
            if ($check_datetime!='*') {
                if ($check_datetime==1) {
                    $csv_data=$csv_data->whereNull('dov.check_datetime');
                } else {
                    $csv_data=$csv_data->whereNotNull('dov.check_datetime');
                }
            }

            // decision_datetime
            if ($decission_cnt == "!0") {
                // $csv_data=$csv_data->having('decision_cnt', '!=', '0');
                $csv_data=$csv_data->whereNull('data_shipment_vouchers.decision_datetime');
            } elseif ($decission_cnt != "*") {
                // $csv_data=$csv_data->having('decision_cnt', '=', $decission_cnt);
                $csv_data=$csv_data->whereNotNull('data_shipment_vouchers.decision_datetime');
            }

            // send_datetime
            if ($send_cnt == "!0") {
                // $csv_data=$csv_data->having('send_cnt', '!=', '0');
                $csv_data=$csv_data->whereNull('data_shipment_vouchers.send_datetime');
            } elseif ($send_cnt != "*") {
                // $csv_data=$csv_data->having('send_cnt', '=', $send_cnt);
                $csv_data=$csv_data->whereNotNull('data_shipment_vouchers.send_datetime');
            }
        } elseif ($request->page_title=='order_detail_list') {
            // return $request->all();
            $order_info=$request->order_info;
            $par_shi_code = $request->par_shi_code;
            $par_rec_code = $request->par_rec_code;
            $order_item_code = $request->order_item_code;
            $mes_lis_shi_tra_trade_number=$request->mes_lis_shi_tra_trade_number;
            $fixedSpecial=$request->fixedSpecial;
            $printingStatus=$request->printingStatus;
            $situation=$request->situation;
            $send_datetime=$request->send_datetime;

            $csv_data=$csv_data->where('ds.data_order_id', $data_order_id)
            ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $order_info['mes_lis_shi_log_del_delivery_service_code'])
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $order_info['mes_lis_shi_par_sel_code'])
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_name', $order_info['mes_lis_shi_par_sel_name'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $order_info['mes_lis_shi_tra_goo_major_category'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $order_info['mes_lis_shi_tra_ins_temperature_code']);
            if ($request->send_data==false) {
                if ($mes_lis_shi_tra_trade_number!=null) {
                    $csv_data = $csv_data->where('data_shipment_vouchers.mes_lis_shi_tra_trade_number', $mes_lis_shi_tra_trade_number);
                }
                if ($fixedSpecial!="*") {
                    $csv_data = $csv_data->where('data_shipment_vouchers.mes_lis_shi_tra_ins_goods_classification_code', $fixedSpecial);
                }
                if ($printingStatus!="*") {
                    if ($printingStatus=="未印刷あり") {
                        $csv_data = $csv_data->whereNull('data_shipment_vouchers.print_datetime');
                    }
                    if ($fixedSpecial!="*") {
                        $csv_data = $csv_data->where('dsv.mes_lis_shi_tra_ins_goods_classification_code', $fixedSpecial);
                    }
                }
                if ($situation!="*") {
                    if ($situation=="未確定あり") {
                        $csv_data = $csv_data->whereNull('data_shipment_vouchers.decision_datetime');
                    }
                    if ($situation=="確定済") {
                        $csv_data = $csv_data->whereNotNull('data_shipment_vouchers.decision_datetime');
                    }
                }
                if ($send_datetime!="*") {
                    if ($send_datetime=="未送信あり") {
                        $csv_data = $csv_data->whereNull('data_shipment_vouchers.send_datetime');
                    }
                    if ($send_datetime=="送信済") {
                        $csv_data = $csv_data->whereNotNull('data_shipment_vouchers.send_datetime');
                    }
                }
                if ($par_shi_code!=null) {
                    $csv_data = $csv_data->where('data_shipment_vouchers.mes_lis_shi_par_shi_code', $par_shi_code);
                }
                if ($par_rec_code!=null) {
                    $csv_data = $csv_data->where('data_shipment_vouchers.mes_lis_shi_par_rec_code', $par_rec_code);
                }
                if ($order_item_code!=null) {
                    $csv_data = $csv_data->where('dsi.mes_lis_shi_lin_ite_order_item_code', $order_item_code);
                }
            }
            // ========
            // send_data
            else if ($request->send_data==true) {
                $csv_data = $csv_data->whereNotNull('data_shipment_vouchers.decision_datetime')
                ->whereNull('data_shipment_vouchers.send_datetime');
            }
            // =======
        }
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
        self::checkDateTimeChange($voucher_id_array);
        return ['shipment_data'=>$shipment_data,'total_data'=>$csv_data->count(),'voucher_id_array'=>$voucher_id_array];
        // return $csv_data;
    }

    public static function getOrderPdfData($request)
    {
        Log::debug(__METHOD__.':start---');
        $data_order_id=$request->data_order_id;
        $byr_buyer_id=$request->byr_buyer_id;
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
        ->where('cc.slr_seller_id', $slr_seller_id);

        // ==================================
        if ($request->page_title=='order_list') {
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
            $byr_category_code = $request->category_code; // 部門
            $mes_lis_ord_par_sel_code = $request->mes_lis_ord_par_sel_code; // 取引先コード
            $trade_number = $request->trade_number;


            $byr_category_code = $byr_category_code['category_code'];


            $csv_data= $csv_data->whereBetween('dor.receive_datetime', [$receive_date_from, $receive_date_to])
            ->whereBetween('data_order_vouchers.mes_lis_ord_tra_dat_delivery_date_to_receiver', [$delivery_date_from, $delivery_date_to]);
            if ($trade_number!=null) {
                $csv_data= $csv_data->where('data_order_vouchers.mes_lis_ord_tra_trade_number', $trade_number);
            }
            if ($delivery_service_code!='*') {
                $csv_data=$csv_data->where('data_order_vouchers.mes_lis_ord_log_del_delivery_service_code', $delivery_service_code);
            }
            if ($mes_lis_ord_par_sel_code!='') {
                $csv_data=$csv_data->where('data_order_vouchers.mes_lis_ord_par_sel_code', $mes_lis_ord_par_sel_code);
            }

            if ($temperature!='*') {
                $csv_data=$csv_data->where('data_order_vouchers.mes_lis_ord_tra_ins_temperature_code', $temperature);
            }

            if ($byr_category_code!='*') {
                $csv_data=$csv_data->where('data_order_vouchers.mes_lis_ord_tra_goo_major_category', $byr_category_code);
            }

            if ($check_datetime!='*') {
                if ($check_datetime==1) {
                    $csv_data=$csv_data->whereNull('data_order_vouchers.check_datetime');
                } else {
                    $csv_data=$csv_data->whereNotNull('data_order_vouchers.check_datetime');
                }
            }
            // decision_datetime
            if ($decission_cnt == "!0") {
                // $csv_data=$csv_data->having('decision_cnt', '!=', '0');
                $csv_data=$csv_data->whereNull('dsv.decision_datetime');
            } elseif ($decission_cnt != "*") {
                // $csv_data=$csv_data->having('decision_cnt', '=', $decission_cnt);
                $csv_data=$csv_data->whereNotNull('dsv.decision_datetime');
            }

            // send_datetime
            if ($send_cnt == "!0") {
                // $csv_data=$csv_data->having('send_cnt', '!=', '0');
                $csv_data=$csv_data->whereNull('dsv.send_datetime');
            } elseif ($send_cnt != "*") {
                // $csv_data=$csv_data->having('send_cnt', '=', $send_cnt);
                $csv_data=$csv_data->whereNotNull('dsv.send_datetime');
            }
            // $csv_data=$csv_data->groupBy('dsv.mes_lis_shi_tra_trade_number');
        } elseif ($request->page_title=='order_detail_list') {
            $order_info=$request->order_info;
            $par_shi_code = $request->par_shi_code;
            $par_rec_code = $request->par_rec_code;
            $order_item_code = $request->order_item_code;
            $mes_lis_shi_tra_trade_number=$request->mes_lis_shi_tra_trade_number;
            $fixedSpecial=$request->fixedSpecial;
            $printingStatus=$request->printingStatus;
            $situation=$request->situation;
            $send_datetime=$request->send_datetime;
            // $csv_data=$csv_data->where('data_shipments.data_order_id', $data_order_id)
            $csv_data=$csv_data->where('dor.data_order_id', $data_order_id)
            ->where('dsv.mes_lis_shi_log_del_delivery_service_code', $order_info['mes_lis_shi_log_del_delivery_service_code'])
            ->where('dsv.mes_lis_shi_par_sel_code', $order_info['mes_lis_shi_par_sel_code'])
            ->where('dsv.mes_lis_shi_par_sel_name', $order_info['mes_lis_shi_par_sel_name'])
            ->where('dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver', $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'])
            ->where('dsv.mes_lis_shi_tra_goo_major_category', $order_info['mes_lis_shi_tra_goo_major_category'])
            ->where('dsv.mes_lis_shi_tra_ins_temperature_code', $order_info['mes_lis_shi_tra_ins_temperature_code']);
            if ($mes_lis_shi_tra_trade_number!=null) {
                $csv_data = $csv_data->where('dsv.mes_lis_shi_tra_trade_number', $mes_lis_shi_tra_trade_number);
            }
            if ($fixedSpecial!="*") {
                $csv_data = $csv_data->where('dsv.mes_lis_shi_tra_ins_goods_classification_code', $fixedSpecial);
            }
            if ($printingStatus!="*") {
                if ($printingStatus=="未印刷あり") {
                    $csv_data = $csv_data->whereNull('dsv.print_datetime');
                }
                if ($printingStatus=="印刷済") {
                    $csv_data = $csv_data->whereNotNull('dsv.print_datetime');
                }
            }
            if ($situation!="*") {
                if ($situation=="未確定あり") {
                    $csv_data = $csv_data->whereNull('dsv.decision_datetime');
                }
                if ($situation=="確定済") {
                    $csv_data = $csv_data->whereNotNull('dsv.decision_datetime');
                }
            }
            if ($send_datetime!="*") {
                if ($send_datetime=="未送信あり") {
                    $csv_data = $csv_data->whereNull('dsv.send_datetime');
                }
                if ($send_datetime=="送信済") {
                    $csv_data = $csv_data->whereNotNull('dsv.send_datetime');
                }
            }
            if ($par_shi_code!=null) {
                $csv_data = $csv_data->where('dsv.mes_lis_shi_par_shi_code', $par_shi_code);
            }
            if ($par_rec_code!=null) {
                $csv_data = $csv_data->where('dsv.mes_lis_shi_par_rec_code', $par_rec_code);
            }
            if ($order_item_code!=null) {
                $csv_data = $csv_data->where('doi.mes_lis_ord_lin_ite_order_item_code', $order_item_code);
            }

            // ========
            // send_data
            if ($request->send_data==true) {
                $csv_data = $csv_data->whereNotNull('dsv.decision_datetime')
                ->whereNull('dsv.send_datetime');
            }
            // =======
        }

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
        self::checkDateTimeChange($voucher_id_arr);
        // ==========For Voucher Id End==============
        Log::debug(__METHOD__.':end---');
        return ['report_arr_final'=>$report_arr_final,'voucher_id_array'=>$voucher_id_arr,'raw_shipment_data'=>$shipment_data];
    }
    public static function getPickingPdfData($request)
    {
        Log::debug(__METHOD__.':start---');
        $order_info=$request->order_info;
        $mes_lis_shi_tra_trade_number=$request->mes_lis_shi_tra_trade_number;
        $fixedSpecial=$request->fixedSpecial;
        $printingStatus=$request->printingStatus;
        $situation=$request->situation;
        $send_datetime=$request->send_datetime;
        $data_order_id=$request->data_order_id;
        $report_arr_final = array();

        $picking_data = data_shipment_voucher::select(
            'ds.mes_lis_buy_name',
            'data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date',
            'data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver',
            'data_shipment_vouchers.mes_lis_shi_par_rec_name',
            'data_shipment_vouchers.mes_lis_shi_par_rec_name_sbcs',
            'dsi.mes_lis_shi_lin_ite_name',
            'dsi.mes_lis_shi_lin_ite_name_sbcs',
            'dsi.mes_lis_shi_lin_ite_order_item_code',
            'dsi.mes_lis_shi_lin_qua_shi_quantity',
            'dsi.mes_lis_shi_lin_qua_shi_num_of_order_units',
            'dsi.mes_lis_shi_lin_qua_unit_multiple'
        )
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id');

        $picking_data=$picking_data->where('ds.data_order_id', $data_order_id)
        ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $order_info['mes_lis_shi_log_del_delivery_service_code'])
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $order_info['mes_lis_shi_par_sel_code'])
        ->where('data_shipment_vouchers.mes_lis_shi_par_sel_name', $order_info['mes_lis_shi_par_sel_name'])
        ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'])
        ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $order_info['mes_lis_shi_tra_goo_major_category'])
        ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $order_info['mes_lis_shi_tra_ins_temperature_code']);
        $picking_data=$picking_data->get();

        Log::debug(__METHOD__.':end---');
        return ['picking_data'=>$picking_data];
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
    public function shipmentUpdateArray($data_array, $file_name="", $reason_code_list=[])
    {
        Log::debug(__METHOD__.':start---');

        $update_voucher_arr = [];

        $cur_date=date('y-m-d h:i:s');

        $trade_number='';
        DB::beginTransaction();
        try {
            foreach ($data_array as $key => $value) {
                $shipment_item_array=array();

                // csv count check
                if (count($value) != 177) {
                    // CSVカラム数チェック
                    Log::error('upload file format error:177:'.count($value));
                    return response()->json(['status'=>0,'message'=>'アップロードファイルのフォーマットが不正です。']);
                }

                $line_number = $value[105]; // 行番号

                // data_shipment_voucher情報取得
                if ($trade_number !== $value[31]) {
                    $trade_number = $value[31];     // 伝票番号
                    $order_date = $value[76];       // 発注日
                    $dsv_query=data_shipment_voucher::where('mes_lis_shi_tra_trade_number', $trade_number)
                        ->where('mes_lis_shi_tra_dat_order_date', $order_date);
                    $data_shipment_voucher_info=$dsv_query->first();
                    if (empty($data_shipment_voucher_info)) {
                        // 取得無しエラー
                        Log::error('Can not get data_shipment_voucher information:'.$dsv_query->toSql());
                        return response()->json(['status'=>0,'message'=>'対象伝票データがありません。<br>[伝票番号]'.$trade_number.'<br>[発注日]'.$order_date]);
                    }
                    $update_voucher_arr[]['shipment_voucher_id'] = $data_shipment_voucher_info->data_shipment_voucher_id;
                }

                // decision_datetime check
                if (!empty($data_shipment_voucher_info->decision_datetime)) {
                    // 確定済み
                    Log::debug('Already decision:[trade_number]'.$trade_number.' [line_number]'.$line_number);
                    continue;
                }

                // data_shipment_item情報取得
                $dsi_query = data_shipment_item::where('data_shipment_voucher_id', $data_shipment_voucher_info->data_shipment_voucher_id)
                    ->where('mes_lis_shi_lin_lin_line_number', $line_number);
                $data_shipment_item_info=$dsi_query->first();
                if (empty($data_shipment_item_info)) {
                    // 取得無しエラー
                    Log::error('Can not get data_shipment_items information:'.$dsi_query->toSql());
                    return response()->json(['status'=>0,'message'=>'対象明細データがありません。<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }

                // data set from db
                $net_price_unit_price = $data_shipment_item_info->mes_lis_shi_lin_amo_item_net_price_unit_price;            // 原単価
                $selling_price_unit_price = $data_shipment_item_info->mes_lis_shi_lin_amo_item_selling_price_unit_price;    // 売単価
                $ord_quantity = $data_shipment_item_info->mes_lis_shi_lin_qua_ord_quantity;                                 // 発注数量(バラ)
                $ord_num_of_order_units = $data_shipment_item_info->mes_lis_shi_lin_qua_ord_num_of_order_units;             // 発注数量(単位)
                $unit_multiple = $data_shipment_item_info->mes_lis_shi_lin_qua_unit_multiple;                               // 発注単位
                $tax_rate = $data_shipment_voucher_info->mes_lis_shi_tra_tax_tax_rate;                                      // 税率

                // data set from csv
                $revised_delivery_date = $value[79];                                                                // 訂正後直接納品先納品日
                $shi_num_of_order_units = $value[162];                                                              // 出荷数量(単位)
                $sto_reason_code = $value[165];                                                                     // 欠品理由


                // data check validation
                // - shi_num_of_order_units
                // -- number check
                if (!is_numeric($shi_num_of_order_units)) {
                    // 数値以外
                    Log::error('not number:[shi_num_of_order_units]:'.$shi_num_of_order_units);
                    return response()->json(['status'=>0,'message'=>'対象データ不正です。<br>[出荷数量(単位)]:'.$shi_num_of_order_units.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }
                // -- max check
                if ((0 > $shi_num_of_order_units) || ($shi_num_of_order_units > $ord_num_of_order_units)) {
                    // しきい値
                    Log::error('not between number:'.'[ord_num_of_order_units]:'.$ord_num_of_order_units.' [shi_num_of_order_units]:'.$shi_num_of_order_units);
                    return response()->json(['status'=>0,'message'=>'対象データが許容外です。<br>[出荷数量(単位)]:'.$shi_num_of_order_units.'<br>[発注数量(単位)]'.$ord_num_of_order_units.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }

                // - sto_reason_code
                if ($sto_reason_code == '0') {
                    $sto_reason_code = '00';
                }

                // -- json data check
                if (!array_key_exists($sto_reason_code, $reason_code_list)) {
                    //
                    Log::error('not correct reason_code:[sto_reason_code]:'.$sto_reason_code);
                    return response()->json(['status'=>0,'message'=>'対象データ不正です。<br>[欠品理由]:'.$sto_reason_code.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }

                // -- shi_num_of_order_units check
                if (($ord_num_of_order_units != $shi_num_of_order_units) && ($sto_reason_code == '00')) {
                    // 欠品理由無し
                    Log::error('not reason_code:'.'[sto_reason_code]:'.$sto_reason_code);
                    return response()->json(['status'=>0,'message'=>'欠品理由が選択されていません。<br>[欠品理由]:'.$sto_reason_code.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                } elseif (($ord_num_of_order_units == $shi_num_of_order_units) && ($sto_reason_code != '00')) {
                    // 欠品理由不正
                    Log::error('not reason_code:'.'[sto_reason_code]:'.$sto_reason_code);
                    return response()->json(['status'=>0,'message'=>'欠品理由が不正です。<br>[欠品理由]:'.$sto_reason_code.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }

                // - mes_lis_shi_tra_dat_revised_delivery_date
                // -- date format check
                if (!empty($revised_delivery_date)) {
                    // date check
                    $date_temp = explode('-', $revised_delivery_date);
                    if ((count($date_temp) !== 3)||
                        ((count($date_temp) === 3)&&(!checkdate($date_temp[1], $date_temp[2], $date_temp[0])))) {
                        // date check
                        Log::error('not correct revised_delivery_date:'.'[revised_delivery_date]:'.$revised_delivery_date);
                        return response()->json(['status'=>0,'message'=>'対象データ不正です。<br>[訂正後直接納品先納品日]:'.$revised_delivery_date.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                    }
                    // -- order_date check
                    if (strtotime($revised_delivery_date) < strtotime($order_date)) {
                        // 発注日より過去は指定できない
                        Log::error('can not use revised_delivery_date before order date:'.'[revised_delivery_date]:'.$revised_delivery_date);
                        return response()->json(['status'=>0,'message'=>'発注日より過去は指定できません。<br>[訂正後直接納品先納品日]:'.$revised_delivery_date.'<br>[発注日]'.$order_date.'<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                    }

                    // 追加
                    $update_voucher_arr[count($update_voucher_arr)-1]['revised_delivery_date'] = $revised_delivery_date;
                }

                $shi_quantity = $unit_multiple * $shi_num_of_order_units;                       // 出荷数量(バラ)
                $sto_quantity = $ord_quantity - $shi_quantity;                                  // 欠品数量(バラ)
                $sto_num_of_order_units = $ord_num_of_order_units - $shi_num_of_order_units;    // 欠品数量(単位)
                $net_price = $shi_quantity * $net_price_unit_price;                             // 原価金額
                $selling_price = $shi_quantity * $selling_price_unit_price;                     // 売価金額
                $tax = $net_price * $tax_rate / 100;                                            // 税額


                $shipment_item_array['mes_lis_shi_lin_amo_item_net_price']=$net_price;
                $shipment_item_array['mes_lis_shi_lin_amo_item_selling_price']=$selling_price;
                $shipment_item_array['mes_lis_shi_lin_amo_item_tax']=$tax;
                $shipment_item_array['mes_lis_shi_lin_qua_shi_quantity']=$shi_quantity;
                $shipment_item_array['mes_lis_shi_lin_qua_shi_num_of_order_units']=$shi_num_of_order_units;
                $shipment_item_array['mes_lis_shi_lin_qua_sto_quantity']=$sto_quantity;
                $shipment_item_array['mes_lis_shi_lin_qua_sto_num_of_order_units']=$sto_num_of_order_units;
                $shipment_item_array['mes_lis_shi_lin_qua_sto_reason_code']=$sto_reason_code;

                // data_shipment_items update
                $dsi_query->update($shipment_item_array);
            }

            // voucher re calc
            $net_price_total=0;
            $selling_price_total=0;
            $tax_total=0;
            $item_total=0;
            $unit_total=0;

            foreach ($update_voucher_arr as $voucher_info) {
                // data_shipment_item情報取得
                $dsi_query = data_shipment_item::where('data_shipment_voucher_id', $voucher_info['shipment_voucher_id']);
                $data_shipment_item_info=$dsi_query->get();
                if (empty($data_shipment_item_info)) {
                    // 取得無しエラー
                    Log::error('Can not get data_shipment_items information:'.$dsi_query->toSql());
                    return response()->json(['status'=>0,'message'=>'対象データがありません。<br>[伝票番号]'.$trade_number.'<br>[明細番号]'.$line_number]);
                }

                // status check
                $status = 1;
                $minou_cnt = 0;
                $ichibu_cnt =0;
                foreach ($data_shipment_item_info as $ival) {
                    $net_price_total += $ival->mes_lis_shi_lin_amo_item_net_price;           // 原価金額合計
                    $selling_price_total += $ival->mes_lis_shi_lin_amo_item_selling_price;   // 売価金額合計
                    $tax_total += $ival->mes_lis_shi_lin_amo_item_tax;                       // 税額合計
                    $item_total += $ival->mes_lis_shi_lin_qua_shi_quantity;                  // 数量合計
                    $unit_total += $ival->mes_lis_shi_lin_qua_shi_num_of_order_units;        // 発注単位数量合計

                    // status check
                    if ($ival->mes_lis_shi_lin_qua_shi_num_of_order_units == 0) {
                        // 未納
                        $minou_cnt++;
                    } elseif ($ival->mes_lis_shi_lin_qua_shi_num_of_order_units < $ival->mes_lis_shi_lin_qua_ord_num_of_order_units) {
                        // 一部未納
                        $ichibu_cnt++;
                    }
                }
                if (count($data_shipment_item_info) === $minou_cnt) {
                    // 未納
                    $status = 3;
                } elseif (($minou_cnt > 0)||($ichibu_cnt > 0)) {
                    // 一部未納
                    $status = 2;
                }


                // update shipment_vouchers
                $shipment_voucher_array['status']=$status;
                if (isset($voucher_info['revised_delivery_date'])) {
                    $shipment_voucher_array['mes_lis_shi_tra_dat_revised_delivery_date']=$voucher_info['revised_delivery_date'];
                }
                $shipment_voucher_array['mes_lis_shi_tot_tot_net_price_total']=$net_price_total;
                $shipment_voucher_array['mes_lis_shi_tot_tot_selling_price_total']=$selling_price_total;
                $shipment_voucher_array['mes_lis_shi_tot_tot_tax_total']=$tax_total;
                $shipment_voucher_array['mes_lis_shi_tot_tot_item_total']=$item_total;
                $shipment_voucher_array['mes_lis_shi_tot_tot_unit_total']=$unit_total;

                // 自動確定
                $shipment_voucher_array['decision_datetime']=$cur_date;

                // voucher 更新
                data_shipment_voucher::where('data_shipment_voucher_id', $voucher_info['shipment_voucher_id'])->update($shipment_voucher_array);

                // 値初期化
                $net_price_total = 0;       // 原価金額合計
                $selling_price_total = 0;   // 売価金額合計
                $tax_total = 0;             // 税額合計
                $item_total = 0;            // 数量合計
                $unit_total = 0;            // 発注単位数量合計
            }



            // voucher 更新
            if (isset($shipment_voucher_array)) {
                $dsv_query->update($shipment_voucher_array);
                // udpate shipments
                $shipment_array['upload_datetime']=date('y-m-d H:i:s');
                $shipment_array['upload_file_path']=$file_name;
                $data_shipment_id=$data_shipment_voucher_info->data_shipment_id;
                data_shipment::where('data_shipment_id', $data_shipment_id)->update($shipment_array);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            log::error($e);
            return response()->json(['message' => $e, 'status' => 0]);
            // something went wrong
        }

        Log::debug(__METHOD__.':end---');
        return response()->json(['message'=>"出荷データアップロード",'status'=>1]);
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
