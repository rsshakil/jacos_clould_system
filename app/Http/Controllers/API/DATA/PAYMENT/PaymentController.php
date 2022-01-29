<?php

namespace App\Http\Controllers\API\DATA\PAYMENT;

use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\DATA\PAYMENT\DataController;
use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use App\Models\BYR\byr_buyer;
use App\Models\DATA\PAYMENT\data_payment;
use App\Models\DATA\PAYMENT\data_payment_pay;
use App\Models\DATA\PAYMENT\data_payment_pay_detail;
use App\Models\DATA\INVOICE\data_invoice;
use App\Traits\Csv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    private $all_used_fun;
    private $page;
    private $total_page;
    private $page_numberX;
    private $page_numberY;
    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.PAYMENT_DOWNLOAD_CSV_PATH'));
        $this->page=0;
        $this->total_page=0;
        $this->page_numberX=168;
        $this->page_numberY=15;
    }
    public function getPaymentList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // Log::debug($request);
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        // 条件指定検索
        $mes_lis_pay_pay_code = $request->mes_lis_pay_pay_code; // 受信日時開始
        $receive_date_from = $request->receive_date_from; // 受信日時開始
        $receive_date_to = $request->receive_date_to; // 受信日時終了
        $receive_date_from = $receive_date_from!=null? date('Y-m-d 00:00:00', strtotime($receive_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $receive_date_to = $receive_date_to!=null? date('Y-m-d 23:59:59', strtotime($receive_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $mes_lis_buy_name = $request->mes_lis_buy_name; // 納品日開始
        $mes_lis_pay_per_end_date_from = $request->mes_lis_pay_per_end_date_from; // 納品日開始
        $mes_lis_pay_per_end_date_to = $request->mes_lis_pay_per_end_date_to; // 納品日終了
        $mes_lis_pay_per_end_date_from = $mes_lis_pay_per_end_date_from!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_pay_per_end_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $mes_lis_pay_per_end_date_to = $mes_lis_pay_per_end_date_to!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_pay_per_end_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $mes_lis_pay_lin_det_pay_out_date_from = $request->mes_lis_pay_lin_det_pay_out_date_from;
        $mes_lis_pay_lin_det_pay_out_date_to = $request->mes_lis_pay_lin_det_pay_out_date_to;
        $mes_lis_pay_lin_det_pay_out_date_from = $mes_lis_pay_lin_det_pay_out_date_from!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_pay_lin_det_pay_out_date_from)):config('const.FROM_DATETIME'); // 受信日時開始
        $mes_lis_pay_lin_det_pay_out_date_to = $mes_lis_pay_lin_det_pay_out_date_to!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_pay_lin_det_pay_out_date_to)):config('const.TO_DATETIME'); // 受信日時終了
        $check_datetime = $request->check_datetime; // 便
        $trade_number = $request->trade_number;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $authUser = User::find($adm_user_id);
        $cmn_company_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = $this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        // 検索
        $query = data_payment_pay::select(
            'dppd.data_payment_pay_id',
            'dp.data_payment_id',
            'dp.receive_datetime',
            'data_payment_pays.mes_lis_pay_pay_code',
            'data_payment_pays.mes_lis_buy_name',
            'data_payment_pays.check_datetime',
            'data_payment_pays.mes_lis_pay_per_end_date',
            'dppd.mes_lis_pay_lin_det_pay_out_date',
            'dppd.mes_lis_pay_lin_det_amo_payable_amount',
            DB::raw('sum(dppd.mes_lis_pay_lin_det_amo_payable_amount+dppd.mes_lis_pay_lin_det_amo_tax) as total_amount')
        )
            ->join('data_payments as dp', 'dp.data_payment_id', '=', 'data_payment_pays.data_payment_id')
            ->join('data_payment_pay_details as dppd', 'data_payment_pays.data_payment_pay_id', '=', 'dppd.data_payment_pay_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dp.cmn_connect_id')
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->where('cc.slr_seller_id', $slr_seller_id);

        // 取引先コード
        Log::debug('mes_lis_pay_pay_code:'.$mes_lis_pay_pay_code);
        if ($mes_lis_pay_pay_code !=null) {
            $query =$query->where('data_payment_pays.mes_lis_pay_pay_code', $mes_lis_pay_pay_code);
        }

        $query= $query->whereBetween('dp.receive_datetime', [$receive_date_from, $receive_date_to])
        ->whereBetween('data_payment_pays.mes_lis_pay_per_end_date', [$mes_lis_pay_per_end_date_from, $mes_lis_pay_per_end_date_to])
        ->whereBetween('dppd.mes_lis_pay_lin_det_pay_out_date', [$mes_lis_pay_lin_det_pay_out_date_from, $mes_lis_pay_lin_det_pay_out_date_to]);

        // 発注者名称
        if ($mes_lis_buy_name !=null) {
            $query =$query->where('data_payment_pays.mes_lis_buy_name', $mes_lis_buy_name);
        }

        // 参照状況
        if ($check_datetime!='*') {
            if ($check_datetime==1) {
                $query= $query->whereNull('data_payment_pays.check_datetime');
            } else {
                $query= $query->whereNotNull('data_payment_pays.check_datetime');
            }
        }

        $query =$query->where('dppd.mes_lis_pay_lin_det_pay_code', '3003')
            ->groupBy('dp.receive_datetime')
            ->groupBy('data_payment_pays.mes_lis_pay_pay_code')
            ->groupBy('data_payment_pays.mes_lis_pay_per_end_date')
            ->groupBy('dppd.mes_lis_pay_lin_det_pay_out_date');


        //  サブクエリ接続
        $query =  data_payment_pay_detail::select(
            'p.data_payment_id',
            'p.receive_datetime',
            'p.mes_lis_pay_pay_code',
            'p.mes_lis_buy_name',
            'p.check_datetime',
            'p.mes_lis_pay_per_end_date',
            'p.mes_lis_pay_lin_det_pay_out_date',
            'p.mes_lis_pay_lin_det_amo_payable_amount',
            'p.total_amount'
        )->joinsub($query, 'p', function ($join) {
            $join->on('data_payment_pay_details.data_payment_pay_id', '=', 'p.data_payment_pay_id');
        })->groupBy('p.data_payment_id')
        ->orderBy('p.' . $sort_by, $sort_type);

        // 伝票番号
        if ($trade_number!=null) {
            $query=$query->where('data_payment_pay_details.mes_lis_pay_lin_lin_trade_number_reference', '=', $trade_number);
        }

        // 検索実行
        $result =$query->paginate($per_page);

        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);

        Log::debug(__METHOD__.':end---');
        return response()->json(['payment_item_list' => $result, 'byr_buyer_list' => $byr_buyer]);
    }

    public function get_payment_detail_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_payment_id = $request->data_payment_id;
        $pay_code = $request->pay_code;
        $end_date = $request->end_date;
        $out_date = $request->out_date;
        $today=date('Y-m-d H:i:s');
        $whereClause = [
            'dpp.mes_lis_pay_pay_code'  => $pay_code,
            'dpp.mes_lis_pay_per_end_date'   => $end_date,
            'dppd.mes_lis_pay_lin_det_pay_out_date' => $out_date
        ];
        $result = data_payment::select(
            'data_payments.data_payment_id',
            'data_payments.receive_datetime',
            'dpp.mes_lis_pay_pay_code',
            'dpp.mes_lis_pay_pay_name',
            'dpp.mes_lis_pay_pay_gln',
            'dpp.mes_lis_pay_pay_id',
            'dpp.mes_lis_buy_name',
            'dpp.mes_lis_buy_code',
            'dpp.check_datetime',
            'dpp.mes_lis_pay_per_end_date',
            'dppd.mes_lis_pay_lin_det_pay_out_date',
            'dppd.mes_lis_pay_lin_det_amo_payable_amount',
            DB::raw('sum(dppd.mes_lis_pay_lin_det_amo_payable_amount+dppd.mes_lis_pay_lin_det_amo_tax) as total_amount')
        )->join('data_payment_pays as dpp', 'data_payments.data_payment_id', '=', 'dpp.data_payment_id')
            ->join('data_payment_pay_details as dppd', 'dpp.data_payment_pay_id', '=', 'dppd.data_payment_pay_id')
            ->where('dpp.data_payment_id', $data_payment_id)
            ->where('dppd.mes_lis_pay_lin_det_pay_code', '3003')
            ->where($whereClause)
            ->first();
        $payCodeList = data_payment_pay::select(
            'data_payments.data_payment_id',
            'data_payments.receive_datetime',
            'data_payment_pays.mes_lis_pay_pay_code',
            'data_payment_pay_details.mes_lis_pay_lin_sel_code',
            'data_payment_pays.mes_lis_pay_pay_name',
            'data_payment_pays.mes_lis_pay_pay_gln',
            'data_payment_pays.mes_lis_pay_pay_id',
            'data_payment_pays.mes_lis_buy_name',
            'data_payment_pays.mes_lis_buy_code',
            'data_payment_pays.check_datetime',
            'data_payment_pays.mes_lis_pay_per_end_date',
            'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date',
            'data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount',
            'data_payment_pay_details.mes_lis_pay_lin_det_amo_tax',
            DB::raw('SUM(CASE WHEN data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus = "+" or data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus is NULL then data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount
             WHEN data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus = "-" then - data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount
        END)+data_payment_pay_details.mes_lis_pay_lin_det_amo_tax as total_amount'),
            // DB::raw('sum(data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount+data_payment_pay_details.mes_lis_pay_lin_det_amo_tax) as total_amount')
        )
        ->join('data_payments', 'data_payments.data_payment_id', '=', 'data_payment_pays.data_payment_id')
        ->join('data_payment_pay_details', 'data_payment_pays.data_payment_pay_id', '=', 'data_payment_pay_details.data_payment_pay_id')
        ->where('data_payment_pays.data_payment_id', $data_payment_id)->where('data_payment_pay_details.mes_lis_pay_lin_det_pay_code', '3003')->groupBy('data_payment_pay_details.mes_lis_pay_lin_sel_code')->get();


        $paymentdetailRghtTable = data_payment_pay_detail::select(
            DB::raw('SUM(CASE WHEN data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus = "+" or data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus is NULL then data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount
             WHEN data_payment_pay_details.mes_lis_pay_lin_det_amo_pay_plus_minus = "-" then - data_payment_pay_details.mes_lis_pay_lin_det_amo_payable_amount
        END) as mes_lis_pay_lin_det_amo_payable_amount_sum'),
            'data_payment_pay_details.mes_lis_pay_lin_det_det_meaning',
            'data_payment_pay_details.mes_lis_pay_lin_det_det_code',
            'dpp.mes_lis_pay_pay_code',
            'data_payment_pay_details.mes_lis_pay_lin_sel_code'
        )
            ->join('data_payment_pays as dpp', 'data_payment_pay_details.data_payment_pay_id', '=', 'dpp.data_payment_pay_id')
            ->where(
                ['data_payment_pay_details.mes_lis_pay_lin_det_pay_code' => '2000',//have to check
                'dpp.data_payment_id' => $data_payment_id,
                'dpp.mes_lis_pay_pay_code'  => $pay_code,
                'dpp.mes_lis_pay_per_end_date'   => $end_date,
                'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date' => $out_date
                ]
            )
                ->groupBy('data_payment_pay_details.mes_lis_pay_lin_sel_code', 'data_payment_pay_details.mes_lis_pay_lin_det_det_code')->get();

        $pQ1 = data_payment_pay_detail::select(
            DB::raw('"仕入合計金額" as p_title'),
            // DB::raw('SUM(mes_lis_pay_lin_det_amo_requested_amount) as amount'),
            DB::raw('SUM(mes_lis_pay_lin_det_amo_payable_amount) as amount'),
            DB::raw('"1" as sumation_type')
        )
            ->join('data_payment_pays as dpp', 'data_payment_pay_details.data_payment_pay_id', '=', 'dpp.data_payment_pay_id')
            ->where(
                ['data_payment_pay_details.mes_lis_pay_lin_det_pay_code' => '3001',
                'dpp.data_payment_id' => $data_payment_id,
                'dpp.mes_lis_pay_pay_code'  => $pay_code,
                'dpp.mes_lis_pay_per_end_date'   => $end_date,
                'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date' => $out_date
                ]
            )
            ->groupBy('data_payment_pay_details.data_payment_pay_id');
        $pQ2 = data_payment_pay_detail::select(
            DB::raw('"仕入消費税" as p_title'),
            DB::raw('SUM(mes_lis_pay_lin_det_amo_tax) as amount'),
            DB::raw('"1" as sumation_type')
        )
            ->join('data_payment_pays as dpp', 'data_payment_pay_details.data_payment_pay_id', '=', 'dpp.data_payment_pay_id')

            ->where(
                ['data_payment_pay_details.mes_lis_pay_lin_det_pay_code' => '3001',
                'dpp.data_payment_id' => $data_payment_id,
                'dpp.mes_lis_pay_pay_code'  => $pay_code,
                'dpp.mes_lis_pay_per_end_date'   => $end_date,
                'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date' => $out_date
                ]
            )
            ->groupBy('data_payment_pay_details.data_payment_pay_id');

        $pQ3 = data_payment_pay_detail::select(
            DB::raw('"相殺合計金額" as p_title'),
            DB::raw('SUM(mes_lis_pay_lin_det_amo_payable_amount) as amount'),
            DB::raw('"2" as sumation_type')
        )
            ->join('data_payment_pays as dpp', 'data_payment_pay_details.data_payment_pay_id', '=', 'dpp.data_payment_pay_id')
            ->where(
                ['data_payment_pay_details.mes_lis_pay_lin_det_pay_code' => '3002',
                'dpp.data_payment_id' => $data_payment_id,
                'dpp.mes_lis_pay_pay_code'  => $pay_code,
                'dpp.mes_lis_pay_per_end_date'   => $end_date,
                'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date' => $out_date
                ]
            )
            ->groupBy('data_payment_pay_details.data_payment_pay_id');
        $pQ4 = data_payment_pay_detail::select(
            DB::raw('"相殺消費税" as p_title'),
            DB::raw('SUM(mes_lis_pay_lin_det_amo_tax) as amount'),
            DB::raw('"2" as sumation_type')
        )
            ->join('data_payment_pays as dpp', 'data_payment_pay_details.data_payment_pay_id', '=', 'dpp.data_payment_pay_id')

            ->where(
                ['data_payment_pay_details.mes_lis_pay_lin_det_pay_code' => '3002',
                'dpp.data_payment_id' => $data_payment_id,
                'dpp.mes_lis_pay_pay_code'  => $pay_code,
                'dpp.mes_lis_pay_per_end_date'   => $end_date,
                'data_payment_pay_details.mes_lis_pay_lin_det_pay_out_date' => $out_date
                ]
            )->groupBy('data_payment_pay_details.data_payment_pay_id');
        $pdtableleft = $pQ1->union($pQ2)->union($pQ3)->union($pQ4)->orderBy('sumation_type', 'ASC')->get();

        data_payment_pay::where(
            [
            'data_payment_pays.data_payment_id' => $data_payment_id,
            'data_payment_pays.mes_lis_pay_pay_code'  => $pay_code,
            'data_payment_pays.mes_lis_pay_per_end_date'   => $end_date
            ]
        )->whereNull('check_datetime')->update(['check_datetime'=>$today]);
        Log::debug(__METHOD__.':end---');
        return response()->json(['payment_item_header' => $result,'pay_code_list'=>$payCodeList, 'pdtableleft' => $pdtableleft, 'paymentdetailRghtTable' => $paymentdetailRghtTable]);
    }

    public function get_payment_item_detail_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $payment_id = $request->payment_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $per_page = $request->select_field_per_page_num;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $from_date = $from_date!=null? date('Y-m-d 00:00:00', strtotime($from_date)):config('const.FROM_DATETIME');
        $to_date = $to_date!=null? date('Y-m-d 23:59:59', strtotime($to_date)):config('const.TO_DATETIME');
        $category_code = $request->category_code;

        $category_code = $category_code['category_code'];

        $mes_lis_pay_lin_tra_code = $request->mes_lis_pay_lin_tra_code;
        $mes_lis_pay_lin_sel_code = $request->mes_lis_pay_lin_sel_code;

        $mes_lis_pay_lin_lin_trade_number_reference = $request->mes_lis_pay_lin_lin_trade_number_reference;
        $mes_lis_inv_lin_det_pay_code = $request->mes_lis_inv_lin_det_pay_code;
        $mes_lis_pay_lin_det_verification_result_code = $request->mes_lis_pay_lin_det_verification_result_code;
        $mes_lis_pay_lin_det_trade_type_code = $request->mes_lis_pay_lin_det_trade_type_code;
        $mes_lis_pay_lin_det_balance_carried_code = $request->mes_lis_pay_lin_det_balance_carried_code;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $pay_code_list=$request->pay_code_list;

        $pay_code = $request->pay_code;
        $sel_code = $request->sel_code;
        $end_date = $request->end_date;
        $out_date = $request->out_date;
        $whereClause = [
            'data_payment_pays.mes_lis_pay_pay_code'  => $pay_code,
            'data_payment_pays.mes_lis_pay_per_end_date'   => $end_date,
            'dppd.mes_lis_pay_lin_sel_code'  => $sel_code,
            'dppd.mes_lis_pay_lin_det_pay_out_date' => $out_date
        ];
        $result = data_payment_pay::select(
            'dp.data_payment_id',
            'dp.receive_datetime',
            'data_payment_pays.mes_lis_pay_pay_code',
            'data_payment_pays.mes_lis_pay_pay_name',
            'data_payment_pays.mes_lis_pay_pay_gln',
            'data_payment_pays.mes_lis_pay_pay_id',
            'data_payment_pays.mes_lis_buy_name',
            'data_payment_pays.mes_lis_buy_code',
            'data_payment_pays.check_datetime',
            'data_payment_pays.mes_lis_pay_per_end_date',
            'dppd.mes_lis_pay_lin_det_pay_out_date',
            'dppd.mes_lis_pay_lin_det_amo_payable_amount',
            'dppd.mes_lis_pay_lin_sel_code',
            DB::raw('SUM(CASE WHEN dppd.mes_lis_pay_lin_det_amo_pay_plus_minus = "+" or dppd.mes_lis_pay_lin_det_amo_pay_plus_minus is NULL then dppd.mes_lis_pay_lin_det_amo_payable_amount
             WHEN dppd.mes_lis_pay_lin_det_amo_pay_plus_minus = "-" then - dppd.mes_lis_pay_lin_det_amo_payable_amount
        END)+dppd.mes_lis_pay_lin_det_amo_tax as total_amount'),
            // DB::raw('(dppd.mes_lis_pay_lin_det_amo_payable_amount+dppd.mes_lis_pay_lin_det_amo_tax) as total_amount')
        )
            ->join('data_payments as dp', 'dp.data_payment_id', '=', 'data_payment_pays.data_payment_id')
            ->join('data_payment_pay_details as dppd', 'dppd.data_payment_pay_id', '=', 'data_payment_pays.data_payment_pay_id')
            ->where('data_payment_pays.data_payment_id', $payment_id)
           ->where('dppd.mes_lis_pay_lin_det_pay_code', '3003')//have to check
            ->where($whereClause)
            ->first();

        $result1 = data_payment_pay::select(
            'dppd.data_payment_pay_detail_id',
            'dppd.mes_lis_pay_lin_det_transfer_of_ownership_date',
            'dppd.mes_lis_pay_lin_det_goo_major_category',
            'dppd.mes_lis_pay_lin_tra_code',
            'dppd.mes_lis_pay_lin_lin_trade_number_reference',
            'dppd.mes_lis_pay_lin_det_pay_code',
            'dppd.mes_lis_pay_lin_det_trade_type_code',
            'dppd.mes_lis_pay_lin_det_balance_carried_code',
            'dppd.mes_lis_pay_lin_det_amo_requested_amount',
            'dppd.mes_lis_pay_lin_det_amo_payable_amount',
            'dppd.mes_lis_pay_lin_det_amo_pay_plus_minus',
            'dppd.mes_lis_pay_lin_det_verification_result_code'
        )
            ->join('data_payment_pay_details as dppd', 'dppd.data_payment_pay_id', '=', 'data_payment_pays.data_payment_pay_id')
            ->where(['data_payment_pays.data_payment_id' => $payment_id])
            ->where([
                'data_payment_pays.mes_lis_pay_pay_code'  => $pay_code,
                'dppd.mes_lis_pay_lin_sel_code'  => $sel_code,
                'data_payment_pays.mes_lis_pay_per_end_date'   => $end_date,
                'dppd.mes_lis_pay_lin_det_pay_out_date' => $out_date
            ])
            ->whereIn('dppd.mes_lis_pay_lin_det_pay_code', $pay_code_list)
            ->whereBetween('dppd.mes_lis_pay_lin_det_transfer_of_ownership_date', [$from_date, $to_date]);
        if ($mes_lis_pay_lin_tra_code != null) {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_tra_code', $mes_lis_pay_lin_tra_code);
        }
        if ($mes_lis_pay_lin_sel_code != null) {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_sel_code', $mes_lis_pay_lin_sel_code);
        }
        if ($mes_lis_pay_lin_lin_trade_number_reference != null) {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_lin_trade_number_reference', $mes_lis_pay_lin_lin_trade_number_reference);
        }

        if ($mes_lis_inv_lin_det_pay_code != '*') {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_det_pay_code', $mes_lis_inv_lin_det_pay_code);
        }

        if ($mes_lis_pay_lin_det_verification_result_code != '*') {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_det_verification_result_code', $mes_lis_pay_lin_det_verification_result_code);
        }

        if ($mes_lis_pay_lin_det_trade_type_code != '*') {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_det_trade_type_code', $mes_lis_pay_lin_det_trade_type_code);
        }

        if ($mes_lis_pay_lin_det_balance_carried_code != '*') {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_det_balance_carried_code', $mes_lis_pay_lin_det_balance_carried_code);
        }

        if ($category_code != '*') {
            $result1 = $result1->where('dppd.mes_lis_pay_lin_det_goo_major_category', $category_code);
        }
        $result1 = $result1->orderBy('dppd.' . $sort_by, $sort_type);

        $paymentdetailTopTable = $result1->paginate($per_page);
        $byr_buyer_category_list = $this->all_used_fun->get_allCategoryByByrId($byr_buyer_id);
        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $buyer_settings = $buyer_settings->setting_information;
        Log::debug(__METHOD__.':end---');
        return response()->json(['payment_item_header' => $result, 'paymentdetailTopTable' => $paymentdetailTopTable, 'byr_buyer_category_list' => $byr_buyer_category_list, 'buyer_settings' => $buyer_settings]);
    }
    public function paymentDownload(Request $request)
    {
        $downloadType = $request->downloadType;
        $new_file_name ='';
        $csv_data_count = 0;
        if ($downloadType == 1) {
            // CSV Download
            $new_file_name = $this->all_used_fun->downloadFileName($request, 'csv', '支払');
            $download_file_url = Config::get('app.url') . "storage/app" . config('const.PAYMENT_DOWNLOAD_CSV_PATH') . "/" . $new_file_name;
            // get shipment data query
            $payment_query = DataController::getPaymentData($request);
            $csv_data_count = $payment_query['total_data'];
            $payment_datas = $payment_query['payment_data'];
            $payment_data=array();
            foreach ($payment_datas as $key => $data) {
                unset($data['receive_datetime']);
                $payment_data[]=$data;
            }
            // return $payment_data;
            // CSV create
            Csv::create(
                config('const.PAYMENT_DOWNLOAD_CSV_PATH') . "/" . $new_file_name,
                $payment_data,
                DataController::paymentCsvHeading(),
                config('const.CSV_FILE_ENCODE')
            );
        }

        return response()->json(['message' => 'Success', 'status' => 1, 'new_file_name' => $new_file_name, 'url' => $download_file_url, 'csv_data_count' => $csv_data_count]);
    }
    public function paymentPdfDownload(Request $request)
    {
        $payment_query = DataController::getPaymentData($request);
        $payment_data = $payment_query['payment_data'];
        $csv_data_count = $payment_query['total_data'];

        $collection = collect($payment_data);
        $grouped = $collection->groupBy('mes_lis_pay_lin_sel_code');
        $payment_data_by_codes=$grouped->values()->all();

        $all_payment_data=array();
        foreach ($payment_data_by_codes as $code_key => $code_value) {
            $heading_array=array();
            $body_data_array=array();
            $footer_array=array();
            foreach ($code_value as $key => $value) {
                if ($value['mes_lis_pay_lin_det_pay_code']==3001||$value['mes_lis_pay_lin_det_pay_code']==3002||$value['mes_lis_pay_lin_det_pay_code']==3003) {
                    $heading_array[]=$value;
                }
                if ($value['mes_lis_pay_lin_det_pay_code']==1001||$value['mes_lis_pay_lin_det_pay_code']==1002||$value['mes_lis_pay_lin_det_pay_code']==1004) {
                    $body_data_array[]=$value;
                }
                if ($value['mes_lis_pay_lin_det_det_code']=='0010'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0020'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0030'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0040'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0050'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0060'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0070'
                    ||$value['mes_lis_pay_lin_det_det_code']=='0080') {
                    $footer_array[]=$value;
                }
            }
            $recs = collect($body_data_array);
            $sorted = $recs->sortBy(function ($product) {
                $sort1 = $product['mes_lis_pay_lin_tra_code'];
                $sort2 = $product['mes_lis_pay_lin_det_transfer_of_ownership_date'];
                $sort3 = $product['mes_lis_pay_lin_lin_trade_number_reference'];
                return [$sort1, $sort2, $sort3];
            });
            $body_data_sorted = $sorted->values()->all();

            $footer_collection = collect($footer_array);
            $footer_sorted = $footer_collection->sortBy(function ($ffoter_product) {
                $footer_sort1 = $ffoter_product['mes_lis_pay_lin_det_det_meaning'];
                $footer_sort2 = $ffoter_product['mes_lis_pay_lin_det_transfer_of_ownership_date'];
                $footer_sort3 = $ffoter_product['mes_lis_pay_lin_lin_trade_number_reference'];
                return [$footer_sort1, $footer_sort2, $footer_sort3];
            });
            $footer_data_sorted = $footer_sorted->values()->all();

            $single_payment_data=array(
                'headings_data'=>$heading_array,
                'body_data'=>$body_data_sorted,
                'footer_data'=>$footer_data_sorted,
            );
            $all_payment_data[]=$single_payment_data;
        }
        $total_page=0;
        foreach ($all_payment_data as $payment_key => $value) {
            $body_data=$value['body_data'];
            $footer_data=$value['footer_data'];
            $body_page=$this->pageGenerator($body_data, 37);
            $footer_page=$this->pageGenerator($footer_data, 53);
            $total_page+=($footer_page+$body_page);
        }
        // return $all_payment_data;
        $new_file_name = $this->all_used_fun->downloadFileName($request, 'pdf', '支払');
        $byr_json_settings = json_decode($this->all_used_fun->getByrJsonData($request));
        $download_file_url = Config::get('app.url') . "storage/" . config('const.PAYMENT_PDF_SAVE_PATH') . $new_file_name;
        $download_files=$this->pdfGenerate($all_payment_data, $new_file_name, $total_page, $byr_json_settings);

        return response()->json(['message' => 'Success', 'status' => 1, 'new_file_name' => $new_file_name, 'url' => $download_file_url, 'csv_data_count' => $csv_data_count]);
    }
    public function pageGenerator($page_data, $per_page_data=37)
    {
        $page=0;
        if (!empty($page_data)) {
            $page=1;
            foreach ($page_data as $key_footer => $footer) {
                if (!isset($footer[$key_footer+1]) && ($key_footer+1)==count($page_data)) {
                    if ($per_page_data==($key_footer)) {
                        $page+=1;
                    }
                }
                if ($per_page_data==$key_footer) {
                    if (count($page_data)-($key_footer+1)>0) {
                        $per_page_data+=53;
                        $page+=1;
                    }
                }
            }
        }else{
            $page+=1;
        }
        return $page;
    }
    public function pdfGenerate($pdf_datas=[], $new_file_name=null, $total_page=0, $byr_json_settings)
    {
        $pdf_file_paths=array();
        $receipt=$this->all_used_fun->fpdfRet();
        $payment_pdf_save_path=config('const.PAYMENT_PDF_SAVE_PATH');
        $this->total_page=$total_page;
        foreach ($pdf_datas as $pdf_data_key => $value) {
            $headings_data=$value['headings_data'];
            $body_data=$value['body_data'];
            $footer_data=$value['footer_data'];
            $x = 0;
            $y = 0;
            $sub_total=0;
            $gross_total=0;
            $receipt-> AddPage();
            $this->page+=1;
            $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
            $tplIdx = $receipt->importPage(1);
            $receipt->UseTemplate($tplIdx, null, null, null, null, true);
            $receipt->SetXY($this->page_numberX, $this->page_numberY);
            $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x, $y + 28);
            $receipt->Cell(0, 0, date('Y年m月d日', strtotime($headings_data[0]['receive_datetime'])), 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x + 17, $y + 37);
            $receipt->Write(0, $headings_data[0]['mes_lis_pay_pay_name']);
            $receipt->SetXY($x + 17, $y + 42);
            $receipt->Write(0, $headings_data[0]['mes_lis_pay_lin_sel_code']);
            $receipt->SetXY($x + 17, $y + 45);
            $receipt->Cell(0, 0, $headings_data[0]['mes_lis_buy_name'].'      ', 0, 1, 'R', 0, '', 0);
            $receipt->SetXY($x + 120, $y + 64);
            $receipt->Cell(21, 6, $headings_data[0]['mes_lis_pay_lin_det_pay_out_date'], 0, 1, 'R', 0, '', 0);
            foreach ($headings_data as $key => $heading) {
                $plus_minus=$heading['mes_lis_pay_lin_det_amo_pay_plus_minus']=='-'?$heading['mes_lis_pay_lin_det_amo_pay_plus_minus'].' ':'';
                $det_pay_code=$heading['mes_lis_pay_lin_det_pay_code'];
                if ($det_pay_code==3001) {
                    $receipt->SetXY($x + 48, $y + 64);
                    $receipt->Cell(20.5, 5.7, $plus_minus.number_format($heading['mes_lis_pay_lin_det_amo_payable_amount']), 0, 1, 'R', 0, '', 0);
                    $receipt->SetXY($x + 48, $y + 69.7);
                    $receipt->Cell(20.5, 4.7, $plus_minus.number_format($heading['mes_lis_pay_lin_det_amo_tax']), 0, 1, 'R', 0, '', 0);
                    $receipt->SetXY($x + 48, $y + 74.5);
                    $receipt->Cell(20.5, 4.7, $plus_minus.number_format(($heading['mes_lis_pay_lin_det_amo_payable_amount']+$heading['mes_lis_pay_lin_det_amo_tax'])), 0, 1, 'R', 0, '', 0);
                } elseif ($det_pay_code==3002) {
                    $receipt->SetXY($x + 68.5, $y + 64);
                    $receipt->Cell(20.5, 5.7, $plus_minus.number_format($heading['mes_lis_pay_lin_det_amo_payable_amount']), 0, 1, 'R', 0, '', 0);
                    $receipt->SetXY($x + 68.5, $y + 69.7);
                    $receipt->Cell(20.5, 4.7, $plus_minus.number_format($heading['mes_lis_pay_lin_det_amo_tax']), 0, 1, 'R', 0, '', 0);
                    $receipt->SetXY($x + 68.5, $y + 74.5);
                    $receipt->Cell(20.5, 4.7, $plus_minus.number_format(($heading['mes_lis_pay_lin_det_amo_payable_amount']+$heading['mes_lis_pay_lin_det_amo_tax'])), 0, 1, 'R', 0, '', 0);
                }
                if ($det_pay_code==3003) {
                    $receipt->SetXY($x + 89, $y + 74.5);
                    $receipt->Cell(26, 4.7, $plus_minus.number_format(($heading['mes_lis_pay_lin_det_amo_payable_amount']+$heading['mes_lis_pay_lin_det_amo_tax'])), 0, 1, 'R', 0, '', 0);
                }
            }
            $x = 26;
            $y = 92.5;
            $arr = array();
            $i = 0;
            $per_page_data=37;

            foreach ($body_data as $key => $data) {
                $receipt->SetXY($x, $y);
                $tra_name=$data['mes_lis_pay_lin_tra_name']?$data['mes_lis_pay_lin_tra_name']:$data['mes_lis_pay_lin_tra_name_sbcs'];
                $receipt->Cell(29, 4.5, $tra_name, 0, 1, 'R', 0, '', 0);
                $x += 29;
                $receipt->SetXY($x, $y);
                $receipt->Cell(16, 4.5, $data['mes_lis_pay_lin_tra_code'], 0, 1, 'R', 0, '', 0);
                $x += 16;
                $receipt->SetXY($x, $y);
                $receipt->Cell(24.5, 4.5, $data['mes_lis_pay_lin_det_transfer_of_ownership_date'], 0, 1, 'R', 0, '', 0);
                $x += 24.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(24.5, 4.5, $data['mes_lis_pay_lin_lin_trade_number_reference'], 0, 1, 'R', 0, '', 0);
                $x += 24.5;
                $receipt->SetXY($x, $y);
                $trade_type_code_val= $this->all_used_fun->getbyrjsonValueBykeyName('mes_lis_pay_lin_det_trade_type_code', $data['mes_lis_pay_lin_det_trade_type_code'], "payments", $byr_json_settings);
                $receipt->Cell(13.5, 4.5, $data['mes_lis_pay_lin_det_trade_type_code'].' '.$trade_type_code_val, 0, 1, 'R', 0, '', 0);
                $x += 13.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(18.5, 4.5, $data['mes_lis_pay_lin_det_amo_pay_plus_minus'].' '.number_format($data['mes_lis_pay_lin_det_amo_payable_amount']), 0, 1, 'R', 0, '', 0);
                $x += 18.5;
                $sub_total+=$data['mes_lis_pay_lin_det_amo_pay_plus_minus'].$data['mes_lis_pay_lin_det_amo_payable_amount'];
                if (!in_array($data['mes_lis_pay_lin_tra_code'], $arr) && $i==0) {
                    $arr[]=$data['mes_lis_pay_lin_tra_code'];
                    $i++;
                    if (!isset($body_data[$key+1]['mes_lis_pay_lin_tra_code']) || $body_data[$key+1]['mes_lis_pay_lin_tra_code']!=$body_data[$key]['mes_lis_pay_lin_tra_code']) {
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(17.5, 4.5, number_format($sub_total), 0, 1, 'R', 0, '', 0);
                        $sub_total = 0;
                        $i=0;
                    }
                } else {
                    if (isset($body_data[$key+1]['mes_lis_pay_lin_tra_code']) && in_array($body_data[$key+1]['mes_lis_pay_lin_tra_code'], $arr)) {
                        $i++;
                    } else {
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(17.5, 4.5, number_format($sub_total), 0, 1, 'R', 0, '', 0);
                        $sub_total = 0;
                        $i=0;
                    }
                }
                $x += 17.5;
                $gross_total+=$data['mes_lis_pay_lin_det_amo_pay_plus_minus'].$data['mes_lis_pay_lin_det_amo_payable_amount'];
                if (!isset($data[$key+1]) && ($key+1)==count($body_data)) {
                    if ($per_page_data==($key)) {
                        $receipt-> AddPage();
                        $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
                        $tplIdx = $receipt->importPage(2);
                        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
                        $x = 169.5;
                        $y = 23.7;
                        $this->page+=1;
                        $receipt->SetXY($this->page_numberX, $this->page_numberY);
                        $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
                    }
                    $y+=4.5;
                    $receipt->SetXY($x, $y);
                    $receipt->Cell(18, 4.5, number_format($gross_total), 0, 1, 'R', 0, '', 0);
                }
                $x = 26;
                $y +=4.5;
                if ($per_page_data==$key) {
                    if (count($body_data)-($key+1)>0) {
                        $receipt-> AddPage();
                        $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
                        $tplIdx = $receipt->importPage(2);
                        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
                        $x = 26;
                        $y = 28;
                        $per_page_data+=53;
                        $this->page+=1;
                        $receipt->SetXY($this->page_numberX, $this->page_numberY);
                        $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
                    }
                }
            }
            $receipt=$this->footerSet($receipt, $footer_data, $headings_data, 0, 32.5);
            $sub_total=0;
            $gross_total=0;
        }
        $pdf_file_path= $this->all_used_fun->pdfFileSave($receipt, $new_file_name, $payment_pdf_save_path);
        array_push($pdf_file_paths, $pdf_file_path);
        Log::debug(__METHOD__.':end---');
        Log::info("Total page: ".$this->page);
        return $pdf_file_paths;
    }
    public function footerSet($receipt, $footer_data, $headings_data, $x=0, $y=32.5)
    {
        if (!empty($footer_data)) {
            $receipt-> AddPage();
            $this->page+=1;
            $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
            $tplIdx = $receipt->importPage(3);
            $receipt->UseTemplate($tplIdx, null, null, null, null, true);
            $receipt->SetXY($this->page_numberX, $this->page_numberY);
            $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
            $x = 26;
            $y=28;
            $arr=array();
            $i=0;
            $sub_total=0;
            $gross_total=0;
            $per_page_data=52;
            foreach ($footer_data as $key_footer => $footer) {
                $y+=4.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(45, 4.5, $footer['mes_lis_pay_lin_det_det_meaning'], 0, 1, 'R', 0, '', 0);
                $x+=45;
                $receipt->SetXY($x, $y);
                $receipt->Cell(24.5, 4.5, $footer['mes_lis_pay_lin_det_transfer_of_ownership_date'], 0, 1, 'R', 0, '', 0);
                $x+=24.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(24.5, 4.5, $footer['mes_lis_pay_lin_lin_trade_number_reference'], 0, 1, 'R', 0, '', 0);
                $x+=24.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(13.5, 4.5, $footer['mes_lis_pay_lin_det_trade_type_code'], 0, 1, 'R', 0, '', 0);
                $x+=13.5;
                $receipt->SetXY($x, $y);
                $receipt->Cell(18.5, 4.5, $footer['mes_lis_pay_lin_det_amo_pay_plus_minus'].' '.number_format($footer['mes_lis_pay_lin_det_amo_payable_amount']), 0, 1, 'R', 0, '', 0);
                $x+=18.5;
                $sub_total+=$footer['mes_lis_pay_lin_det_amo_pay_plus_minus'].$footer['mes_lis_pay_lin_det_amo_payable_amount'];
                if (!in_array($footer['mes_lis_pay_lin_det_det_meaning'], $arr) && $i==0) {
                    $arr[]=$footer['mes_lis_pay_lin_det_det_meaning'];
                    $i++;
                    if (!isset($footer_data[$key_footer+1]['mes_lis_pay_lin_det_det_meaning']) || $footer_data[$key_footer+1]['mes_lis_pay_lin_det_det_meaning']!=$footer_data[$key_footer]['mes_lis_pay_lin_det_det_meaning']) {
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(17.5, 4.5, number_format($sub_total), 0, 1, 'R', 0, '', 0);
                        $sub_total = 0;
                        $i=0;
                    }
                } else {
                    if (isset($footer_data[$key_footer+1]['mes_lis_pay_lin_det_det_meaning']) && in_array($footer_data[$key_footer+1]['mes_lis_pay_lin_det_det_meaning'], $arr)) {
                        $i++;
                    } else {
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(17.5, 4.5, number_format($sub_total), 0, 1, 'R', 0, '', 0);
                        $sub_total = 0;
                        $i=0;
                    }
                }
                $x += 17.5;
                $gross_total+=$footer['mes_lis_pay_lin_det_amo_pay_plus_minus'].$footer['mes_lis_pay_lin_det_amo_payable_amount'];
                if (!isset($footer[$key_footer+1]) && ($key_footer+1)==count($footer_data)) {
                    if ($per_page_data==($key_footer)) {
                        $receipt-> AddPage();
                        $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
                        $tplIdx = $receipt->importPage(2);
                        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
                        $x = 169.5;
                        $y = 23.7;
                        $this->page+=1;
                        $receipt->SetXY($this->page_numberX, $this->page_numberY);
                        $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
                    }
                    $y+=4.5;
                    $receipt->SetXY($x, $y);
                    $receipt->Cell(18, 4.5, number_format($gross_total), 0, 1, 'R', 0, '', 0);
                }
                if ($per_page_data==$key_footer) {
                    if (count($footer_data)-($key_footer+1)>0) {
                        $receipt-> AddPage();
                        $receipt->setSourceFile(storage_path(config('const.BLANK_PAYMENT_PDF_PATH')));
                        $tplIdx = $receipt->importPage(3);
                        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
                        $x = 26;
                        $y = 28;
                        $per_page_data+=53;
                        $this->page+=1;
                        $receipt->SetXY($this->page_numberX, $this->page_numberY);
                        $receipt->Cell(20, 4.5, $this->page.'/'.$this->total_page, 0, 1, 'R', 0, '', 0);
                    }
                }
                $x = 26;
            }
        }

        return $receipt;
    }

    public function get_payment_customer_code_list(Request $request)
    {
        $byr_buyer_id=$request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $result = data_payment_pay::select(
            'data_payment_pays.mes_lis_buy_code',
            'data_payment_pays.mes_lis_buy_name',
            'data_payment_pays.mes_lis_pay_pay_code',
            'data_payment_pays.mes_lis_pay_pay_name',
            'dppd.mes_lis_pay_lin_sel_code',
            'dppd.mes_lis_pay_lin_sel_name',
        )
    ->join('data_payments as dp', 'dp.data_payment_id', '=', 'data_payment_pays.data_payment_id')
    ->join('data_payment_pay_details as dppd', 'dppd.data_payment_pay_id', '=', 'data_payment_pays.data_payment_pay_id')
    ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dp.cmn_connect_id')
    ->where('cc.byr_buyer_id', $byr_buyer_id)
    ->where('cc.slr_seller_id', $slr_seller_id)
    ->groupBy('dppd.mes_lis_pay_lin_sel_code')
    ->groupBy('data_payment_pays.mes_lis_pay_pay_code')
    ->get();
        //     $result = DB::select("SELECT
        //     dpp.mes_lis_buy_code,
        //     dpp.mes_lis_buy_name,
        //     dpp.mes_lis_pay_pay_code,
        //     dpp.mes_lis_pay_pay_name,
        //     dppd.mes_lis_pay_lin_sel_code,
        //     dppd.mes_lis_pay_lin_sel_name
        //     FROM
        //    data_payments AS dp
        //    INNER JOIN data_payment_pays AS dpp ON dp.data_payment_id=dpp.data_payment_id
        //    LEFT JOIN data_payment_pay_details AS dppd ON dpp.data_payment_pay_id=dppd.data_payment_pay_id
        //    INNER JOIN cmn_connects AS cc ON dp.cmn_connect_id=cc.cmn_connect_id
        //    WHERE cc.byr_buyer_id=$byr_buyer_id AND cc.slr_seller_id=$slr_seller_id
        //    GROUP BY dppd.mes_lis_pay_lin_sel_code, dpp.mes_lis_pay_pay_code
        //    ");
        return response()->json(['order_customer_code_lists' => $result]);
    }

    public function get_payment_trade_code_list(Request $request)
    {
        $pay_code_list=$request->pay_code_list;
        $byr_buyer_id=$request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $result = data_payment_pay::select(
            'dppd.mes_lis_pay_lin_tra_code',
        // 'dppd.mes_lis_pay_lin_tra_name',
        DB::raw('CASE WHEN dppd.mes_lis_pay_lin_tra_name="" THEN  dppd.mes_lis_pay_lin_tra_name_sbcs ELSE  dppd.mes_lis_pay_lin_tra_name  END as mes_lis_pay_lin_tra_name'),
        )
        ->join('data_payments as dp', 'dp.data_payment_id', '=', 'data_payment_pays.data_payment_id')
        ->join('data_payment_pay_details as dppd', 'dppd.data_payment_pay_id', '=', 'data_payment_pays.data_payment_pay_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'dp.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->where('dp.data_payment_id', $request->payment_id)
        ->whereIn('dppd.mes_lis_pay_lin_det_pay_code', $pay_code_list)
        ->groupBy('dppd.mes_lis_pay_lin_tra_code')
        ->get();
        return response()->json(['order_customer_code_lists' => $result]);
    }
    public function unpaidPaymentPist(Request $request)
    {
        $result = DataController::getUnpaidData($request)
        ->get();
        return response()->json(['unpaid_list' => $result]);
    }
    public function paymentUnpaidDataDownload(Request $request)
    {
        $new_file_name = $this->all_used_fun->downloadFileName($request, 'csv', '未払');
        $download_file_url = Config::get('app.url') . "storage/app" . config('const.PAYMENT_UNPAID_CSV_PATH') . "/" . $new_file_name;
        // get unpaid data query
        $unpaid_query = DataController::getUnpaidData($request);
        $csv_data_count = $unpaid_query->count();
        $unpaid_data = $unpaid_query->get()->toArray();
        // CSV create
        Csv::create(
            config('const.PAYMENT_UNPAID_CSV_PATH') . "/" . $new_file_name,
            $unpaid_data,
            DataController::paymentUnpaidCsvHeading(),
            config('const.CSV_FILE_ENCODE')
        );

        return response()->json(['message' => 'Success', 'status' => 1, 'new_file_name' => $new_file_name, 'url' => $download_file_url, 'csv_data_count' => $csv_data_count]);
    }
}
