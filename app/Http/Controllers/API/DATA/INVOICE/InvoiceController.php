<?php

namespace App\Http\Controllers\API\DATA\INVOICE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Models\ADM\User;
use App\Models\BYR\byr_order_detail;
use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\BYR\byr_buyer;
use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\DATA\INVOICE\InvoiceDataController;
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

class InvoiceController extends Controller
{
    private $request;
    private $all_used_fun;
    public function __construct()
    {
        $this->request = new \Illuminate\Http\Request();
        $this->request->setMethod('POST');
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.INVOICE_SEND_CSV_PATH'));
        $this->all_used_fun->folder_create('app/'.config('const.INVOICE_MOVED_CSV_PATH'));
        $this->all_used_fun->folder_create('app/'.config('const.INVOICE_DOWNLOAD_CSV_PATH'));
        $this->all_used_fun->folder_create('app/'.config('const.INVOICE_COMPARE_CSV_PATH'));
    }
    public function invoiceScheduler($request)
    {
        $cs = new CmnScenarioController();

        $ret = $cs->exec($request);
        return $ret;
        Log::debug($ret->getContent());
        $ret = json_decode($ret->getContent(), true);
        if (1 === $ret['status']) {
            // sceanario exec error
            Log::error($ret['message']);
            return $ret;
        }
        return response()->json($ret);
    }

    public function get_all_invoice_list(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        // return $request->all();
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $per_page = $request->select_field_per_page_num == null ? 10 : $request->select_field_per_page_num;
        $mes_lis_inv_pay_code=$request->mes_lis_inv_pay_code;
        $mes_lis_inv_per_begin_date=$request->mes_lis_inv_per_begin_date;
        $mes_lis_inv_per_end_date=$request->mes_lis_inv_per_end_date;
        $send_datetime_status=$request->send_datetime_status;
        $decission_cnt=$request->decission_cnt;
        $send_cnt=$request->send_cnt;
        // $mes_lis_inv_pay_id=$request->mes_lis_inv_pay_id;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $trade_number = $request->trade_number;

        $mes_lis_inv_per_begin_date = $mes_lis_inv_per_begin_date!=null? date('Y-m-d 00:00:00', strtotime($mes_lis_inv_per_begin_date)):config('const.FROM_DATETIME'); // 受信日時開始
        $mes_lis_inv_per_end_date = $mes_lis_inv_per_end_date!=null? date('Y-m-d 23:59:59', strtotime($mes_lis_inv_per_end_date)):config('const.TO_DATETIME'); // 受信日時終了
        $table_name='data_invoices.';
        if ($sort_by=="data_invoice_id") {
            $table_name='data_invoices.';
        } elseif ($sort_by=="mes_lis_inv_lin_det_amo_requested_amount") {
            $table_name='dipd.';
        } else {
            $table_name='dip.';
        }
        $authUser = User::find($adm_user_id);
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $cmn_company_id = 0;
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = $this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_company_id = $cmn_company_info['cmn_company_id'];
        }
        $result=data_invoice::select(
            'data_invoices.data_invoice_id',
            'dip.mes_lis_inv_per_end_date',
            'dip.mes_lis_inv_pay_code',
            'dip.mes_lis_inv_pay_name',
            'dip.mes_lis_buy_code',
            'dip.mes_lis_inv_pay_id',
            'dip.mes_lis_buy_name',
            'dipd.mes_lis_inv_lin_det_amo_requested_amount',
            DB::raw('sum(dipd.mes_lis_inv_lin_det_amo_requested_amount) as total_amount'),
            DB::raw('COUNT(distinct dipd.data_invoice_pay_detail_id) AS cnt'),
            DB::raw('COUNT( isnull( dipd.decision_datetime) OR NULL) AS decision_cnt'),
            DB::raw('COUNT( isnull( dipd.send_datetime)  OR NULL) AS send_cnt')
        )
        ->join('data_invoice_pays as dip', 'dip.data_invoice_id', '=', 'data_invoices.data_invoice_id')
        ->leftJoin('data_invoice_pay_details as dipd', 'dipd.data_invoice_pay_id', '=', 'dip.data_invoice_pay_id')
        ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_invoices.cmn_connect_id')
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereNull('dipd.deleted_at');
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
        $byr_buyer = $this->all_used_fun->get_company_list($cmn_company_id);
        $partner_codes=cmn_connect::select(DB::raw("CONCAT('0',partner_code) AS partner_code"))
        ->where('byr_buyer_id', $byr_buyer_id)->where('slr_seller_id', $slr_seller_id)->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['invoice_list' => $result, 'byr_buyer_list' => $byr_buyer,'partner_codes'=>$partner_codes]);
    }

    public function invoiceInsert(Request $request)
    {
        $adm_user_id = $request->adm_user_id;
        $byr_buyer_id = $request->byr_buyer_id;
        $authUser = User::find($adm_user_id);
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = $this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }
        $invoice_id = data_invoice::insertGetId([
            'cmn_connect_id'=>$cmn_connect_id,
            'sta_sen_identifier'=>$request->sta_sen_identifier,
            'sta_sen_ide_authority'=>'',
            'sta_rec_identifier'=>$request->sta_rec_identifier,
            'sta_rec_ide_authority'=>'',
            'sta_doc_standard'=>'',
            'sta_doc_type_version'=>'',
            'sta_doc_instance_identifier'=>'',
            'sta_doc_type'=>'',
            'sta_doc_creation_date_and_time'=>'',
            'sta_bus_scope_instance_identifier'=>'',
            'sta_bus_scope_type'=>'',
            'sta_bus_scope_identifier'=>'',
            'mes_ent_unique_creator_identification'=>'',
            'mes_mes_sender_station_address'=>'',
            'mes_mes_ultimate_receiver_station_address'=>'',
            'mes_mes_immediate_receiver_station_addres'=>'',
            'mes_mes_number_of_trading_documents'=>'',
            'mes_mes_sys_key'=>'',
            'mes_mes_sys_value'=>'',
            'mes_lis_con_version'=>'',
            'mes_lis_doc_version'=>'',
            'mes_lis_ext_namespace'=>'',
            'mes_lis_ext_version'=>'',
            'mes_lis_pay_code'=>$request->mes_lis_pay_code,
            'mes_lis_pay_gln'=>$request->mes_lis_pay_gln,
            'mes_lis_pay_name'=>'',
            'mes_lis_pay_name_sbcs'=>'',
        ]);
        $request['data_invoice_id'] = $invoice_id;
        $data_invoice_pay_id = data_invoice_pay::insertGetId([
            'data_invoice_id'=>$invoice_id,
            'mes_lis_inv_pay_code'=>$request->mes_lis_inv_pay_code,
            // 'mes_lis_inv_pay_id'=>$request->mes_lis_inv_pay_id,
            'mes_lis_inv_per_begin_date'=>$request->mes_lis_inv_per_begin_date,
            'mes_lis_inv_per_end_date'=>$request->mes_lis_inv_per_end_date,
            'mes_lis_buy_code'=>$request->mes_lis_buy_code,
            'mes_lis_buy_gln'=>$request->mes_lis_buy_gln,
            'mes_lis_inv_pay_gln'=>$request->mes_lis_inv_pay_gln
            ]);
        $cmn_connect_info=$this->all_used_fun->logInformation($byr_buyer_id);
        Log::info($cmn_connect_info->buyer_name.','.$cmn_connect_info->seller_name.','.$cmn_connect_info->partner_code.',invoice,'.Auth::User()->id.',1');
        return response()->json(['success' => 1]);
    }

    public function update_invoice_detail(Request $request)
    {
        $matches = array();
        $explodeAmountSign = $request->requested_amount;
        preg_match_all("/\d+|[\\+\\-\\/\\*]/", $explodeAmountSign, $matches);
        $countMatch = count($matches[0]);
        if ($countMatch==2) {
            $request_amount = $matches[0][1];
            $request_sign = $matches[0][0];
        } else {
            $request_amount = $matches[0][0];
            $request_sign = '+';
        }

        $updatedArray = array(
            'mes_lis_inv_lin_det_transfer_of_ownership_date'=>$request->mes_lis_inv_lin_det_transfer_of_ownership_date,
            'mes_lis_inv_lin_det_goo_major_category'=>$request->mes_lis_inv_lin_det_goo_major_category,
            'mes_lis_inv_lin_tra_code'=>$request->mes_lis_inv_lin_tra_code,
            'mes_lis_inv_lin_lin_trade_number_reference'=>$request->mes_lis_inv_lin_lin_trade_number_reference,
            'mes_lis_inv_lin_det_pay_code'=>$request->mes_lis_inv_lin_det_pay_code,
            'mes_lis_inv_lin_det_balance_carried_code'=>$request->mes_lis_inv_lin_det_balance_carried_code,
            'mes_lis_inv_lin_det_amo_requested_amount'=>$request_amount,
            'mes_lis_inv_lin_det_amo_requested_amount'=>$request_amount,
            'mes_lis_inv_lin_det_amo_req_plus_minus'=>$request_sign,
            'mes_lis_inv_lin_tra_gln'=>$request->mes_lis_inv_lin_tra_gln,
            'mes_lis_inv_lin_sel_gln'=>$request->mes_lis_inv_lin_sel_gln,
            'mes_lis_inv_lin_sel_code'=>$request->mes_lis_inv_lin_sel_code
        );
        if ($request->data_invoice_pay_detail_id!='') {
            data_invoice_pay_detail::where(['data_invoice_pay_detail_id'=>$request->data_invoice_pay_detail_id])->update($updatedArray);
            return response()->json(['success' => 1,'update_success'=>1]);
        } else {
            $data_invoice_id = data_invoice_pay::where('data_invoice_id', $request->data_invoice_id)->first();
            $updatedArray['data_invoice_pay_id']=$data_invoice_id->data_invoice_pay_id;
            data_invoice_pay_detail::insert($updatedArray);
            return response()->json(['success' => 1,'insert_success'=>1]);
        }
    }
    public function delete_invoice_detail(Request $request)
    {
        data_invoice_pay_detail::where('data_invoice_pay_detail_id', $request->data_invoice_pay_detail_id)->delete();
        return response()->json(['status' => 1,'message'=>'削除しました。']);
    }

    public function execInvoiceSchedular(Request $request)
    {
        // return $request->all();
        $byr_buyer_id=$request->byr_buyer_id;
        $Invoice_command = new InvoiceCommand();
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $message='';
        $status='';
        $class='';
        $data_count=0;
        $cmn_connects=cmn_connect::select('cmn_connect_id')->where('slr_seller_id', $slr_seller_id)->where('byr_buyer_id', $byr_buyer_id)->get();
        foreach ($cmn_connects as $key => $cmn_connect) {
            $executed_data=$Invoice_command->invoiceSchedulerCode(1, $cmn_connect->cmn_connect_id, $byr_buyer_id);
            $message=$executed_data['message'];
            $status=$executed_data['status'];
            $data_count+=$executed_data['data']['total_success_data'];
            $class=$executed_data['data']['class'];
        }
        $ret_data=array(
            'message'=>$message,
            'status'=>$status,
            'data'=>[
                'total_success_data'=>$data_count,
                'class'=>$class,
            ]
        );
        $cmn_connect_info=$this->all_used_fun->logInformation($byr_buyer_id);
        Log::info($cmn_connect_info->buyer_name.','.$cmn_connect_info->seller_name.','.$cmn_connect_info->partner_code.',invoice,'.Auth::User()->id.','.$data_count);
        return $ret_data;
        return response()->json(['message' => "締め処理実行",'status'=>1,'class'=>'success']);
    }

    public function invoiceDetailsList(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_invoice_id=$request->data_invoice_id;
        $per_page = $request->select_field_per_page_num == null ? 10 : $request->select_field_per_page_num;
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
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
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
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->where('di.data_invoice_id', '=', $data_invoice_id);
        // ->where('data_invoices.cmn_connect_id','=',$cmn_connect_id);
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
        $result=$result->whereBetween('data_invoice_pay_details.mes_lis_inv_lin_det_transfer_of_ownership_date', [$from_date,$to_date]);
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
        $result = $result->orderBy($table_name.$sort_by, $sort_type);

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
        $byr_buyer_id=$request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
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
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->where('di.data_invoice_id', '=', $data_invoice_id);

        $result=$result->where('dip.mes_lis_inv_per_end_date', $request->end_date)
            ->where('dip.mes_lis_inv_pay_code', $request->pay_code);
        $result = $result->groupBy('data_invoice_pay_details.mes_lis_inv_lin_tra_code');
        $result = $result->orderBy('data_invoice_pay_details.'.$sort_by, $sort_type);
        $result=$result->get();
        return response()->json(['popUpList' => $result]);
    }

    public function sendInvoiceData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $data_count=$request->data_count;
        $data_invoice_id=$request->data_invoice_id;
        $param_data=$request->param_data;
        $download_file_url='';
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $authUser = User::find($adm_user_id);

        $cmn_connect_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_used_fun->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }
        $csv_data_count = data_invoice::join('data_invoice_pays as dip', 'dip.data_invoice_id', '=', 'data_invoices.data_invoice_id')
            ->join('data_invoice_pay_details as dipd', 'dipd.data_invoice_pay_id', '=', 'dip.data_invoice_pay_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_invoices.cmn_connect_id')
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->where('cc.slr_seller_id', $slr_seller_id)
            ->where('data_invoices.data_invoice_id', $data_invoice_id)
            ->whereNotNull('dipd.decision_datetime')
            ->whereNull('dipd.send_datetime')
            ->where('dip.mes_lis_inv_per_end_date', $param_data['end_date'])
            ->where('dip.mes_lis_inv_pay_code', $param_data['pay_code'])
            ->get()->count();
        if (!$data_count) {
            $request->request->add(['cmn_connect_id' => $cmn_connect_id]);
            $dateTime = date('Y-m-d H:i:s');
            $new_file_name = $this->all_used_fun->sendFileName($request, 'csv', 'invoice');
            data_invoice::where('data_invoice_id', $data_invoice_id)->update(['mes_mes_number_of_trading_documents'=>$csv_data_count]);

            $send_file_path = config('const.INVOICE_SEND_CSV_PATH')."/". $new_file_name;
            $download_file_url = Config::get('app.url')."storage/app".$send_file_path;
            $invoice_query = InvoiceDataController::get_invoice_data($request);
            $invoice_data = $invoice_query['invoice_data'];
            // CSV create
            Csv::create(
                $send_file_path,
                $invoice_data,
                InvoiceDataController::invoiceCsvHeading()
            );
            data_invoice::join('data_invoice_pays as dip', 'dip.data_invoice_id', '=', 'data_invoices.data_invoice_id')
            ->join('data_invoice_pay_details as dipd', 'dipd.data_invoice_pay_id', '=', 'dip.data_invoice_pay_id')
            ->join('cmn_connects as cc', 'cc.cmn_connect_id', '=', 'data_invoices.cmn_connect_id')
            ->where('cc.byr_buyer_id', $byr_buyer_id)
            ->where('cc.slr_seller_id', $slr_seller_id)
            ->where('data_invoices.data_invoice_id', $data_invoice_id)
            ->whereNotNull('dipd.decision_datetime')
            ->whereNull('dipd.send_datetime')
            ->where('dip.mes_lis_inv_per_end_date', $param_data['end_date'])
            ->where('dip.mes_lis_inv_pay_code', $param_data['pay_code'])
            ->update(['dipd.send_datetime'=>$dateTime]);
            $cmn_connect_info=$this->all_used_fun->logInformation($byr_buyer_id);

            Log::info('[INVOICE][sendInvoiceData]:'.$cmn_connect_info->buyer_name.','.$cmn_connect_info->seller_name.',partner_code:'.$cmn_connect_info->partner_code.',user_id:'.Auth::User()->id.',data_count:'.$csv_data_count.',file_path:'.$send_file_path);
        }

        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => 'Success','status'=>1, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
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
            $download_file_url = Config::get('app.url')."storage/app".config('const.INVOICE_DOWNLOAD_CSV_PATH')."/". $new_file_name;
            // get shipment data query
            $invoice_query = InvoiceDataController::get_invoice_data($request);
            $csv_data_count = $invoice_query['total_data'];
            $invoice_data = $invoice_query['invoice_data'];
            // CSV create
            Csv::create(
                config('const.INVOICE_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                $invoice_data,
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
    public function decessionData(Request $request)
    {
        $dateTime = date('Y-m-d H:i:s');
        $date_null = $request->date_null;
        if ($date_null) {
            $dateTime = null;
        } else {
            $dateTime = date('Y-m-d H:i:s');
        }
        // return $dateTime;
        $data_invoice_pay_detail_ids = $request->update_id;
        if ($data_invoice_pay_detail_ids) {
            foreach ($data_invoice_pay_detail_ids as $id) {
                if (!$date_null) {
                    data_invoice_pay_detail::where('data_invoice_pay_detail_id', $id)->update(['decision_datetime' => $dateTime]);
                } else {
                    data_invoice_pay_detail::where('data_invoice_pay_detail_id', $id)
                ->whereNull('send_datetime')
                ->update(['decision_datetime' => $dateTime]);
                }
            }
        }
        return response()->json(['message' => 'success','status'=>1]);
    }
    public function checkdatetimeData(Request $request)
    {
        $rowInfo = $request->item;
        $dateTime = date('Y-m-d H:i:s');
        $action_type = $request->action_type;
        if ($action_type==2) {
            $dateTime = null;
        } else {
            $dateTime = date('Y-m-d H:i:s');
        }
        data_invoice_pay_detail::where('data_shipment_voucher_id', $rowInfo['data_shipment_voucher_id'])->update(['check_datetime' => $dateTime]);
        return response()->json(['message' => 'success','status'=>1]);
    }
    public function amountDataUpdate(Request $request)
    {
        $rowInfo = $request->item;
        $dateTime = date('Y-m-d H:i:s');
        data_invoice_pay_detail::where('data_shipment_voucher_id', $rowInfo['data_shipment_voucher_id'])->update([
            'decision_datetime' => $dateTime,
            'check_datetime' => $dateTime,
            'mes_lis_inv_lin_det_amo_requested_amount'=>$rowInfo['mes_lis_acc_tot_tot_net_price_total'],
            'mes_lis_inv_lin_det_amo_tax'=>$rowInfo['mes_lis_acc_tot_tot_tax_total'],
            ]);
        return response()->json(['message' => 'success','status'=>1]);
    }

    public function invoiceCompareData(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id=$request->byr_buyer_id;
        $shipment_ids=$request->shipment_ids;
        // $shipment_ids= implode(',', array_filter($shipment_ids));
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
        $result = data_shipment_voucher::select(
            'data_shipment_vouchers.data_shipment_voucher_id',
            'data_shipment_vouchers.mes_lis_shi_par_sel_code',
            'data_shipment_vouchers.mes_lis_shi_tra_trade_number',
            'data_shipment_vouchers.mes_lis_shi_par_shi_code',
            'data_shipment_vouchers.mes_lis_shi_par_shi_name',
            'drv.data_receive_voucher_id',
            'drv.mes_lis_acc_tra_dat_transfer_of_ownership_date',
            'drv.mes_lis_acc_tot_tot_tax_total',
            'dipd.decision_datetime',
            'dipd.send_datetime',
            'dipd.check_datetime',
            'dipd.mes_lis_inv_lin_det_amo_requested_amount',
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date IS NULL THEN data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver ELSE data_shipment_vouchers.mes_lis_shi_tra_dat_revised_delivery_date  END as shipment_delivery_date'),
            DB::raw('CASE WHEN data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total IS NULL THEN "0" ELSE data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total  END as mes_lis_shi_tot_tot_net_price_total'),
            DB::raw('CASE WHEN drv.mes_lis_acc_tot_tot_net_price_total IS NULL THEN "0" ELSE drv.mes_lis_acc_tot_tot_net_price_total  END as mes_lis_acc_tot_tot_net_price_total'),
        )
        ->join('data_invoice_pay_details as dipd', 'dipd.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_receive_vouchers as drv', 'drv.mes_lis_acc_tra_trade_number', '=', 'data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'drv.data_receive_id')
        ->join('cmn_connects as cc', function ($join) {
            $join->on('cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
            ->on('cc.cmn_connect_id', '=', 'dr.cmn_connect_id');
        })
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereIn('data_shipment_vouchers.data_shipment_voucher_id', $shipment_ids)
        ->whereRaw('data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total != drv.mes_lis_acc_tot_tot_net_price_total')
        ->orderBy('shipment_delivery_date')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['voucherList'=>$result]);
    }
    public function invoiceCompareDataDownload(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $byr_buyer_id=$request->byr_buyer_id;
        $shipment_ids=$request->shipment_ids;
        // $shipment_ids= implode(',', array_filter($shipment_ids));
        $slr_seller_id = Auth::User()->SlrInfo->slr_seller_id;
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
        ->join('data_invoice_pay_details as dipd', 'dipd.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
        ->join('data_receive_vouchers as drv', 'drv.mes_lis_acc_tra_trade_number', '=', 'data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->join('data_receives as dr', 'dr.data_receive_id', '=', 'drv.data_receive_id')
        ->join('data_shipment_items as dsi', 'dsi.data_shipment_voucher_id', '=', 'data_shipment_vouchers.data_shipment_voucher_id')
        ->join('data_receive_items as dri', function ($join) {
            $join->on('dri.mes_lis_acc_lin_lin_line_number', '=', 'dsi.mes_lis_shi_lin_lin_line_number')
            ->on('dri.data_receive_voucher_id', '=', 'drv.data_receive_voucher_id');
        })
        ->join('cmn_connects as cc', function ($join) {
            $join->on('cc.cmn_connect_id', '=', 'ds.cmn_connect_id')
            ->on('cc.cmn_connect_id', '=', 'dr.cmn_connect_id');
        })
        ->where('cc.byr_buyer_id', $byr_buyer_id)
        ->where('cc.slr_seller_id', $slr_seller_id)
        ->whereIn('data_shipment_vouchers.data_shipment_voucher_id', $shipment_ids)
        ->where('data_shipment_vouchers.mes_lis_shi_tot_tot_net_price_total', '!=', 'drv.mes_lis_acc_tot_tot_net_price_total')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_par_sel_code')
        ->orderBy('data_shipment_vouchers.mes_lis_shi_tra_trade_number')
        ->orderBy('dsi.mes_lis_shi_lin_lin_line_number')
        ->get()->toArray();
        $new_file_name = $this->all_used_fun->downloadFileName($request, 'csv', '出荷受領比較');
        $download_file_url = Config::get('app.url')."storage/app".config('const.INVOICE_COMPARE_CSV_PATH')."/". $new_file_name;
        // CSV create
        Csv::create(
            config('const.INVOICE_COMPARE_CSV_PATH')."/". $new_file_name,
            $result,
            InvoiceDataController::invoiceCompareCsvHeading(),
            config('const.CSV_FILE_ENCODE')
        );

        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url]);
    }
    public function invoiceCompareItem(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $data_shipment_voucher_id=$request->data_shipment_voucher_id;
        $data_receive_voucher_id=$request->data_receive_voucher_id;
        $result = data_shipment_item::select(
            'data_shipment_items.mes_lis_shi_lin_lin_line_number',
            'data_shipment_items.mes_lis_shi_lin_ite_order_item_code',
            'data_shipment_items.mes_lis_shi_lin_ite_name',
            'data_shipment_items.mes_lis_shi_lin_qua_shi_quantity',
            'dri.mes_lis_acc_lin_qua_rec_quantity',
            DB::raw('CASE WHEN data_shipment_items.mes_lis_shi_lin_amo_item_net_price IS NULL THEN "0" ELSE data_shipment_items.mes_lis_shi_lin_amo_item_net_price  END as mes_lis_shi_lin_amo_item_net_price'),
            DB::raw('CASE WHEN dri.mes_lis_acc_lin_amo_item_net_price IS NULL THEN "0" ELSE dri.mes_lis_acc_lin_amo_item_net_price  END as mes_lis_acc_lin_amo_item_net_price'),
        )
        ->join('data_receive_items as dri', 'dri.mes_lis_acc_lin_lin_line_number', '=', 'data_shipment_items.mes_lis_shi_lin_lin_line_number')
        ->where('data_shipment_items.data_shipment_voucher_id', $data_shipment_voucher_id)
        ->where('dri.data_receive_voucher_id', $data_receive_voucher_id)
        ->orderBy('data_shipment_items.mes_lis_shi_lin_lin_line_number')
        ->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['compareItemList'=>$result]);
    }

    public function get_invoice_customer_code_list(Request $request)
    {
        $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id, $request->byr_buyer_id);

        $result = data_invoice_pay::select(
            "data_invoice_pays.mes_lis_buy_code",
            'data_invoice_pays.mes_lis_buy_name',
            'data_invoice_pays.mes_lis_inv_pay_code',
            'data_invoice_pays.mes_lis_inv_pay_name'
        )
        ->join('data_invoices as di', 'di.data_invoice_id', 'data_invoice_pays.data_invoice_id')
        ->where('di.cmn_connect_id', $cmn_connect_id)
        ->groupBy('data_invoice_pays.mes_lis_buy_code')
        ->groupBy('data_invoice_pays.mes_lis_inv_pay_code')
        ->get();
        return response()->json(['order_customer_code_lists' => $result]);
    }
}
