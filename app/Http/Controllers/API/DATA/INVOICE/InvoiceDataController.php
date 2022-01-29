<?php

namespace App\Http\Controllers\API\DATA\INVOICE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvoiceDataController extends Controller
{
    public static function get_invoice_data($request)
    {
        // 対象データ取得
        $data_invoice_id=$request->data_invoice_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;

        $csv_data=data_invoice::select(
            'data_invoices.sta_sen_identifier',
            'data_invoices.sta_sen_ide_authority',
            'data_invoices.sta_rec_identifier',
            'data_invoices.sta_rec_ide_authority',
            'data_invoices.sta_doc_standard',
            'data_invoices.sta_doc_type_version',
            'data_invoices.sta_doc_instance_identifier',
            'data_invoices.sta_doc_type',
            'data_invoices.sta_doc_creation_date_and_time',
            'data_invoices.sta_bus_scope_instance_identifier',
            'data_invoices.sta_bus_scope_type',
            'data_invoices.sta_bus_scope_identifier',
            'data_invoices.mes_ent_unique_creator_identification',
            'data_invoices.mes_mes_sender_station_address',
            'data_invoices.mes_mes_ultimate_receiver_station_address',
            'data_invoices.mes_mes_immediate_receiver_station_addres',
            'data_invoices.mes_mes_number_of_trading_documents',
            'data_invoices.mes_mes_sys_key',
            'data_invoices.mes_mes_sys_value',
            'data_invoices.mes_lis_con_version',
            'data_invoices.mes_lis_doc_version',
            'data_invoices.mes_lis_ext_namespace',
            'data_invoices.mes_lis_ext_version',
            'data_invoices.mes_lis_pay_code',
            'data_invoices.mes_lis_pay_gln',
            'data_invoices.mes_lis_pay_name',
            'data_invoices.mes_lis_pay_name_sbcs',
            'dip.mes_lis_buy_code',
            'dip.mes_lis_buy_gln',
            'dip.mes_lis_buy_name',
            'dip.mes_lis_buy_name_sbcs',
            'dip.mes_lis_inv_pay_id',
            'dip.mes_lis_inv_pay_code',
            'dip.mes_lis_inv_pay_gln',
            'dip.mes_lis_inv_pay_name',
            'dip.mes_lis_inv_pay_name_sbcs',
            'dip.mes_lis_inv_per_begin_date',
            'dip.mes_lis_inv_per_end_date',
            'dipd.data_invoice_pay_detail_id',
            'dipd.mes_lis_inv_lin_lin_trade_number_reference',
            'dipd.mes_lis_inv_lin_lin_issue_classification_code',
            'dipd.mes_lis_inv_lin_lin_sequence_number',
            'dipd.mes_lis_inv_lin_tra_code',
            'dipd.mes_lis_inv_lin_tra_gln',
            'dipd.mes_lis_inv_lin_tra_name',
            'dipd.mes_lis_inv_lin_tra_name_sbcs',
            'dipd.mes_lis_inv_lin_sel_code',
            'dipd.mes_lis_inv_lin_sel_gln',
            'dipd.mes_lis_inv_lin_sel_name',
            'dipd.mes_lis_inv_lin_sel_name_sbcs',
            'dipd.mes_lis_inv_lin_det_goo_major_category',
            'dipd.mes_lis_inv_lin_det_goo_sub_major_category',
            'dipd.mes_lis_inv_lin_det_transfer_of_ownership_date',
            'dipd.mes_lis_inv_lin_det_amo_requested_amount',
            'dipd.mes_lis_inv_lin_det_amo_req_plus_minus',
            'dipd.mes_lis_inv_lin_det_amo_tax',
            'dipd.mes_lis_inv_lin_det_balance_carried_code',
            'dipd.mes_lis_inv_lin_det_credit_or_unsettlement',
            'dipd.mes_lis_inv_lin_det_pay_code',
            'dipd.mes_lis_inv_lin_det_tax_tax_type_code',
            'dipd.mes_lis_inv_lin_det_tax_tax_rate'
        )
        ->join('data_invoice_pays as dip', 'dip.data_invoice_id', '=', 'data_invoices.data_invoice_id')
        ->join('data_invoice_pay_details as dipd', 'dipd.data_invoice_pay_id', '=', 'dip.data_invoice_pay_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_invoices.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereNull('dipd.deleted_at');
        if ($request->page_title=='invoice_list') {
            $mes_lis_inv_pay_code=$request->mes_lis_inv_pay_code;
            $send_datetime_status=$request->send_datetime_status;
            $decision_datetime_status=$request->decision_datetime_status;
            $mes_lis_inv_per_begin_date=$request->mes_lis_inv_per_begin_date;
            $mes_lis_inv_per_end_date=$request->mes_lis_inv_per_end_date;
            $trade_number=$request->trade_number;
            $mes_lis_inv_per_begin_date = $mes_lis_inv_per_begin_date!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_inv_per_begin_date)):config('const.FROM_DATETIME'); // 受信日時開始
            $mes_lis_inv_per_end_date = $mes_lis_inv_per_end_date!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_inv_per_end_date)):config('const.TO_DATETIME'); // 受信日時終了
            if ($trade_number!=null) {
                $csv_data=$csv_data->where('dipd.mes_lis_inv_lin_lin_trade_number_reference', '=', $trade_number);
            }
            if ($mes_lis_inv_pay_code!=null) {
                $csv_data=$csv_data->where('dip.mes_lis_inv_pay_code', '=', $mes_lis_inv_pay_code);
            }
            $csv_data=$csv_data->whereBetween('dip.mes_lis_inv_per_end_date', [$mes_lis_inv_per_begin_date,$mes_lis_inv_per_end_date]);

            // will confirm
            if ($decision_datetime_status=='未確定あり') {
                $csv_data=$csv_data->whereNull('dipd.decision_datetime');
            } elseif ($decision_datetime_status=='確定済') {
                $csv_data=$csv_data->whereNotNull('dipd.decision_datetime');
            }
            if ($send_datetime_status=='未送信あり') {
                $csv_data=$csv_data->whereNull('dipd.send_datetime');
            } elseif ($send_datetime_status=='送信済') {
                $csv_data=$csv_data->whereNotNull('dipd.send_datetime');
            }
        } elseif ($request->page_title=='invoice_details_list') {
            $number_reference=$request->mes_lis_inv_lin_lin_trade_number_reference;
            $decision_datetime_status=$request->decision_datetime_status;
            $send_datetime_status=$request->send_datetime_status;
            $mes_lis_inv_lin_tra_code=$request->mes_lis_inv_lin_tra_code;
            $category_code = $request->category_code;
            $category_code =$category_code['category_code'];
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $payment_datetime_status=$request->payment_datetime_status;
            $from_date = $from_date!=null? date('Y-m-d 00:00:00', strtotime($from_date)):config('const.FROM_DATETIME'); // 受信日時開始
            $to_date = $to_date!=null? date('Y-m-d 23:59:59', strtotime($to_date)):config('const.TO_DATETIME'); // 受信日時終了
            $param_data = $request->param_data;
            $csv_data=$csv_data->leftJoin('data_payment_pays as dpp', function ($join) {
                $join->on('dpp.mes_lis_pay_pay_code', '=', 'dip.mes_lis_inv_pay_code');
                $join->on('dpp.mes_lis_pay_per_end_date', '=', 'dip.mes_lis_inv_per_end_date');
                $join->on('dpp.mes_lis_buy_code', '=', 'dip.mes_lis_buy_code');
            })->leftJoin('data_payment_pay_details as dppd', function ($join) {
                $join->on('dppd.data_payment_pay_id', '=', 'dpp.data_payment_pay_id');
                $join->on('dipd.mes_lis_inv_lin_lin_trade_number_reference', '=', 'dppd.mes_lis_pay_lin_lin_trade_number_reference');
                $join->on('dipd.mes_lis_inv_lin_det_transfer_of_ownership_date', '=', 'dppd.mes_lis_pay_lin_det_transfer_of_ownership_date');
            })
            ->where('data_invoices.data_invoice_id', $data_invoice_id)
            ->whereBetween('dipd.mes_lis_inv_lin_det_transfer_of_ownership_date', [$from_date,$to_date]);
            if ($decision_datetime_status=='未確定あり') {
                $csv_data=$csv_data->whereNull('dipd.decision_datetime');
            } elseif ($decision_datetime_status=='確定済') {
                $csv_data=$csv_data->whereNotNull('dipd.decision_datetime');
            }
            if ($payment_datetime_status=='支払い済み') {
                $csv_data=$csv_data->whereNotNull('dppd.mes_lis_pay_lin_det_pay_out_date');
            } elseif ($payment_datetime_status=='未払い') {
                $csv_data=$csv_data->whereNull('dppd.mes_lis_pay_lin_det_pay_out_date');
            }
            if ($send_datetime_status=='未確定あり') {
                $csv_data=$csv_data->whereNull('dipd.send_datetime');
            } elseif ($send_datetime_status=='確定済') {
                $csv_data=$csv_data->whereNotNull('dipd.send_datetime');
            }

            if ($number_reference!=null) {
                $csv_data=$csv_data->where('dipd.mes_lis_inv_lin_lin_trade_number_reference', '=', $number_reference);
            }

            if ($category_code!='*') {
                $csv_data=$csv_data->where('dipd.mes_lis_inv_lin_det_goo_major_category', '=', $category_code);
            }

            if ($mes_lis_inv_lin_tra_code!='') {
                $csv_data=$csv_data->where('dipd.mes_lis_inv_lin_tra_code', '=', $mes_lis_inv_lin_tra_code);
            }
            // $csv_data=$csv_data->orderBy('dipd.'.$sort_by,$sort_type);
            $csv_data=$csv_data->where('dip.mes_lis_inv_per_end_date', $param_data['end_date'])
            ->where('dip.mes_lis_inv_pay_code', $param_data['pay_code']);
            // send_data
            if ($request->send_data==true) {
                $csv_data = $csv_data->whereNotNull('dipd.decision_datetime')
                ->whereNull('dipd.send_datetime');
            }
            // =======
        }
        $csv_data=$csv_data->orderBy("dipd.mes_lis_inv_lin_lin_trade_number_reference")
        ->take(config('const.DOWNLOAD_DATA_LIMIT'));

        $invoice_datas = $csv_data->get()->toArray();

        $invoice_data=array();
        $pay_id_array=array();
        foreach ($invoice_datas as $key => $value) {
            //
            $pay_id_array[]=$value['data_invoice_pay_detail_id'];
            unset($value['data_invoice_pay_detail_id']);
            $invoice_data[]=$value;
        }
        $pay_id_array=array_values(array_unique($pay_id_array));
        self::checkDateTimeChange($pay_id_array);
        Log::debug(__METHOD__.':end---');
        return ['invoice_data'=>$invoice_data,'total_data'=>$csv_data->count(),'pay_id_array'=>$pay_id_array];
    }
    public static function checkDateTimeChange($pay_id_array){
        $today=date('y-m-d H:i:s');
        foreach ($pay_id_array as $key => $pay_id) {
            data_invoice_pay_detail::where('data_invoice_pay_detail_id',$pay_id)
            ->whereNull('check_datetime')
            ->update(['check_datetime'=>$today]);
        }
    }
    public static function invoiceCsvHeading()
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
            '請求書番号',
            '請求取引先コード',
            '請求取引先GLN',
            '請求取引先名',
            '請求取引先名カナ',
            '対象期間開始',
            '対象期間終了',
            '取引番号（発注・返品）',
            '発行区分',
            '連番',
            '計上部署コード',
            '計上部署GLN',
            '計上部署名称',
            '計上部署名称（カナ）',
            '取引先コード',
            '取引先GLN',
            '取引先名称',
            '取引先名称カナ',
            '商品分類（大）',
            '商品分類（中）',
            '計上日',
            '請求金額',
            '請求金額符号',
            '税額合計金額',
            '請求区分',
            '未払買掛区分',
            '支払内容',
            '税区分',
            '税率'
        ];
    }
    public static function invoiceCompareCsvHeading()
    {
        return [
            "取引先コード",
            "取引番号（発注・返品）",
            "直接納品先コード",
            "直接納品先名称",
            // "shipment_delivery_date",
            "出荷配送日",
            "計上日",
            "原価金額合計",
            "原価金額合計", // "mes_lis_acc_tot_tot_net_price_total"
            "取引明細番号（発注・返品）",
            "商品コード（発注用）",
            "商品名",
            "出荷数量（バラ）",
            "受領数量（バラ数）", //"mes_lis_acc_lin_qua_rec_quantity",
            "原価金額（出荷）",
            "原価金額（受領）", //"mes_lis_acc_lin_amo_item_net_price",
            "請求金額",
            "作業状況",
        ];
    }
}
