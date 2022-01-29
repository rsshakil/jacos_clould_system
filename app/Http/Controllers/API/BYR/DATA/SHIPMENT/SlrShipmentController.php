<?php

namespace App\Http\Controllers\API\BYR\DATA\SHIPMENT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Http\Controllers\API\BYR\DATA\SHIPMENT\DataController;
use App\Traits\Csv;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Http\Controllers\API\BYR\DATA\DFLT\DefaultFunctions;


class SlrShipmentController extends Controller
{
    private $default_functions;
    private $all_functions;
    public function __construct()
    {
        $this->default_functions = new DefaultFunctions();
        $this->all_functions = new AllUsedFunction();
    }
    public function slrShipmentDownload(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        //ownloadType=2 for Fixed length
        $data_order_id=$request->data_order_id?$request->data_order_id:1;
        $downloadType=$request->downloadType;
        $csv_data_count =0;
        if ($downloadType==1) {
            // CSV Download
            $new_file_name = $this->default_functions->downloadFileName($request, 'csv', '受注');
            $download_file_url = Config::get('app.url')."storage/app".config('const.SLR_SHIPMENT_DOWNLOAD_CSV_PATH')."/". $new_file_name;

            // get shipment data query
            $shipment_query = DataController::get_shipment_data($request);
            $csv_data_count = $shipment_query->count();
            $shipment_data = $shipment_query->get()->toArray();
            // CSV create
            Csv::create(
                config('const.SLR_SHIPMENT_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                $shipment_data,
                DataController::shipmentCsvHeading(),
                config('const.CSV_FILE_ENCODE')
            );
        } elseif ($downloadType==2) {
            // JCA Download
            // $request = new \Illuminate\Http\Request();
            // $request->setMethod('POST');
            // $request=$this->request;
            $request->request->add(['scenario_id' => 17]);
            $request->request->add(['data_order_id' => $data_order_id]);
            $new_file_name =$this->default_functions->downloadFileName($request, 'txt', '受注');
            $download_file_url = Config::get('app.url')."storage/".config('const.SLR_JCA_FILE_PATH'). $new_file_name;
            $request->request->add(['file_name' => $new_file_name]);
            // $request->request->remove('downloadType');
            // return $request->all();
            $cs = new CmnScenarioController();
            $ret = $cs->exec($request);
        }
        Log::debug(__METHOD__.':end---');
// return "OK";
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$new_file_name, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    public function getShipmentItemSearch(Request $request)
    {
        // return $request->all();
        // $data_order_id = $request->data_order_id;
        $order_info = $request->order_info;
        $mes_lis_shi_lin_ite_order_item_code = $request->mes_lis_shi_lin_ite_order_item_code;
        $mes_lis_shi_lin_ite_gtin = $request->mes_lis_shi_lin_ite_gtin;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $per_page = $request->per_page == null ? 10 : $request->per_page;

        $data_order_id = $order_info['data_order_id'];
        $mes_lis_shi_log_del_delivery_service_code = $order_info['mes_lis_shi_log_del_delivery_service_code'];
        $mes_lis_shi_par_sel_code = $order_info['mes_lis_shi_par_sel_code'];
        $mes_lis_shi_par_sel_name = $order_info['mes_lis_shi_par_sel_name'];
        $mes_lis_shi_tra_dat_delivery_date_to_receiver = $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'];
        $mes_lis_shi_tra_goo_major_category = $order_info['mes_lis_shi_tra_goo_major_category'];
        $mes_lis_shi_tra_ins_temperature_code = $order_info['mes_lis_shi_tra_ins_temperature_code'];
        $mes_lis_shi_tra_trade_number = $order_info['mes_lis_shi_tra_trade_number'];
        $receive_datetime = $order_info['receive_datetime'];

        $result = DB::table('data_shipment_items as dsi')
            ->select(
                'dsi.mes_lis_shi_lin_ite_order_item_code',
                'dsi.mes_lis_shi_lin_ite_gtin',
                'dsi.mes_lis_shi_lin_ite_name',
                'dsi.mes_lis_shi_lin_ite_ite_spec',
                'dsi.mes_lis_shi_lin_fre_field_name'
            )
            ->leftJoin('data_shipment_vouchers as dsv', 'dsv.data_shipment_voucher_id', '=', 'dsi.data_shipment_voucher_id')
            ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'dsv.data_shipment_id')
            ->where('ds.data_order_id', $data_order_id)
            ->where('dsv.mes_lis_shi_log_del_delivery_service_code', $mes_lis_shi_log_del_delivery_service_code)
             ->where('dsv.mes_lis_shi_par_sel_code', $mes_lis_shi_par_sel_code)
            ->where('dsv.mes_lis_shi_par_sel_name', $mes_lis_shi_par_sel_name)
            ->where('dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver', $mes_lis_shi_tra_dat_delivery_date_to_receiver)
            ->where('dsv.mes_lis_shi_tra_goo_major_category', $mes_lis_shi_tra_goo_major_category)
            ->where('dsv.mes_lis_shi_tra_ins_temperature_code', $mes_lis_shi_tra_ins_temperature_code);
        // ->where('dsv.mes_lis_shi_tra_trade_number', $mes_lis_shi_tra_trade_number);
        // ->where('ds.receive_datetime', $receive_datetime);

        if ($mes_lis_shi_lin_ite_gtin) {
            $result=$result->where('dsi.mes_lis_shi_lin_ite_gtin', $mes_lis_shi_lin_ite_gtin);
        }
        if ($mes_lis_shi_lin_ite_order_item_code) {
            $result=$result->where('dsi.mes_lis_shi_lin_ite_order_item_code', $mes_lis_shi_lin_ite_order_item_code);
        }
        $result=$result->whereNull('dsv.decision_datetime');

        // $result=$result->groupBy('dsv.mes_lis_shi_tra_trade_number');
        $result=$result->groupBy('dsi.mes_lis_shi_lin_ite_order_item_code');
        $result=$result->orderBy('dsi.'.$sort_by, $sort_type)
        ->paginate($per_page);


        return response()->json(['order_item_lists' => $result]);
    }
    public function update_shipment_item_details(Request $request)
    {
        //return $request->all();
        $items = $request->items;
        // print_r($items[0]['data_shipment_voucher_id']);exit;
        // echo $items[0]->data_shipment_voucher_id;exit;
        $updated_date = $request->updated_date;
        $total_selling_price = $request->total_selling_price;
        $total_cost_price = $request->total_cost_price;
        $status = $request->order_status;
        $mes_lis_shi_tot_tot_net_price_total_sum=0;
        $mes_lis_shi_tot_tot_selling_price_total_sum=0;
        $mes_lis_shi_tot_tot_tax_total_sum=0;
        $mes_lis_shi_tot_tot_item_total_sum=0;
        $mes_lis_shi_tot_tot_unit_total_sum=0;

        foreach ($items as $item) {
            $netprice = $item['mes_lis_shi_lin_qua_shi_quantity']*$item['mes_lis_shi_lin_amo_item_net_price_unit_price'];
            $sellingPrice = $item['mes_lis_shi_lin_qua_shi_quantity']*$item['mes_lis_shi_lin_amo_item_selling_price_unit_price'];
            $tax = ($netprice*$item['mes_lis_shi_tra_tax_tax_rate'])/100;
            $mes_lis_shi_tot_tot_net_price_total_sum +=$netprice;
            $mes_lis_shi_tot_tot_selling_price_total_sum +=$sellingPrice;
            $mes_lis_shi_tot_tot_tax_total_sum +=$tax;//$item['mes_lis_shi_lin_amo_item_tax'];
            $mes_lis_shi_tot_tot_item_total_sum +=$item['mes_lis_shi_lin_qua_shi_quantity'];
            $mes_lis_shi_tot_tot_unit_total_sum +=$item['mes_lis_shi_lin_qua_shi_num_of_order_units'];
            data_shipment_item::where('data_shipment_item_id', $item['data_shipment_item_id'])->update([
               // 'mes_lis_shi_tra_dat_revised_delivery_date'=>$item->mes_lis_shi_tra_dat_revised_delivery_date,
                // 'mes_lis_shi_tot_tot_net_price_total'=>$item->mes_lis_shi_tot_tot_net_price_total,
                // 'mes_lis_shi_tot_tot_selling_price_total'=>$item->mes_lis_shi_tot_tot_selling_price_total,
                // 'mes_lis_shi_tot_tot_tax_total'=>$item->mes_lis_shi_tot_tot_tax_total,
                // 'mes_lis_shi_tot_tot_item_total'=>$item->mes_lis_shi_tot_tot_item_total,
                // 'mes_lis_shi_tot_tot_unit_total'=>$item->mes_lis_shi_tot_tot_unit_total,
                // 'mes_lis_shi_tot_fre_unit_weight_total'=>$item->mes_lis_shi_tot_fre_unit_weight_total,
                'mes_lis_shi_lin_qua_shi_num_of_order_units'=>$item['mes_lis_shi_lin_qua_shi_num_of_order_units'],
                'mes_lis_shi_lin_qua_shi_quantity'=>$item['mes_lis_shi_lin_qua_shi_quantity'],
                'mes_lis_shi_lin_qua_sto_quantity'=>$item['mes_lis_shi_lin_qua_ord_quantity']-$item['mes_lis_shi_lin_qua_shi_quantity'],
                'mes_lis_shi_lin_qua_sto_num_of_order_units'=>$item['mes_lis_shi_lin_qua_ord_num_of_order_units']-$item['mes_lis_shi_lin_qua_shi_num_of_order_units'],
                'mes_lis_shi_lin_qua_sto_reason_code'=>$item['mes_lis_shi_lin_qua_sto_reason_code'],
               // 'mes_lis_shi_lin_amo_item_net_price'=>$total_cost_price,
                'mes_lis_shi_lin_amo_item_net_price'=>$netprice,
                'mes_lis_shi_lin_amo_item_net_price_unit_price'=>$item['mes_lis_shi_lin_amo_item_net_price_unit_price'],
                //'mes_lis_shi_lin_amo_item_selling_price'=>$total_selling_price,
                'mes_lis_shi_lin_amo_item_selling_price'=>$sellingPrice,
                'mes_lis_shi_lin_amo_item_tax'=>$tax,
                'mes_lis_shi_lin_amo_item_selling_price_unit_price'=>$item['mes_lis_shi_lin_amo_item_selling_price_unit_price'],
            ]);
        }
       if(isset($updated_date)){
        data_shipment_voucher::where('data_shipment_voucher_id', $items[0]['data_shipment_voucher_id'])->update([
            'mes_lis_shi_tra_dat_revised_delivery_date'=>$updated_date
            ]);
       }
        data_shipment_voucher::where('data_shipment_voucher_id', $items[0]['data_shipment_voucher_id'])->update([
            'mes_lis_shi_tot_tot_net_price_total'=>$mes_lis_shi_tot_tot_net_price_total_sum,
            'mes_lis_shi_tot_tot_selling_price_total'=>$mes_lis_shi_tot_tot_selling_price_total_sum,
            'mes_lis_shi_tot_tot_tax_total'=>$mes_lis_shi_tot_tot_tax_total_sum,
            'mes_lis_shi_tot_tot_item_total'=>$mes_lis_shi_tot_tot_item_total_sum,
            'mes_lis_shi_tot_tot_unit_total'=>$mes_lis_shi_tot_tot_unit_total_sum,
            'status'=>$status
            ]);

        return response()->json(['success' => '1']);
    }

    public function update_shipment_item_details_from_search(Request $request)
    {
        //return $request->all();
        $items = $request->items;
        // print_r($items[0]['data_shipment_voucher_id']);exit;
        // echo $items[0]->data_shipment_voucher_id;exit;
        $updated_date = $request->updated_date;
        $total_selling_price = $request->total_selling_price;
        $total_cost_price = $request->total_cost_price;
        $status = $request->order_status;
        $mes_lis_shi_tot_tot_net_price_total_sum=0;
        $mes_lis_shi_tot_tot_selling_price_total_sum=0;
        $mes_lis_shi_tot_tot_tax_total_sum=0;
        $mes_lis_shi_tot_tot_item_total_sum=0;
        $mes_lis_shi_tot_tot_unit_total_sum=0;

        foreach ($items as $item) {
            $netprice = $item['mes_lis_shi_lin_qua_shi_quantity']*$item['mes_lis_shi_lin_amo_item_net_price_unit_price'];
            $sellingPrice = $item['mes_lis_shi_lin_qua_shi_quantity']*$item['mes_lis_shi_lin_amo_item_selling_price_unit_price'];
            $tax = ($netprice*$item['mes_lis_shi_tra_tax_tax_rate'])/100;

            data_shipment_item::where('data_shipment_item_id', $item['data_shipment_item_id'])->update([
                'mes_lis_shi_lin_qua_shi_num_of_order_units'=>$item['mes_lis_shi_lin_qua_shi_num_of_order_units'],
                'mes_lis_shi_lin_qua_shi_quantity'=>$item['mes_lis_shi_lin_qua_shi_quantity'],
                'mes_lis_shi_lin_qua_sto_quantity'=>$item['mes_lis_shi_lin_qua_ord_quantity']-$item['mes_lis_shi_lin_qua_shi_quantity'],
                'mes_lis_shi_lin_qua_sto_num_of_order_units'=>$item['mes_lis_shi_lin_qua_ord_num_of_order_units']-$item['mes_lis_shi_lin_qua_shi_num_of_order_units'],
                'mes_lis_shi_lin_qua_sto_reason_code'=>$item['mes_lis_shi_lin_qua_sto_reason_code'],
                'mes_lis_shi_lin_amo_item_net_price'=>$netprice,
                'mes_lis_shi_lin_amo_item_net_price_unit_price'=>$item['mes_lis_shi_lin_amo_item_net_price_unit_price'],
                'mes_lis_shi_lin_amo_item_selling_price'=>$sellingPrice,
                'mes_lis_shi_lin_amo_item_tax'=>$tax,
                'mes_lis_shi_lin_amo_item_selling_price_unit_price'=>$item['mes_lis_shi_lin_amo_item_selling_price_unit_price'],
            ]);
        }
       if(isset($updated_date)){
        data_shipment_voucher::where('data_shipment_voucher_id', $items[0]['data_shipment_voucher_id'])->update([
            'mes_lis_shi_tra_dat_revised_delivery_date'=>$updated_date
            ]);
       }
       /*new code for update status and total*/
       $data_shipment_voucher_id = $items[0]['data_shipment_voucher_id'];
       $result = DB::select("
       SELECT data_shipment_items.*,data_shipment_vouchers.*,data_order_vouchers.*,data_order_items.* FROM data_shipment_items
       inner join data_shipment_vouchers on data_shipment_vouchers.data_shipment_voucher_id=data_shipment_items.data_shipment_voucher_id
       inner join data_shipments on data_shipments.data_shipment_id=data_shipment_vouchers.data_shipment_id
       inner join data_order_vouchers on data_order_vouchers.data_order_id=data_shipments.data_order_id AND data_order_vouchers.mes_lis_ord_tra_trade_number = data_shipment_vouchers.mes_lis_shi_tra_trade_number
       inner join data_order_items on data_order_items.data_order_voucher_id=data_order_vouchers.data_order_voucher_id AND data_order_items.mes_lis_ord_lin_lin_line_number = data_shipment_items.mes_lis_shi_lin_lin_line_number
       where data_shipment_items.data_shipment_voucher_id = '$data_shipment_voucher_id'
       order by data_shipment_items.mes_lis_shi_lin_lin_line_number
       ");
       if($result){
           $allIsfull =array();
           $allIsZero =array();
           $allIsNotZero =array();
           foreach($result as $vl){
               if($vl->mes_lis_shi_lin_qua_shi_quantity==0){
                $allIsZero[]=$vl;
               }
               if($vl->mes_lis_shi_lin_qua_shi_num_of_order_units==$vl->mes_lis_shi_lin_qua_ord_num_of_order_units){
                $allIsfull[]=$vl;
               }

               $netprice = $vl->mes_lis_shi_lin_qua_shi_quantity*$vl->mes_lis_shi_lin_amo_item_net_price_unit_price;
                $sellingPrice = $vl->mes_lis_shi_lin_qua_shi_quantity*$vl->mes_lis_shi_lin_amo_item_selling_price_unit_price;
                $tax = ($netprice*$vl->mes_lis_shi_tra_tax_tax_rate)/100;

               $mes_lis_shi_tot_tot_net_price_total_sum +=$netprice;
               $mes_lis_shi_tot_tot_selling_price_total_sum +=$sellingPrice;
               $mes_lis_shi_tot_tot_tax_total_sum +=$tax;
               $mes_lis_shi_tot_tot_item_total_sum +=$vl->mes_lis_shi_lin_qua_shi_quantity;
               $mes_lis_shi_tot_tot_unit_total_sum +=$vl->mes_lis_shi_lin_qua_shi_num_of_order_units;

           }
           $totalRows =count($result);
           $allIsfullLength =count($allIsfull);
           $allIsZeroLength =count($allIsZero);
           $update_status = '一部未納';
           if($totalRows==$allIsZeroLength){
             $update_status = '未納';
           }
           if($totalRows==$allIsfullLength){
             $update_status = '完納';
           }
       }
      /*new code for update status and total*/
        data_shipment_voucher::where('data_shipment_voucher_id', $data_shipment_voucher_id)->update([
            'mes_lis_shi_tot_tot_net_price_total'=>$mes_lis_shi_tot_tot_net_price_total_sum,
            'mes_lis_shi_tot_tot_selling_price_total'=>$mes_lis_shi_tot_tot_selling_price_total_sum,
            'mes_lis_shi_tot_tot_tax_total'=>$mes_lis_shi_tot_tot_tax_total_sum,
            'mes_lis_shi_tot_tot_item_total'=>$mes_lis_shi_tot_tot_item_total_sum,
            'mes_lis_shi_tot_tot_unit_total'=>$mes_lis_shi_tot_tot_unit_total_sum,
            'status'=>$update_status
            ]);

        return response()->json(['success' => '1']);
    }
    public function slrPdfDownload(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();

        $pdf_download_type=$request->pdf_download_type;
        $order_info=$request->order_info;

        // return $request->all();
        $csv_data_count =0;
        $download_file_url=null;
        if ($pdf_download_type=='order_pdf') {
            $cur_datetime=date('y-m-d H:i:s');
            // CSV Download
            // $new_file_name = $this->all_functions->downloadFileName($request, 'pdf', '受注');
            // $download_file_url = Config::get('app.url')."storage/app".config('const.PDF_SAVE_PATH')."/". $new_file_name;
            $new_file_name = $this->all_functions->downloadPdfFileName($order_info, 'pdf', '発注明細書');
            // get shipment data query
            // $pdf_data_json = DataController::getShipmentPdfData($request);
            $pdf_data_json = DataController::getOrderPdfData($request);
            $pdf_datas=$pdf_data_json['report_arr_final'];
            $voucher_id_array=$pdf_data_json['voucher_id_array'];

            $download_files=$this->pdfGenerate($pdf_datas,$new_file_name);
            $download_file_url=$download_files[0]['pdf_file_url'];
            $pdf_file_names=$download_files[0]['pdf_file_name'];

            foreach ($voucher_id_array as $key => $voucher_id) {
                data_shipment_voucher::where('data_order_voucher_id', $voucher_id)->update(
                    ['print_datetime'=>$cur_datetime]
                );
            }
        }
        Log::debug(__METHOD__.':end---');

        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$pdf_file_names, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }

    // PDF Function
    public function pdfGenerate($pdf_datas=[],$new_file_name)
    {
        Log::debug(__METHOD__.':start---');

        $pdf_file_paths=array();
        $x = 0;
        $y = 0;
        $odd_even=0;
        $data_count=0;
        $first_page=0;
        $shipment_pdf_save_path=config('const.SLR_SHIPMENT_PDF_SAVE_PATH');

        $receipt=$this->all_functions->fpdfRet();
        foreach ($pdf_datas as $key => $sel_data) {
            if ($first_page!=0) {
                $receipt->AddPage();
                $first_page+=1;
            }

            foreach ($sel_data as $key => $rec_data) {
                if ($first_page==0) {
                    $receipt->AddPage();
                }

                foreach ($rec_data as $key => $trade_data) {
                    if ($data_count==0) {
                        $receipt=$this->all_functions->pdfHeaderData($receipt, $trade_data, $x, $y);
                    }
                    if ($odd_even==0) {
                        if ($data_count!=0 && $data_count%2==0) {
                            $receipt->AddPage();
                            $receipt=$this->all_functions->pdfHeaderData($receipt, $trade_data, $x, $y);
                        }
                        $this->all_functions->coordinateText($receipt, $trade_data, 0, 50.7, 103.4);
                    } else {
                        $this->all_functions->coordinateText($receipt, $trade_data, 0, 117, 170);
                    }

                    if ($odd_even==0) {
                        $odd_even=1;
                    } else {
                        $odd_even=0;
                    }
                    $data_count+=1;
                }
                $data_count=0;
                $odd_even=0;
            }
            $first_page=0;
        }
        $pdf_file_path= $this->all_functions->pdfFileSave($receipt, $new_file_name, $shipment_pdf_save_path);
        array_push($pdf_file_paths, $pdf_file_path);
        // return 0;
        Log::debug(__METHOD__.':end---');
        return $pdf_file_paths;
        // return $response;
    }
}
