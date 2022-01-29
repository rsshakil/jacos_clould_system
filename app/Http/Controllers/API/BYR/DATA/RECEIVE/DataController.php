<?php

namespace App\Http\Controllers\API\BYR\DATA\RECEIVE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DATA\RCV\data_receive;
use App\Models\DATA\RCV\data_receive_voucher;
use App\Models\DATA\RTN\data_return;
use App\Models\ADM\User;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class DataController extends Controller
{
    // private $all_used_fun;
    // public function __construct(){
    //     $this->all_used_fun = new AllUsedFunction();
    // }
    public static function getReceiveData($request)
    {
        // 対象データ取得
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_receive_voucher::select(
            'dr.sta_sen_identifier',
            'dr.sta_sen_ide_authority',
            'dr.sta_rec_identifier',
            'dr.sta_rec_ide_authority',
            'dr.sta_doc_standard',
            'dr.sta_doc_type_version',
            'dr.sta_doc_instance_identifier',
            'dr.sta_doc_type',
            'dr.sta_doc_creation_date_and_time',
            'dr.sta_bus_scope_type',
            'dr.sta_bus_scope_instance_identifier',
            'dr.sta_bus_scope_identifier',
            'dr.mes_ent_unique_creator_identification',
            'dr.mes_mes_sender_station_address',
            'dr.mes_mes_ultimate_receiver_station_address',
            'dr.mes_mes_immediate_receiver_station_addres',
            'dr.mes_mes_number_of_trading_documents',
            'dr.mes_mes_sys_key',
            'dr.mes_mes_sys_value',
            'dr.mes_lis_con_version',
            'dr.mes_lis_doc_version',
            'dr.mes_lis_ext_namespace',
            'dr.mes_lis_ext_version',
            'dr.mes_lis_pay_code',
            'dr.mes_lis_pay_gln',
            'dr.mes_lis_pay_name',
            'dr.mes_lis_pay_name_sbcs',
            'dr.mes_lis_buy_code',
            'dr.mes_lis_buy_gln',
            'dr.mes_lis_buy_name',
            'dr.mes_lis_buy_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_tra_trade_number',
            'data_receive_vouchers.mes_lis_acc_tra_additional_trade_number',
            'data_receive_vouchers.mes_lis_acc_fre_shipment_number',
            'data_receive_vouchers.mes_lis_acc_par_shi_code',
            'data_receive_vouchers.mes_lis_acc_par_shi_gln',
            'data_receive_vouchers.mes_lis_acc_par_shi_name',
            'data_receive_vouchers.mes_lis_acc_par_shi_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_rec_code',
            'data_receive_vouchers.mes_lis_acc_par_rec_gln',
            'data_receive_vouchers.mes_lis_acc_par_rec_name',
            'data_receive_vouchers.mes_lis_acc_par_rec_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_tra_code',
            'data_receive_vouchers.mes_lis_acc_par_tra_gln',
            'data_receive_vouchers.mes_lis_acc_par_tra_name',
            'data_receive_vouchers.mes_lis_acc_par_tra_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_dis_code',
            'data_receive_vouchers.mes_lis_acc_par_dis_name',
            'data_receive_vouchers.mes_lis_acc_par_dis_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_pay_code',
            'data_receive_vouchers.mes_lis_acc_par_pay_gln',
            'data_receive_vouchers.mes_lis_acc_par_pay_name',
            'data_receive_vouchers.mes_lis_acc_par_pay_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_sel_code',
            'data_receive_vouchers.mes_lis_acc_par_sel_gln',
            'data_receive_vouchers.mes_lis_acc_par_sel_name',
            'data_receive_vouchers.mes_lis_acc_par_sel_name_sbcs',
            'data_receive_vouchers.mes_lis_acc_par_sel_branch_number',
            'data_receive_vouchers.mes_lis_acc_par_sel_ship_location_code',
            'data_receive_vouchers.mes_lis_acc_log_shi_gln',
            'data_receive_vouchers.mes_lis_acc_log_del_route_code',
            'data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',
            'data_receive_vouchers.mes_lis_acc_log_del_stock_transfer_code',
            'data_receive_vouchers.mes_lis_acc_log_del_delivery_code',
            'data_receive_vouchers.mes_lis_acc_log_del_delivery_time',
            'data_receive_vouchers.mes_lis_acc_log_del_transportation_code',
            'data_receive_vouchers.mes_lis_acc_log_log_barcode_print',
            'data_receive_vouchers.mes_lis_acc_log_log_category_name_print1',
            'data_receive_vouchers.mes_lis_acc_log_log_category_name_print2',
            'data_receive_vouchers.mes_lis_acc_log_log_receiver_abbr_name',
            'data_receive_vouchers.mes_lis_acc_log_log_text',
            'data_receive_vouchers.mes_lis_acc_log_log_text_sbcs',
            'data_receive_vouchers.mes_lis_acc_log_maker_code_for_receiving',
            'data_receive_vouchers.mes_lis_acc_log_delivery_slip_number',
            'data_receive_vouchers.mes_lis_acc_tra_goo_major_category',
            'data_receive_vouchers.mes_lis_acc_tra_goo_sub_major_category',
            'data_receive_vouchers.mes_lis_acc_tra_dat_order_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_delivery_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_delivery_date_to_receiver',
            'data_receive_vouchers.mes_lis_acc_tra_dat_revised_delivery_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_revised_delivery_date_to_receiver',
            'data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_campaign_start_date',
            'data_receive_vouchers.mes_lis_acc_tra_dat_campaign_end_date',
            'data_receive_vouchers.mes_lis_acc_tra_ins_goods_classification_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_order_classification_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_ship_notification_request_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_eos_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_private_brand_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_liquor_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_trade_type_code',
            'data_receive_vouchers.mes_lis_acc_tra_ins_paper_form_less_code',
            'data_receive_vouchers.mes_lis_acc_tra_fre_trade_number_request_code',
            'data_receive_vouchers.mes_lis_acc_tra_fre_package_code',
            'data_receive_vouchers.mes_lis_acc_tra_fre_variable_measure_item_code',
            'data_receive_vouchers.mes_lis_acc_tra_tax_tax_type_code',
            'data_receive_vouchers.mes_lis_acc_tra_tax_tax_rate',
            'data_receive_vouchers.mes_lis_acc_tra_not_text',
            'data_receive_vouchers.mes_lis_acc_tra_not_text_sbcs',
            'data_receive_vouchers.mes_lis_acc_tot_tot_net_price_total',
            'data_receive_vouchers.mes_lis_acc_tot_tot_selling_price_total',
            'data_receive_vouchers.mes_lis_acc_tot_tot_tax_total',
            'data_receive_vouchers.mes_lis_acc_tot_tot_item_total',
            'data_receive_vouchers.mes_lis_acc_tot_tot_unit_total',
            'data_receive_vouchers.mes_lis_acc_tot_fre_unit_weight_total',
            'dri.mes_lis_acc_lin_lin_line_number',
            'dri.mes_lis_acc_lin_lin_additional_line_number',
            'dri.mes_lis_acc_lin_fre_trade_number',
            'dri.mes_lis_acc_lin_fre_line_number',
            'dri.mes_lis_acc_lin_fre_shipment_line_number',
            'dri.mes_lis_acc_lin_goo_minor_category',
            'dri.mes_lis_acc_lin_goo_detailed_category',
            'dri.mes_lis_acc_lin_ite_scheduled_date',
            'dri.mes_lis_acc_lin_ite_deadline_date',
            'dri.mes_lis_acc_lin_ite_center_delivery_instruction_code',
            'dri.mes_lis_acc_lin_ite_maker_code',
            'dri.mes_lis_acc_lin_ite_gtin',
            'dri.mes_lis_acc_lin_ite_order_item_code',
            'dri.mes_lis_acc_lin_ite_ord_code_type',
            'dri.mes_lis_acc_lin_ite_supplier_item_code',
            'dri.mes_lis_acc_lin_ite_name',
            'dri.mes_lis_acc_lin_ite_name_sbcs',
            'dri.mes_lis_acc_lin_fre_shipment_item_code',
            'dri.mes_lis_acc_lin_ite_ite_spec',
            'dri.mes_lis_acc_lin_ite_ite_spec_sbcs',
            'dri.mes_lis_acc_lin_ite_col_color_code',
            'dri.mes_lis_acc_lin_ite_col_description',
            'dri.mes_lis_acc_lin_ite_col_description_sbcs',
            'dri.mes_lis_acc_lin_ite_siz_size_code',
            'dri.mes_lis_acc_lin_ite_siz_description',
            'dri.mes_lis_acc_lin_ite_siz_description_sbcs',
            'dri.mes_lis_acc_lin_fre_packing_quantity',
            'dri.mes_lis_acc_lin_fre_prefecture_code',
            'dri.mes_lis_acc_lin_fre_country_code',
            'dri.mes_lis_acc_lin_fre_field_name',
            'dri.mes_lis_acc_lin_fre_water_area_code',
            'dri.mes_lis_acc_lin_fre_water_area_name',
            'dri.mes_lis_acc_lin_fre_area_of_origin',
            'dri.mes_lis_acc_lin_fre_item_grade',
            'dri.mes_lis_acc_lin_fre_item_class',
            'dri.mes_lis_acc_lin_fre_brand',
            'dri.mes_lis_acc_lin_fre_item_pr',
            'dri.mes_lis_acc_lin_fre_bio_code',
            'dri.mes_lis_acc_lin_fre_breed_code',
            'dri.mes_lis_acc_lin_fre_cultivation_code',
            'dri.mes_lis_acc_lin_fre_defrost_code',
            'dri.mes_lis_acc_lin_fre_item_preservation_code',
            'dri.mes_lis_acc_lin_fre_item_shape_code',
            'dri.mes_lis_acc_lin_fre_use',
            'dri.mes_lis_acc_lin_sta_statutory_classification_code',
            'dri.mes_lis_acc_lin_amo_item_net_price',
            'dri.mes_lis_acc_lin_amo_item_net_price_unit_price',
            'dri.mes_lis_acc_lin_amo_item_selling_price',
            'dri.mes_lis_acc_lin_amo_item_selling_price_unit_price',
            'dri.mes_lis_acc_lin_amo_item_tax',
            'dri.mes_lis_acc_lin_qua_unit_multiple',
            'dri.mes_lis_acc_lin_qua_unit_of_measure',
            'dri.mes_lis_acc_lin_qua_package_indicator',
            'dri.mes_lis_acc_lin_qua_ord_quantity',
            'dri.mes_lis_acc_lin_qua_ord_num_of_order_units',
            'dri.mes_lis_acc_lin_qua_shi_quantity',
            'dri.mes_lis_acc_lin_qua_shi_num_of_order_units',
            'dri.mes_lis_acc_lin_qua_rec_quantity',
            'dri.mes_lis_acc_lin_qua_rec_num_of_order_units',
            'dri.mes_lis_acc_lin_qua_rec_reason_code',
            'dri.mes_lis_acc_lin_fre_unit_weight',
            'dri.mes_lis_acc_lin_fre_unit_weight_code',
            'dri.mes_lis_acc_lin_fre_item_weight',
            'dri.mes_lis_acc_lin_fre_order_weight',
            'dri.mes_lis_acc_lin_fre_shipment_weight',
            'dri.mes_lis_acc_lin_fre_received_weight'
            )
            ->join('data_receives as dr','dr.data_receive_id','=','data_receive_vouchers.data_receive_id')
            ->join('data_receive_items as dri','dri.data_receive_voucher_id','=','data_receive_vouchers.data_receive_voucher_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
            ->withTrashed()
            ->where('cc.byr_buyer_id', $byr_buyer_id);
            // ->where('cc.slr_seller_id', $slr_seller_id);
            // ->where('data_receives.cmn_connect_id','=',$cmn_connect_id);
        if ($request->page_title=='receive_list') {
            $receive_date_from = $request->receive_date_from; // 受信日時開始
            $receive_date_to = $request->receive_date_to; // 受信日時終了
            $ownership_date_from = $request->ownership_date_from; // 納品日開始
            $ownership_date_to = $request->ownership_date_to; // 納品日終了
            $sel_code = $request->sel_code; // 印刷
            $delivery_service_code = $request->delivery_service_code; // 便
            $temperature_code = $request->temperature_code; // 配送温度区分
            $major_category = $request->major_category;

            $sta_doc_type = $request->sta_doc_type;
            $check_datetime = $request->check_datetime;
            $trade_number = $request->trade_number;

            $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $ownership_date_from = $ownership_date_from!=null? date('Y-m-d 00:00:00', strtotime($ownership_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $ownership_date_to = $ownership_date_to!=null? date('Y-m-d 23:59:59', strtotime($ownership_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $result =$result->whereBetween('dr.receive_datetime', [$receive_date_from, $receive_date_to])
            ->whereBetween('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date', [$ownership_date_from, $ownership_date_to]);
            if ($trade_number!=null) {
                $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_trade_number', $trade_number);
            }
            if ($sel_code) {
                $result =$result->where('data_receive_vouchers.mes_lis_acc_par_sel_code', $sel_code);
            }
            if ($delivery_service_code!='*') {
                $result =$result->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code',$delivery_service_code);
            }
            if ($temperature_code!='*') {
                $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_ins_temperature_code',$temperature_code);
            }
            if ($major_category!='*') {
                $result =$result->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category',$major_category);
            }
            if ($sta_doc_type!='*') {
                $result =$result->where('dr.sta_doc_type',$sta_doc_type);
            }
            if ($check_datetime!='*') {
                if($check_datetime==1){
                    $result= $result->whereNull('data_receive_vouchers.check_datetime');
                }else{
                    $result= $result->whereNotNull('data_receive_vouchers.check_datetime');
                }
            }
        }else if($request->page_title=='receive_details_list'){
            $sel_name = $request->par_sel_name;
            $sel_code = $request->sel_code;
            $major_category = $request->major_category;
            $delivery_service_code = $request->delivery_service_code;
            $ownership_date = $request->ownership_date;
            $data_receive_id=$request->data_receive_id;

            $decesion_status=$request->decesion_status;
            $voucher_class=$request->voucher_class;
            $goods_classification_code=$request->goods_classification_code;
            $trade_number=$request->trade_number;
            $table_name='data_receive_vouchers.';
        $result=$result->where('data_receive_vouchers.data_receive_id','=',$data_receive_id)
        // ->where('data_receive_vouchers.mes_lis_acc_par_sel_name',$sel_name)
        ->where('data_receive_vouchers.mes_lis_acc_par_sel_code',$sel_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_goo_major_category',$major_category)
        ->where('data_receive_vouchers.mes_lis_acc_log_del_delivery_service_code','=',$delivery_service_code)
        ->where('data_receive_vouchers.mes_lis_acc_tra_dat_transfer_of_ownership_date',$ownership_date);
        if($decesion_status!="*"){
            if($decesion_status=="訂正あり"){
                $result = $result->where('data_receive_vouchers.mes_lis_acc_tot_tot_net_price_total','=','data_receive_vouchers.mes_lis_shi_tot_tot_net_price_total');
            }
            if($decesion_status=="訂正なし"){
                $result = $result->where('data_receive_vouchers.mes_lis_acc_tot_tot_net_price_total','!=','data_receive_vouchers.mes_lis_shi_tot_tot_net_price_total');
            }
        }
        if($request->mes_lis_acc_par_shi_code!=null){
            $result = $result->where('data_receive_vouchers.mes_lis_acc_par_shi_code',$request->mes_lis_acc_par_shi_code);
        }
        if($request->mes_lis_acc_par_rec_code!=null){
            $result = $result->where('data_receive_vouchers.mes_lis_acc_par_rec_code',$request->mes_lis_acc_par_rec_code);
        }
        if($voucher_class!="*"){
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_ins_trade_type_code',$voucher_class);
        }
        if($goods_classification_code!="*"){
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_ins_goods_classification_code',$goods_classification_code);
        }
        if($trade_number!=null){
            $result = $result->where('data_receive_vouchers.mes_lis_acc_tra_trade_number',$trade_number);
        }
        // $result=$result->groupBy('drv.mes_lis_acc_tra_trade_number')
        // $result = $result->orderBy($table_name.$sort_by,$sort_type);
        }
        $result=$result->orderBy('data_receive_vouchers.mes_lis_acc_tra_trade_number', "ASC");
        $result=$result->orderBy('dri.mes_lis_acc_lin_lin_line_number', "ASC");
        return $result;
    }

    public static function receiveCsvHeading(){
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
            '訂正後最終納品先納品日',
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
            '受領数量 (バラ数)',
            '受領数量 (発注単位数)',
            '訂正区分',
            '取引単位重量',
            '単価登録単位',
            '商品重量',
            '発注重量',
            '出荷重量',
            '出荷重量'
        ];
    }
}
