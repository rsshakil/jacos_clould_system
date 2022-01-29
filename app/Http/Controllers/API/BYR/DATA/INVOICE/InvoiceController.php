<?php

namespace App\Http\Controllers\API\BYR\DATA\INVOICE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Models\ADM\User;
use App\Models\BYR\byr_order_detail;
use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\BYR\byr_buyer;
use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\BYR\DATA\INVOICE\InvoiceDataController;
use App\Exports\InvoiceCSVExport;
use App\Traits\Csv;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Auth;
use App\Console\Commands\InvoiceCommand;
use App\Models\CMN\cmn_connect;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;

class InvoiceController extends Controller
{
    private $request;
    private $all_used_fun;
    public function __construct()
    {
        $this->request = new \Illuminate\Http\Request();
        $this->request->setMethod('POST');
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.SLR_INVOICE_DOWNLOAD_CSV_PATH'));
        $this->all_used_fun->folder_create('app/'.config('const.SLR_INVOICE_COMPARE_CSV_PATH'));
    }

    public function get_all_invoice_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;
        $mes_lis_inv_pay_code=$request->mes_lis_inv_pay_code;
        $mes_lis_inv_per_begin_date=$request->mes_lis_inv_per_begin_date;
        $mes_lis_inv_per_end_date=$request->mes_lis_inv_per_end_date;
        $send_datetime_status=$request->send_datetime_status;
        $decission_cnt=$request->decission_cnt;
        $send_cnt=$request->send_cnt;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $trade_number = $request->trade_number;

        $mes_lis_inv_per_begin_date = $mes_lis_inv_per_begin_date!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_inv_per_begin_date)):config('const.FROM_DATETIME'); // 受信日時開始
        $mes_lis_inv_per_end_date = $mes_lis_inv_per_end_date!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_inv_per_end_date)):config('const.TO_DATETIME'); // 受信日時終了
        $table_name='data_invoices.';
        if ($sort_by=="data_invoice_id" || $sort_by=="deleted_at") {
            $table_name='data_invoices.';
        } elseif ($sort_by=="mes_lis_inv_lin_det_amo_requested_amount") {
            $table_name='dipd.';
        } else {
            $table_name='dip.';
        }
        $result=data_invoice::select(
            'data_invoices.data_invoice_id',
            'data_invoices.deleted_at',
            'dip.mes_lis_inv_per_end_date',
            'dip.mes_lis_inv_pay_code',
            'dip.mes_lis_inv_pay_name',
            'dip.mes_lis_buy_code',
            'dip.mes_lis_inv_pay_id',
            'dip.mes_lis_buy_name',
            'dipd.mes_lis_inv_lin_det_amo_requested_amount',
            DB::raw('sum(dipd.mes_lis_inv_lin_det_amo_requested_amount) as total_amount'),
            DB::raw('COUNT(distinct dipd.data_invoice_pay_detail_id) AS cnt'),
            // DB::raw('COUNT(distinct dipd.decision_datetime) AS decision_cnt'),
            // DB::raw('COUNT(distinct dipd.send_datetime) AS send_cnt'),
            DB::raw('COUNT( isnull( dipd.decision_datetime) OR NULL) AS decision_cnt'),
            DB::raw('COUNT( isnull( dipd.send_datetime)  OR NULL) AS send_cnt')
        )
        ->join('data_invoice_pays as dip', 'dip.data_invoice_id', '=', 'data_invoices.data_invoice_id')
        ->leftJoin('data_invoice_pay_details as dipd', 'dipd.data_invoice_pay_id', '=', 'dip.data_invoice_pay_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_invoices.cmn_connect_id')
        ->withTrashed()
        ->where('cc.byr_buyer_id', $byr_buyer_id);
        if ($trade_number!=null) {
            $result=$result->where('dipd.mes_lis_inv_lin_lin_trade_number_reference', '=', $trade_number);
        }
        if ($mes_lis_inv_pay_code!=null) {
            $result=$result->where('dip.mes_lis_inv_pay_code', '=', $mes_lis_inv_pay_code);
        }
        $result=$result->whereBetween('dip.mes_lis_inv_per_end_date', [$mes_lis_inv_per_begin_date,$mes_lis_inv_per_end_date]);

        if ($send_cnt == "!0") {
            $result= $result->having('send_cnt', '!=', '0');
        } elseif ($send_cnt != "*") {
            $result= $result->having('send_cnt', '=', $send_cnt);
        }
        if ($decission_cnt == "!0") {
            $result= $result->having('decision_cnt', '!=', '0');
        } elseif ($decission_cnt != "*") {
            $result= $result->having('decision_cnt', '=', $decission_cnt);
        }
        $result=$result->groupBy('data_invoices.data_invoice_id')
        ->orderBy($table_name.$sort_by, $sort_type)
        ->paginate($per_page);
        Log::debug(__METHOD__.':end---');
        return response()->json(['invoice_list' => $result]);
    }

    public function invoiceDetailsList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $data_invoice_id=$request->data_invoice_id;
        $per_page = $request->per_page == null ? 10 : $request->per_page;


        $number_reference=$request->mes_lis_inv_lin_lin_trade_number_reference;
        $decision_datetime_status=$request->decision_datetime_status;
        $send_datetime_status=$request->send_datetime_status;
        $payment_datetime_status=$request->payment_datetime_status;
        $mes_lis_inv_lin_tra_code=$request->mes_lis_inv_lin_tra_code;
        $category_code = $request->category_code;
        $category_code =$category_code['category_code'];
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $from_date = $from_date!=null? date('Y-m-d 00:00:00', strtotime($from_date)):config('const.FROM_DATETIME'); // 受信日時開始
        $to_date = $to_date!=null? date('Y-m-d 23:59:59', strtotime($to_date)):config('const.TO_DATETIME'); // 受信日時終了
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $param_data = $request->param_data;
        $byr_buyer_id=$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_invoice_pay_detail::select(
            'di.data_invoice_id',
            'data_invoice_pay_details.data_invoice_pay_detail_id',
            'dip.mes_lis_inv_per_end_date',
            'data_invoice_pay_details.data_shipment_voucher_id',
            'data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_code',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_name',
            'data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference',
            'data_invoice_pay_details.mes_lis_inv_lin_det_amo_requested_amount',
            'data_invoice_pay_details.mes_lis_inv_lin_det_amo_req_plus_minus',
            'data_invoice_pay_details.mes_lis_inv_lin_det_pay_code',
            'data_invoice_pay_details.mes_lis_inv_lin_det_balance_carried_code',
            'data_invoice_pay_details.send_datetime',
            'data_invoice_pay_details.decision_datetime',
            'data_invoice_pay_details.mes_lis_inv_lin_det_goo_major_category',
            'data_invoice_pay_details.deleted_at',
            'dppd.mes_lis_pay_lin_det_pay_out_date'
        )
        ->join('data_invoice_pays as dip', 'dip.data_invoice_pay_id', '=', 'data_invoice_pay_details.data_invoice_pay_id')
        ->join('data_invoices as di', 'di.data_invoice_id', '=', 'dip.data_invoice_id')
        ->leftJoin('data_payment_pays as dpp', function ($join) {
            $join->on('dpp.mes_lis_pay_pay_code', '=', 'dip.mes_lis_inv_pay_code');
            $join->on('dpp.mes_lis_pay_per_end_date', '=', 'dip.mes_lis_inv_per_end_date');
            $join->on('dpp.mes_lis_buy_code', '=', 'dip.mes_lis_buy_code');
        })
        ->leftJoin('data_payment_pay_details as dppd', function ($join) {
            $join->on('dppd.data_payment_pay_id', '=', 'dpp.data_payment_pay_id');
            $join->on('data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference', '=', 'dppd.mes_lis_pay_lin_lin_trade_number_reference');
            $join->on('data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date', '=', 'dppd.mes_lis_pay_lin_det_transfer_of_ownership_date');
        })
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'di.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('di.data_invoice_id', '=', $data_invoice_id)
        ->whereBetween('data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date', [$from_date,$to_date]);
        if ($decision_datetime_status=='未確定あり') {
            $result=$result->whereNull('data_invoice_pay_details.decision_datetime');
        } elseif ($decision_datetime_status=='確定済') {
            $result=$result->whereNotNull('data_invoice_pay_details.decision_datetime');
        }
        if ($send_datetime_status=='未送信あり') {
            $result=$result->whereNull('data_invoice_pay_details.send_datetime');
        } elseif ($send_datetime_status=='送信済') {
            $result=$result->whereNotNull('data_invoice_pay_details.send_datetime');
        }
        if ($payment_datetime_status=='支払い済み') {
            $result=$result->whereNotNull('dppd.mes_lis_pay_lin_det_pay_out_date');
        } elseif ($payment_datetime_status=='未払い') {
            $result=$result->whereNull('dppd.mes_lis_pay_lin_det_pay_out_date');
        }
        if ($number_reference!=null) {
            $result=$result->where('data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference', '=', $number_reference);
        }

        if ($category_code!='*') {
            $result=$result->where('data_invoice_pay_details.mes_lis_inv_lin_det_goo_major_category', '=', $category_code);
        }

        if ($mes_lis_inv_lin_tra_code!='') {
            $result=$result->where('data_invoice_pay_details.mes_lis_inv_lin_tra_code', '=', $mes_lis_inv_lin_tra_code);
        }
        $result=$result->where('dip.mes_lis_inv_per_end_date', $param_data['end_date'])
            ->where('dip.mes_lis_inv_pay_code', $param_data['pay_code']);
        $table_name='data_invoice_pay_details.';
        if ($sort_by=='mes_lis_pay_lin_det_pay_out_date') {
            $table_name='dppd.';
        }
        $result = $result->orderBy($table_name.$sort_by, $sort_type)
        ->withTrashed();

        $all_invoice_details_list=$result->get();
        $invoice_details_list=$result->paginate($per_page);
        $shipment_ids=array();
        foreach ($all_invoice_details_list as $key => $value) {
            $shipment_ids[]=$value->data_shipment_voucher_id;
        }

        Log::debug(__METHOD__.':end---');
        return response()->json(['invoice_details_list' => $invoice_details_list,'shipment_ids'=>$shipment_ids]);
    }
    public function get_voucher_detail_popup2_invoice(Request $request)
    {
        $data_invoice_id=$request->data_invoice_id;
        $sort_by = 'data_invoice_pay_detail_id';
        $sort_type = 'ASC';
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result=data_invoice_pay_detail::select(
            'di.data_invoice_id',
            'data_invoice_pay_details.data_invoice_pay_detail_id',
            'dip.mes_lis_inv_per_end_date',
           // 'dipd.data_shipment_voucher_id',
            'data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_code',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_name',
            'data_invoice_pay_details.mes_lis_inv_lin_tra_name_sbcs',
            'data_invoice_pay_details.mes_lis_inv_lin_lin_trade_number_reference',
            'data_invoice_pay_details.mes_lis_inv_lin_det_amo_requested_amount',
            'data_invoice_pay_details.mes_lis_inv_lin_det_pay_code',
            'data_invoice_pay_details.mes_lis_inv_lin_det_balance_carried_code',
            'data_invoice_pay_details.send_datetime',
            'data_invoice_pay_details.decision_datetime',
            'data_invoice_pay_details.mes_lis_inv_lin_det_goo_major_category'
        )
        ->join('data_invoice_pays as dip', 'dip.data_invoice_pay_id', '=', 'data_invoice_pay_details.data_invoice_pay_id')
        ->join('data_invoices as di', 'di.data_invoice_id', '=', 'dip.data_invoice_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'di.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('di.data_invoice_id', '=', $data_invoice_id);

        $result=$result->where('dip.mes_lis_inv_per_end_date', $request->end_date)
            ->where('dip.mes_lis_inv_pay_code', $request->pay_code);
        $result = $result->groupBy('data_invoice_pay_details.mes_lis_inv_lin_tra_code');
        $result = $result->orderBy('data_invoice_pay_details.'.$sort_by, $sort_type);
        $result=$result->withTrashed()->get();
        return response()->json(['popUpList' => $result]);
    }

    public function downloadInvoice(Request $request)
    {
        // downloadType=1 for Csv
        // downloadType=2 for Fixed length
        // return $request->all();
        $data_invoice_id=$request->data_invoice_id?$request->data_invoice_id:1;
        $downloadType=$request->downloadType;
        $csv_data_count =0;
        if ($downloadType==1) {
            // CSV Download
            $new_file_name = $this->all_used_fun->downloadFileName($request, 'csv', '請求');
            $download_file_url = Config::get('app.url')."storage/app".config('const.SLR_INVOICE_DOWNLOAD_CSV_PATH')."/". $new_file_name;

            // get shipment data query
            $invoice_query = InvoiceDataController::get_invoice_data($request);
            $csv_data_count = $invoice_query->count();
            $shipment_data = $invoice_query->get()->toArray();

            // CSV create
            Csv::create(
                config('const.SLR_INVOICE_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                $shipment_data,
                InvoiceDataController::invoiceCsvHeading(),
                config('const.CSV_FILE_ENCODE')
            );
        } elseif ($downloadType==2) {
            // $request->request->add(['scenario_id' => 6]);
            // $request->request->add(['data_order_id' => 1]);
            // $new_file_name = self::invoiceFileName($data_order_id, 'txt');
            // $download_file_url = \Config::get('app.url')."storage/".config('const.JCA_FILE_PATH')."/". $new_file_name;
            // $request->request->add(['file_name' => $new_file_name]);
            // $cs = new CmnScenarioController();
            // $ret = $cs->exec($request);
        }

        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    public function get_all_invoice_by_voucher_number($voucher_number)
    {
        $result = byr_order_detail::select('byr_order_details.*', 'byr_shipment_details.*')
            ->join('byr_shipment_details', 'byr_shipment_details.byr_order_detail_id', '=', 'byr_order_details.byr_order_detail_id')
            ->where('voucher_number', $voucher_number)->get();
        $shop_list = array();
        $voucher_list = array();
        $byr_buyer = array();
        return response()->json(['invoice_list' => $result, 'byr_buyer_list' => $byr_buyer, 'shop_list' => $shop_list, 'voucher_list' => $voucher_list]);
    }

    public function invoiceCompareData(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $shipment_ids=$request->shipment_ids;
        $result = data_shipment_voucher::select('data_shipment_vouchers.data_shipment_voucher_id',
        'data_shipment_vouchers.mes_lis_shi_par_sel_code',
        'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
        'data_shipment_vouchers.mes_lis_shi_par_shi_code',
        'data_shipment_vouchers.mes_lis_shi_par_shi_name',
        DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date IS NULL THEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date  END as shipment_delivery_date'),
        DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total IS NULL THEN "0" ELSE data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total  END as mes_lis_shi_tot_tot_net_price_total'),
        'drv.data_receive_voucher_id',
        'drv.mes_lis_acc_tra_dat_transfer_of_ownership_date',
        'drv.mes_lis_acc_tot_tot_tax_total',
        DB::raw('CASE WHEN drv.mes_lis_acc_tot_tot_net_price_total IS NULL THEN "0" ELSE drv.mes_lis_acc_tot_tot_net_price_total  END as mes_lis_acc_tot_tot_net_price_total'),
        'dipd.decision_datetime',
        'dipd.send_datetime',
        'dipd.check_datetime',
        'dipd.mes_lis_inv_lin_det_amo_requested_amount',
        )
        ->join('data_invoice_pay_details as dipd','dipd.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
        ->join('data_receive_vouchers as drv','drv.mes_lis_acc_tra_trade_number','=','data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->join('data_receives as dr','dr.data_receive_id','=','drv.data_receive_id')
        ->join('cmn_connects as cc', function($join){
            $join->on('cc.cmn_connect_id','=', 'ds.cmn_connect_id')
            ->on('cc.cmn_connect_id','=','dr.cmn_connect_id');
        })
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->whereIn('data_shipment_vouchers.data_shipment_voucher_id', $shipment_ids)
        ->where('data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total','!=','drv.mes_lis_acc_tot_tot_net_price_total')
        ->withTrashed()
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['voucherList'=>$result]);
    }
    public function invoiceCompareDataDownload(Request $request)
    {
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $shipment_ids=$request->shipment_ids;
        $result = data_shipment_voucher::select(
            'data_shipment_vouchers.mes_lis_shi_par_sel_code',
            'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
            'data_shipment_vouchers.mes_lis_shi_par_shi_code',
            'data_shipment_vouchers.mes_lis_shi_par_shi_name',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date IS NULL THEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date  END as shipment_delivery_date'),
            'drv.mes_lis_acc_tra_dat_transfer_of_ownership_date',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total IS NULL THEN "0" ELSE data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total  END as mes_lis_shi_tot_tot_net_price_total'),
            DB::raw('CASE WHEN drv.mes_lis_acc_tot_tot_net_price_total IS NULL THEN "0" ELSE drv.mes_lis_acc_tot_tot_net_price_total  END as mes_lis_acc_tot_tot_net_price_total'),
            'dsi.mes_lis_shi_lin_lin_line_number',
            'dsi.mes_lis_shi_lin_ite_order_item_code',
            'dsi.mes_lis_shi_lin_ite_name',
            'dsi.mes_lis_shi_lin_qua_shi_quantity',
            'dri.mes_lis_acc_lin_qua_rec_quantity',
            'dsi.mes_lis_shi_lin_amo_item_net_price',
            'dri.mes_lis_acc_lin_amo_item_net_price',
            'dipd.mes_lis_inv_lin_det_amo_requested_amount',
            'dipd.check_datetime'
            )
            ->join('data_invoice_pay_details as dipd','dipd.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
            ->join('data_shipments as ds','ds.data_shipment_id','=','data_shipment_vouchers.data_shipment_id')
            ->join('data_receive_vouchers as drv','drv.mes_lis_acc_tra_trade_number','=','data_shipment_vouchers.mes_lis_shi_tra_trade_number')
            ->join('data_receives as dr','dr.data_receive_id','=','drv.data_receive_id')
            ->join('data_shipment_items as dsi','dsi.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
            ->join('data_receive_items as dri', function($join){
                $join->on('dri.mes_lis_acc_lin_lin_line_number','=', 'dsi.mes_lis_shi_lin_lin_line_number')
                ->on('dri.data_receive_voucher_id','=','drv.data_receive_voucher_id');
            })
            ->join('cmn_connects as cc', function($join){
                $join->on('cc.cmn_connect_id','=', 'ds.cmn_connect_id')
                ->on('cc.cmn_connect_id','=','dr.cmn_connect_id');
            })
            ->where('cc.byr_buyer_id',$byr_buyer_id)
            ->whereIn('data_shipment_vouchers.data_shipment_voucher_id', $shipment_ids)
            ->where('data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total','!=','drv.mes_lis_acc_tot_tot_net_price_total')
            ->orderBy('data_shipment_vouchers.mes_lis_shi_par_sel_code')
            ->orderBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')
            ->orderBy('dsi.mes_lis_shi_lin_lin_line_number')
            ->withTrashed()
            ->get()->toArray();
        $new_file_name = $this->all_used_fun->downloadFileName($request, 'csv', '請求');
        $download_file_url = Config::get('app.url')."storage/app".config('const.SLR_INVOICE_COMPARE_CSV_PATH')."/". $new_file_name;

        // CSV create
        Csv::create(
            config('const.SLR_INVOICE_COMPARE_CSV_PATH')."/". $new_file_name,
            $result,
            InvoiceDataController::invoiceCompareCsvHeading(),
            config('const.CSV_FILE_ENCODE')
        );
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url]);
    }
    public function invoiceCompareItem(Request $request)
    {
        $data_shipment_voucher_id=$request->data_shipment_voucher_id;
        $data_receive_voucher_id=$request->data_receive_voucher_id;
        $result = data_shipment_item::select('data_shipment_items.mes_lis_shi_lin_lin_line_number',
        'data_shipment_items.mes_lis_shi_lin_ite_order_item_code',
        'data_shipment_items.mes_lis_shi_lin_ite_name',
        'data_shipment_items.mes_lis_shi_lin_qua_shi_quantity',
        'dri.mes_lis_acc_lin_qua_rec_quantity',
        DB::raw('CASE WHEN data_shipment_items.mes_lis_shi_lin_amo_item_net_price IS NULL THEN "0" ELSE data_shipment_items.mes_lis_shi_lin_amo_item_net_price  END as mes_lis_shi_lin_amo_item_net_price'),
        DB::raw('CASE WHEN dri.mes_lis_acc_lin_amo_item_net_price IS NULL THEN "0" ELSE dri.mes_lis_acc_lin_amo_item_net_price  END as mes_lis_acc_lin_amo_item_net_price'),
        )
        ->join('data_receive_items as dri','dri.mes_lis_acc_lin_lin_line_number','=','data_shipment_items.mes_lis_shi_lin_lin_line_number')
        ->where('data_shipment_items.data_shipment_voucher_id',$data_shipment_voucher_id)
        ->where('dri.data_receive_voucher_id',$data_receive_voucher_id)
        ->orderBy('data_shipment_items.mes_lis_shi_lin_lin_line_number')
        ->withTrashed()
        ->get();
        return response()->json(['compareItemList'=>$result]);
    }

    public function getInvoiceCustomerCodeList(Request $request)
    {
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id :Auth::User()->ByrInfo->byr_buyer_id;
        $result = data_invoice_pay::select("data_invoice_pays.mes_lis_buy_code",
        'data_invoice_pays.mes_lis_buy_name','data_invoice_pays.mes_lis_inv_pay_code',
        'data_invoice_pays.mes_lis_inv_pay_name')
        ->join('data_invoices as di','di.data_invoice_id','data_invoice_pays.data_invoice_id')
        ->join('cmn_connects as cc','cc.cmn_connect_id','di.cmn_connect_id')
        ->where('cc.byr_buyer_id',$byr_buyer_id)
        ->groupBy('data_invoice_pays.mes_lis_buy_code')
        ->groupBy('data_invoice_pays.mes_lis_inv_pay_code')
        ->withTrashed()
        ->get();
        return response()->json(['order_customer_code_lists' => $result]);
    }
    public function invoiceDeleteRetrive(Request $request){
        $data_invoice_id=$request->data_invoice_id;
        $deleted_at=$request->deleted_at;
        $invoice_var=data_invoice::where('data_invoice_id',$data_invoice_id);
        if ($deleted_at===null) {
            $invoice_var->delete();
        }else{
            $invoice_var->withTrashed()->restore();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'data_count' => $invoice_var->withTrashed()->count()]);
    }
    public function invoiceDetailsDeleteRetrive(Request $request){
        $deleted_at=$request->deleted_at;
        $invoice_pay_details_var=data_invoice_pay_detail::where('data_invoice_pay_detail_id', $request->data_invoice_pay_detail_id);
        if ($deleted_at===null) {
            $invoice_pay_details_var->delete();
        }else{
            $invoice_pay_details_var->withTrashed()->restore();
        }
        return response()->json(['status' => 1, 'message' => 'Success', 'data_count' => $invoice_pay_details_var->withTrashed()->count()]);
    }
}
