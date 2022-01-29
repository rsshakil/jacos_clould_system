<?php

namespace App\Http\Controllers\API\DATA\PAYMENT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DATA\PAYMENT\data_payment;
use App\Models\DATA\PAYMENT\data_payment_pay;
use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\ADM\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    // private $all_used_fun;

    // public function __construct()
    // {
    //     $this->all_used_fun = new AllUsedFunction();
    //     // $this->all_used_fun->folder_create('app/'.config('const.PAYMENT_CSV_PATH'));
    // }
    public static function getPaymentData($request)
    {
        // 対象データ取得
        $data_payment_id=$request->data_payment_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;


        $csv_data=data_payment_pay::select(
            // data_payments
            'dp.receive_datetime',
            'dp.sta_sen_identifier',
            'dp.sta_sen_ide_authority',
            'dp.sta_rec_identifier',
            'dp.sta_rec_ide_authority',
            'dp.sta_doc_standard',
            'dp.sta_doc_type_version',
            'dp.sta_doc_instance_identifier',
            'dp.sta_doc_type',
            'dp.sta_doc_creation_date_and_time',
            'dp.sta_bus_scope_instance_identifier',
            'dp.sta_bus_scope_type',
            'dp.sta_bus_scope_identifier',
            'dp.mes_ent_unique_creator_identification',
            'dp.mes_mes_sender_station_address',
            'dp.mes_mes_ultimate_receiver_station_address',
            'dp.mes_mes_immediate_receiver_station_addres',
            'dp.mes_mes_number_of_trading_documents',
            'dp.mes_mes_sys_key',
            'dp.mes_mes_sys_value',
            'dp.mes_lis_con_version',
            'dp.mes_lis_doc_version',
            'dp.mes_lis_ext_namespace',
            'dp.mes_lis_ext_version',
            'dp.mes_lis_pay_code',
            'dp.mes_lis_pay_gln',
            'dp.mes_lis_pay_name',
            'dp.mes_lis_pay_name_sbcs',
            'data_payment_pays.data_payment_pay_id',
            'data_payment_pays.mes_lis_buy_code',
            'data_payment_pays.mes_lis_buy_gln',
            'data_payment_pays.mes_lis_buy_name',
            'data_payment_pays.mes_lis_buy_name_sbcs',
            'data_payment_pays.mes_lis_pay_pay_code',
            'data_payment_pays.mes_lis_pay_pay_id',
            'data_payment_pays.mes_lis_pay_pay_gln',
            'data_payment_pays.mes_lis_pay_pay_name',
            'data_payment_pays.mes_lis_pay_pay_name_sbcs',
            'data_payment_pays.mes_lis_pay_per_begin_date',
            'data_payment_pays.mes_lis_pay_per_end_date',
            'dppd.mes_lis_pay_lin_lin_trade_number_reference',
            'dppd.mes_lis_pay_lin_lin_issue_classification_code',
            'dppd.mes_lis_pay_lin_lin_sequence_number',
            'dppd.mes_lis_pay_lin_tra_code',
            'dppd.mes_lis_pay_lin_tra_gln',
            'dppd.mes_lis_pay_lin_tra_name',
            'dppd.mes_lis_pay_lin_tra_name_sbcs',
            'dppd.mes_lis_pay_lin_sel_code',
            'dppd.mes_lis_pay_lin_sel_gln',
            'dppd.mes_lis_pay_lin_sel_name',
            'dppd.mes_lis_pay_lin_sel_name_sbcs',
            'dppd.mes_lis_pay_lin_det_goo_major_category',
            'dppd.mes_lis_pay_lin_det_goo_sub_major_category',
            'dppd.mes_lis_pay_lin_det_transfer_of_ownership_date',
            'dppd.mes_lis_pay_lin_det_pay_out_date',
            'dppd.mes_lis_pay_lin_det_amo_requested_amount',
            'dppd.mes_lis_pay_lin_det_amo_req_plus_minus',
            'dppd.mes_lis_pay_lin_det_amo_payable_amount',
            'dppd.mes_lis_pay_lin_det_amo_pay_plus_minus',
            'dppd.mes_lis_pay_lin_det_amo_optional_amount',
            'dppd.mes_lis_pay_lin_det_amo_opt_plus_minus',
            'dppd.mes_lis_pay_lin_det_amo_tax',
            'dppd.mes_lis_pay_lin_det_trade_type_code',
            'dppd.mes_lis_pay_lin_det_balance_carried_code',
            'dppd.mes_lis_pay_lin_det_creditor_unsettled_code',
            'dppd.mes_lis_pay_lin_det_verification_result_code',
            'dppd.mes_lis_pay_lin_det_pay_code',
            'dppd.mes_lis_pay_lin_det_det_code',
            'dppd.mes_lis_pay_lin_det_det_meaning',
            'dppd.mes_lis_pay_lin_det_det_meaning_sbcs',
            'dppd.mes_lis_pay_lin_det_payment_method_code',
            'dppd.mes_lis_pay_lin_det_tax_tax_type_code',
            'dppd.mes_lis_pay_lin_det_tax_tax_rate'
        )
        ->join('data_payments as dp', 'dp.data_payment_id', '=', 'data_payment_pays.data_payment_id')
        ->join('data_payment_pay_details as dppd', 'dppd.data_payment_pay_id', '=', 'data_payment_pays.data_payment_pay_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dp.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id);
        if ($request->page_title=='payment_list') {
            $mes_lis_pay_pay_code = $request->mes_lis_pay_pay_code;
            $mes_lis_buy_name = $request->mes_lis_buy_name;
            $receive_date_from = $request->receive_date_from;
            $receive_date_to = $request->receive_date_to;
            $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $mes_lis_pay_per_end_date_from = $request->mes_lis_pay_per_end_date_from;
            $mes_lis_pay_per_end_date_to = $request->mes_lis_pay_per_end_date_to;
            $mes_lis_pay_per_end_date_from = $mes_lis_pay_per_end_date_from!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_pay_per_end_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $mes_lis_pay_per_end_date_to = $mes_lis_pay_per_end_date_to!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_pay_per_end_date_to)):config('const.TO_DATETIME'); // 受信日時終了
            $mes_lis_pay_lin_det_pay_out_date_from = $request->mes_lis_pay_lin_det_pay_out_date_from;
            $mes_lis_pay_lin_det_pay_out_date_to = $request->mes_lis_pay_lin_det_pay_out_date_to;
            $mes_lis_pay_lin_det_pay_out_date_from = $mes_lis_pay_lin_det_pay_out_date_from!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_pay_lin_det_pay_out_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
            $mes_lis_pay_lin_det_pay_out_date_to = $mes_lis_pay_lin_det_pay_out_date_to!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_pay_lin_det_pay_out_date_to)):config('const.TO_DATETIME'); // 受信日時終了

            $check_datetime = $request->check_datetime;
            $trade_number = $request->trade_number;

            if ($trade_number!=null) {
                $csv_data=$csv_data->where('dppd.mes_lis_pay_lin_lin_trade_number_reference', '=', $trade_number);
            }
            if ($mes_lis_pay_pay_code !=null) {
                $csv_data=$csv_data->where('data_payment_pays.mes_lis_pay_pay_code', $mes_lis_pay_pay_code);
            }
            $csv_data= $csv_data->whereBetween('dp.receive_datetime', [$receive_date_from, $receive_date_to])
            ->whereBetween('data_payment_pays.mes_lis_pay_per_end_date', [$mes_lis_pay_per_end_date_from, $mes_lis_pay_per_end_date_to])
            ->whereBetween('dppd.mes_lis_pay_lin_det_pay_out_date', [$mes_lis_pay_lin_det_pay_out_date_from, $mes_lis_pay_lin_det_pay_out_date_to]);
            if ($mes_lis_buy_name !=null) {
                $csv_data=$csv_data->where('data_payment_pays.mes_lis_buy_name', $mes_lis_buy_name);
            }
            if ($check_datetime!='*') {
                if ($check_datetime==1) {
                    $csv_data=$csv_data->whereNull('data_payment_pays.check_datetime');
                } else {
                    $csv_data=$csv_data->whereNotNull('data_payment_pays.check_datetime');
                }
            }
        } elseif ($request->page_title=='payment_details_list') {
            $pay_code = $request->pay_code;
            $end_date = $request->end_date;
            $out_date = $request->out_date;
            $whereClause = [
                'dp.data_payment_id'  => $data_payment_id,
                'data_payment_pays.mes_lis_pay_pay_code'  => $pay_code,
                'data_payment_pays.mes_lis_pay_per_end_date'   => $end_date,
                'dppd.mes_lis_pay_lin_det_pay_out_date' => $out_date
            ];
            $csv_data=$csv_data->where($whereClause);
        }
        $csv_data=$csv_data->orderBy('dppd.mes_lis_pay_lin_lin_trade_number_reference', "ASC")
        ->take(config('const.DOWNLOAD_DATA_LIMIT'));
        $payment_datas = $csv_data->get()->toArray();

        $payment_data=array();
        $pay_id_array=array();
        foreach ($payment_datas as $key => $value) {
            //
            $pay_id_array[]=$value['data_payment_pay_id'];
            unset($value['data_payment_pay_id']);
            $payment_data[]=$value;
        }
        $pay_id_array=array_values(array_unique($pay_id_array));
        self::checkDateTimeChange($pay_id_array);
        Log::debug(__METHOD__.':end---');
        return ['payment_data'=>$payment_data,'total_data'=>$csv_data->count(),'pay_id_array'=>$pay_id_array];
    }
    public static function checkDateTimeChange($pay_id_array){
        $today=date('y-m-d H:i:s');
        foreach ($pay_id_array as $key => $pay_id) {
            data_payment_pay::where('data_payment_pay_id',$pay_id)
            ->whereNull('check_datetime')
            ->update(['check_datetime'=>$today]);
        }
    }

    public static function paymentCsvHeading()
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
            '支払日',
            '請求金額',
            '請求金額符号',
            '支払金額',
            '支払金額符号',
            '金額(小売自由使用)',
            '金額符号(小売自由使用)',
            '税額合計金額',
            '処理種別',
            '請求区分',
            '未払買掛区分',
            '照合結果',
            '支払内容',
            '支払内容（個別）',
            '支払内容（個別名称）',
            '支払内容（個別名称カナ）',
            '支払方法区分',
            '税区分',
            '税率'
        ];
    }

    public static function getUnpaidData($request)
    {
        $byr_buyer_id=$request->byr_buyer_id;
        $end_date=$request->end_date;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $result = data_invoice_pay_detail::select(
            'data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_code',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_name',
            'data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date',
            'data_invoice_pay_details.mes_lis_inv_lin_det_amo_req_plus_minus',
            'data_invoice_pay_details.mes_lis_inv_lin_det_amo_requested_amount')
            ->join('data_invoice_pays as dip','dip.data_invoice_pay_id','=','data_invoice_pay_details.data_invoice_pay_id')
            ->join('data_invoices as di','di.data_invoice_id','=','dip.data_invoice_id')
            ->leftJoin('data_payment_pays as dpp', function($join){
            $join->on('dpp.mes_lis_pay_pay_code', '=', 'dip.mes_lis_inv_pay_code');
            $join->on('dpp.mes_lis_pay_per_end_date', '=', 'dip.mes_lis_inv_per_end_date');
            $join->on('dpp.mes_lis_buy_code', '=', 'dip.mes_lis_buy_code');
        })
        ->join('cmn_connects as cc','cc.cmn_connect_id','=','di.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->where('cc.slr_seller_id',$slr_seller_id)
        ->where('dip.mes_lis_inv_per_end_date',$end_date)
        ->whereNull('dpp.data_payment_pay_id')
        ->whereNotNull('data_invoice_pay_details.send_datetime')
        ->orderBy('data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference', "ASC")
        ->orderBy('data_invoice_pay_details.mes_lis_inv_lin_tra_code', "ASC")
        ->orderBy('data_invoice_pay_details.mes_lis_inv_lin_tra_name', "ASC")
        ->orderBy('data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date', "ASC")
        ->orderBy('data_invoice_pay_details.mes_lis_inv_lin_det_amo_requested_amount', "ASC");
        return $result;
    }
    public static function paymentUnpaidCsvHeading()
    {
        return [
            '取引番号（発注・返品）',
            '計上部署コード',
            '計上部署名称',
            '計上日',
            '請求金額符号',
            '請求金額',
        ];
    }
}
