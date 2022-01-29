<?php

namespace App\Http\Controllers\API\BYR\DATA\RTN;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\ADM\User;
use App\Models\DATA\RTN\data_return;
use App\Models\DATA\RTN\data_return_voucher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public static function getRtnData($request)
    {
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_return_voucher::select(
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
            'dr.mes_lis_ret_code',
            'dr.mes_lis_ret_gln',
            'dr.mes_lis_ret_name',
            'dr.mes_lis_ret_name_sbcs',
            'data_return_vouchers.mes_lis_ret_tra_trade_number',
            'data_return_vouchers.mes_lis_ret_tra_additional_trade_number',
            'data_return_vouchers.mes_lis_ret_fre_shipment_number',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_gln',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_name',
            'data_return_vouchers.mes_lis_ret_par_return_receive_from_name_sbcs',
            'data_return_vouchers.mes_lis_ret_par_return_from_code',
            'data_return_vouchers.mes_lis_ret_par_return_from_gln',
            'data_return_vouchers.mes_lis_ret_par_return_from_name',
            'data_return_vouchers.mes_lis_ret_par_return_from_name_sbcs',
            'data_return_vouchers.mes_lis_ret_par_tra_code',
            'data_return_vouchers.mes_lis_ret_par_tra_gln',
            'data_return_vouchers.mes_lis_ret_par_tra_name',
            'data_return_vouchers.mes_lis_ret_par_tra_name_sbcs',
            'data_return_vouchers.mes_lis_ret_par_pay_code',
            'data_return_vouchers.mes_lis_ret_par_pay_gln',
            'data_return_vouchers.mes_lis_ret_par_pay_name',
            'data_return_vouchers.mes_lis_ret_par_pay_name_sbcs',
            'data_return_vouchers.mes_lis_ret_par_sel_code',
            'data_return_vouchers.mes_lis_ret_par_sel_gln',
            'data_return_vouchers.mes_lis_ret_par_sel_name',
            'data_return_vouchers.mes_lis_ret_par_sel_name_sbcs',
            'data_return_vouchers.mes_lis_ret_par_sel_branch_number',
            'data_return_vouchers.mes_lis_ret_par_sel_ship_location_code',
            'data_return_vouchers.mes_lis_ret_log_return_goods_transfer_type',
            'data_return_vouchers.mes_lis_ret_tra_goo_major_category',
            'data_return_vouchers.mes_lis_ret_tra_goo_sub_major_category',
            'data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date',
            'data_return_vouchers.mes_lis_ret_tra_dat_checking_date',
            'data_return_vouchers.mes_lis_ret_tra_dat_checking_date_code',
            'data_return_vouchers.mes_lis_ret_tra_ins_goods_classification_code',
            'data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code',
            'data_return_vouchers.mes_lis_ret_tra_ins_delivery_fee_exemption_code',
            'data_return_vouchers.mes_lis_ret_tra_ins_paper_form_less_code',
            'data_return_vouchers.mes_lis_ret_tra_fre_trade_number_request_code',
            'data_return_vouchers.mes_lis_ret_tra_fre_package_code',
            'data_return_vouchers.mes_lis_ret_tra_fre_variable_measure_item_code',
            'data_return_vouchers.mes_lis_ret_tra_tax_tax_type_code',
            'data_return_vouchers.mes_lis_ret_tra_tax_tax_rate',
            'data_return_vouchers.mes_lis_ret_tra_package_number',
            'data_return_vouchers.mes_lis_ret_tra_not_text',
            'data_return_vouchers.mes_lis_ret_tra_not_text_sbcs',
            'data_return_vouchers.mes_lis_ret_tot_tot_net_price_total',
            'data_return_vouchers.mes_lis_ret_tot_tot_selling_price_total',
            'data_return_vouchers.mes_lis_ret_tot_tot_tax_total',
            'data_return_vouchers.mes_lis_ret_tot_tot_item_total',
            'data_return_vouchers.mes_lis_ret_tot_fre_unit_weight_total',
            'dri.mes_lis_ret_lin_lin_line_number',
            'dri.mes_lis_ret_lin_lin_additional_line_number',
            'dri.mes_lis_ret_lin_fre_trade_number',
            'dri.mes_lis_ret_lin_fre_line_number',
            'dri.mes_lis_ret_lin_fre_shipment_line_number',
            'dri.mes_lis_ret_lin_goo_minor_category',
            'dri.mes_lis_ret_lin_goo_detailed_category',
            'dri.mes_lis_ret_lin_reason_code',
            'dri.mes_lis_ret_lin_ite_maker_code',
            'dri.mes_lis_ret_lin_ite_gtin',
            'dri.mes_lis_ret_lin_ite_order_item_code',
            'dri.mes_lis_ret_lin_ite_code_type',
            'dri.mes_lis_ret_lin_ite_supplier_item_code',
            'dri.mes_lis_ret_lin_ite_name',
            'dri.mes_lis_ret_lin_ite_name_sbcs',
            'dri.mes_lis_ret_lin_fre_shipment_item_code',
            'dri.mes_lis_ret_lin_ite_ite_spec',
            'dri.mes_lis_ret_lin_ite_ite_spec_sbcs',
            'dri.mes_lis_ret_lin_ite_col_color_code',
            'dri.mes_lis_ret_lin_ite_col_description',
            'dri.mes_lis_ret_lin_ite_col_description_sbcs',
            'dri.mes_lis_ret_lin_ite_siz_size_code',
            'dri.mes_lis_ret_lin_ite_siz_description',
            'dri.mes_lis_ret_lin_ite_siz_description_sbcs',
            'dri.mes_lis_ret_lin_fre_packing_quantity',
            'dri.mes_lis_ret_lin_fre_prefecture_code',
            'dri.mes_lis_ret_lin_fre_country_code',
            'dri.mes_lis_ret_lin_fre_field_name',
            'dri.mes_lis_ret_lin_fre_water_area_code',
            'dri.mes_lis_ret_lin_fre_water_area_name',
            'dri.mes_lis_ret_lin_fre_area_of_origin',
            'dri.mes_lis_ret_lin_fre_item_grade',
            'dri.mes_lis_ret_lin_fre_item_class',
            'dri.mes_lis_ret_lin_fre_brand',
            'dri.mes_lis_ret_lin_fre_item_pr',
            'dri.mes_lis_ret_lin_fre_bio_code',
            'dri.mes_lis_ret_lin_fre_breed_code',
            'dri.mes_lis_ret_lin_fre_cultivation_code',
            'dri.mes_lis_ret_lin_fre_defrost_code',
            'dri.mes_lis_ret_lin_fre_item_preservation_code',
            'dri.mes_lis_ret_lin_fre_item_shape_code',
            'dri.mes_lis_ret_lin_fre_use',
            'dri.mes_lis_ret_lin_sta_statutory_classification_code',
            'dri.mes_lis_ret_lin_amo_item_net_price',
            'dri.mes_lis_ret_lin_amo_item_net_price_unit_price',
            'dri.mes_lis_ret_lin_amo_item_selling_price',
            'dri.mes_lis_ret_lin_amo_item_selling_price_unit_price',
            'dri.mes_lis_ret_lin_amo_item_tax',
            'dri.mes_lis_ret_lin_qua_quantity',
            'dri.mes_lis_ret_lin_fre_unit_weight_code',
            'dri.mes_lis_ret_lin_fre_item_weight',
            'dri.mes_lis_ret_lin_fre_return_weight',
            )
            ->join('data_returns as dr','dr.data_return_id','=','data_return_vouchers.data_return_id')
            ->join('data_return_items as dri','dri.data_return_voucher_id','=','data_return_vouchers.data_return_voucher_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dr.cmn_connect_id')
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->withTrashed();
        if ($request->page_title=='return_list') {
            $receive_date_from = $request->receive_date_from; // 受信日時開始
            $receive_date_to = $request->receive_date_to; // 受信日時終了
            $ownership_date_from = $request->ownership_date_from; // 納品日開始
            $ownership_date_to = $request->ownership_date_to; // 納品日終了
            $sel_code = $request->sel_code;
            $delivery_service_code = $request->delivery_service_code; // 便
            $temperature_code = $request->temperature_code; // 配送温度区分
            $major_category = $request->major_category; // 配送温度区分
            $sta_doc_type = $request->sta_doc_type; // 配送温度区分
            $check_datetime = $request->check_datetime; // 配送温度区分
            $trade_number = $request->trade_number;

            $major_category_code=$major_category['category_code'];

            $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00',strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59',strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $ownership_date_from = $ownership_date_from!=null? date('Y-m-d 00:00:00',strtotime($ownership_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $ownership_date_to = $ownership_date_to!=null? date('Y-m-d 23:59:59',strtotime($ownership_date_to)):config('const.TO_DATETIME'); // 受信日時終了

            $result =$result->whereBetween('dr.receive_datetime', [$receive_date_from, $receive_date_to])
            ->whereBetween('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date', [$ownership_date_from, $ownership_date_to]);
            if ($trade_number!=null) {
                $result =$result->where('data_return_vouchers.mes_lis_ret_tra_trade_number', $trade_number);
            }
            if ($major_category_code!='*') {
                $result =$result->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category',$major_category_code);
            }
            if ($request->sel_code!=null) {
                $result =$result->where('data_return_vouchers.mes_lis_ret_par_sel_code',$request->sel_code);
            }
            if ($sta_doc_type!='*') {
                $result =$result->where('dr.sta_doc_type',$sta_doc_type);
            }
            if ($check_datetime!='*') {
                if($check_datetime==1){
                    $result= $result->whereNull('data_return_vouchers.check_datetime');
                }else{
                    $result= $result->whereNotNull('data_return_vouchers.check_datetime');
                }

            }
        }else if($request->page_title=='return_details_list'){
            $data_return_id = $request->data_return_id;

            $sel_name = $request->par_sel_name;
            $sel_code = $request->sel_code;
            $major_category = $request->major_category;
            $ownership_date = $request->ownership_date;

            $decesion_status=$request->decesion_status;
            $voucher_class=$request->voucher_class;
            $goods_classification_code=$request->goods_classification_code;
            $trade_number=$request->trade_number;

            $result = $result->where('dr.data_return_id','=',$data_return_id)
            // ->where('data_return_vouchers.mes_lis_acc_par_sel_name',$sel_name)
            ->where('data_return_vouchers.mes_lis_ret_tra_goo_major_category',$major_category==null?'':$major_category)
            ->where('data_return_vouchers.mes_lis_ret_tra_dat_transfer_of_ownership_date',$ownership_date)
            ->where('data_return_vouchers.mes_lis_ret_par_sel_code',$sel_code);
            if($decesion_status!="*"){
                if($decesion_status=="訂正あり"){
                    $result = $result->where('data_return_vouchers.mes_lis_ret_tot_tot_net_price_total','>',0);
                }
                if($decesion_status=="訂正なし"){
                    $result = $result->where('data_return_vouchers.mes_lis_ret_tot_tot_net_price_total',0);
                }
            }
            if($request->searchCode1!=''){
                $result = $result->where('data_return_vouchers.mes_lis_ret_par_return_receive_from_code',$request->searchCode1);
            }
            if($request->searchCode2!=''){
                $result = $result->where('data_return_vouchers.mes_lis_ret_par_return_from_code',$request->searchCode2);
            }
            if($request->searchCode3!=''){
                $result = $result->where('dri.mes_lis_ret_lin_ite_order_item_code',$request->searchCode3);
            }
            if($voucher_class!="*"){
                $result = $result->where('data_return_vouchers.mes_lis_ret_tra_ins_trade_type_code',$voucher_class);
            }
            if($goods_classification_code!="*"){
                $result = $result->where('data_return_vouchers.mes_lis_ret_tra_ins_goods_classification_code',$goods_classification_code);
            }
            if($trade_number!=null){
                $result = $result->where('data_return_vouchers.mes_lis_ret_tra_trade_number',$trade_number);
            }

        }
        $result=$result->orderBy('data_return_vouchers.mes_lis_ret_tra_trade_number', "ASC");
        $result=$result->orderBy('dri.mes_lis_ret_lin_lin_line_number', "ASC");
        return $result;
    }
    public static function rtnCsvHeading(){
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
            'テスト区分ＩＤ',
            'タイプ',
            '最終送信先ＩＤ',
            'メッセージ識別ＩＤ',
            '送信者ステーションアドレス',
            '最終受信者ステーションアドレス',
            '直接受信者ステーションアドレス',
            '取引数',
            'キー',
            '値',
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
            '計上部署名',
            '計上部署名称(カナ)',
            '請求取引先コード',
            '請求取引先GLN',
            '請求取引先名',
            '請求取引先名カナ',
            '取引先コード',
            '取引先GLN',
            '取引先名称',
            '取引先名称カナ',
            '取引先：枝番',
            '出荷先コード',
            '商品移動区分',
            '商品分類（大）',
            '商品分類（中）',
            '計上日',
            '照合基準日',
            '照合基準日区分',
            '商品区分',
            '処理種別',
            '配送料免除区分',
            '伝票レス区分',
            '取引番号区分',
            'パック区分',
            '不定貫区分',
            '税区分',
            '税率',
            '梱包NO',
            '自由使用欄',
            '自由使用欄半角カナ',
            '原価金額合計',
            '売価金額合計',
            '税額合計金額',
            '数量合計',
            '重量合計',
            '取引明細番号（発注・返品）',
            '取引付属明細番号',
            '元取引番号',
            '元取引明細番号',
            '出荷者管理明細番号',
            '商品分類（小）',
            '商品分類（細）',
            '返品・値引理由コード',
            'メーカーコード',
            '商品コード（GTIN）',
            '商品コード（発注用）',
            '商品コード区分',
            '商品コード（取引先）',
            '商品名',
            '商品名カナ',
            '商品コード（出荷元）',
            '商品規格：規格',
            '商品規格：規格カナ',
            'カラーコード',
            'カラー名称',
            'カラー名称カナ',
            'サイズコード',
            'サイズ名称',
            'サイズ名称カナ',
            '商品規格.生鮮：入数',
            '商品規格.生鮮：都道府県コード',
            '商品規格.生鮮：国コード',
            '商品規格.生鮮：産地名',
            '商品規格.生鮮：水域コード',
            '商品規格.生鮮：水域名',
            '商品規格.生鮮：原産エリア',
            '商品規格.生鮮：等級',
            '商品規格.生鮮：階級',
            '商品規格.生鮮：銘柄',
            '商品規格.生鮮：商品ＰＲ',
            '商品規格.生鮮：バイオ区分',
            '商品規格.生鮮：品種コード',
            '商品規格.生鮮：養殖区分',
            '商品規格.生鮮：解凍区分',
            '商品規格.生鮮：商品状態区分',
            '商品規格.生鮮：形状・部位',
            '商品規格.生鮮：用途',
            '法定管理義務商材区分',
            '原価金額',
            '原単価',
            '売価金額',
            '売単価',
            '税額',
            '返品数量（バラ）',
            '単価登録単位',
            '商品重量',
            '返品重量'
        ];
    }
}
