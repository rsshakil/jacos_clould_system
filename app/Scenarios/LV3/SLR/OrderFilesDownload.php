<?php

namespace App\Scenarios\LV3\SLR;

use App\Http\Controllers\API\CMN\CmnScenarioController;
use App\Scenarios\ScenarioBase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use App\Scenarios\LV3\SLR\OrderData;
use App\Traits\Csv;
use App\Http\Controllers\API\DATA\SHIPMENT\ShipmentController;

class OrderFilesDownload extends ScenarioBase
{
    private $message;
    private $status_code;
    // private $ShipmentController;
    public function __construct()
    {
        parent::__construct();
        $this->message='';
        $this->status_code='';
        // $this->ShipmentController = new ShipmentController();
    }

    //
    public function exec($request, $sc)
    {
        Log::debug(__METHOD__.':start---');
        $file_name="";
        $file_path="";
        $this->status_code=$this->success;
        // $data_order_id=$request->data_order_id?$request->data_order_id:1;
        $csv=$request->csv;
        $jca=$request->jca;
        $order_pdf=$request->order_pdf;
        $shipment_pdf=$request->shipment_pdf;

        $csv_file_name=null;
        $csv_file_path=null;
        $jca_file_name=null;
        $jca_file_path=null;
        $order_pdf_file_name=null;
        $order_pdf_file_path=null;
        $shipment_pdf_file_name=null;
        $shipment_pdf_file_path=null;

        $csv_data_count =0;
        // get shipment data query
        $data_query = OrderData::get_shipment_data($request);
        $shipment_voucher_id_array = $data_query['voucher_id_array'];
        $order_pdf_query = OrderData::getOrderPdfData($request);

        // return ['message'=>'Success','status'=>1,'data'=>$data_query];
        $csv_data_count = $data_query['total_data'];
        $shipment_data = $data_query['shipment_data'];
        if ($csv==1) {
            // CSV Download
            if (!empty($shipment_data)) {
                // $new_file_name = $this->all_functions->downloadFileName($request, 'csv', '受注');
                $csv_file_name = time().'_shipment_csv.csv';
                $csv_file_path = Config::get('app.url')."storage/app".config('const.LV3_SHIPMENT_CSV_PATH')."/". $csv_file_name;
                // CSV create
                Csv::create(
                    config('const.LV3_SHIPMENT_CSV_PATH')."/". $csv_file_name,
                    $shipment_data,
                    OrderData::shipmentCsvHeading(),
                    config('const.CSV_FILE_ENCODE')
                );
            }

        }
        if ($jca==1) {
            // JCA Download
            $request->request->add(['scenario_id' => 6]);
            $jca_file_name = time().'_shipment_jca.txt';
            $request->request->add(['file_name' => $jca_file_name]);
            $request->request->add(['order_data' => $shipment_data]);
            $request->request->add(['file_save_path' => config('const.LV3_SHIPMENT_JCA_PATH')]);
            $cs = new CmnScenarioController();
            $ret = $cs->exec($request);
            $this->status_code=$ret['status'];
            if ($this->status_code==$this->success) {
                $jca_file_path = Config::get('app.url')."storage/".config('const.LV3_SHIPMENT_JCA_PATH').'/'. $jca_file_name;
            }else{
                $jca_file_name =null;
            }

        }
        if ($shipment_pdf==1) {
            if (!empty($shipment_data)) {
                $shipment_pdf_file_name = time().'_shipment_pdf.pdf';
                $request->request->add(['file_name' => $shipment_pdf_file_name]);
                $request->request->add(['order_data' => $shipment_data]);
                $request->request->add(['file_save_path' => config('const.LV3_SHIPMENT_PDF_PATH')]);
                $request->request->add(['shipment_download_type' => 'picking_pdf']);
                $shipmentController = new ShipmentController();
                $ret = $shipmentController->pdfDownload($request);
                $ret=json_decode($ret->getContent(),true);
                Log::info($ret);
                // return false;
                $this->status_code=$ret['status'];
                if ($this->status_code==$this->success) {
                    $shipment_pdf_file_path = Config::get('app.url')."storage/".config('const.LV3_SHIPMENT_PDF_PATH'). $shipment_pdf_file_name;
                }else{
                    $shipment_pdf_file_name =null;
                }
            }
        }
        OrderData::checkDateTimeChange($shipment_voucher_id_array);

        if ($order_pdf==1) {

            $csv_data_count = ($order_pdf_query['raw_shipment_data'])->count();
            $order_data = $order_pdf_query['report_arr_final'];
            $voucher_id_arr = $order_pdf_query['voucher_id_array'];

            if (!empty($order_data)) {
                $order_pdf_file_name = time().'_order_pdf.pdf';
                $shipmentController = new ShipmentController();
                $ret = $shipmentController->pdfGenerate($order_data,$order_pdf_file_name,config('const.LV3_ORDER_PDF_PATH'));
                if (isset($ret[0])) {
                    $order_pdf_file_name = $ret[0]['pdf_file_name'];
                    $order_pdf_file_path = $ret[0]['pdf_file_url'];
                }else{
                    $order_pdf_file_name = null;
                    $order_pdf_file_path = null;
                }
            }
            OrderData::checkDateTimeChange($voucher_id_arr);
        }
        $data_array=array(
            [
                'file_name'=>$csv_file_name,
                'file_path'=>$csv_file_path,
            ],
            [
                'file_name'=>$jca_file_name,
                'file_path'=>$jca_file_path,
            ],
            [
                'file_name'=>$order_pdf_file_name,
                'file_path'=>$order_pdf_file_path,
            ],
            [
                'file_name'=>$shipment_pdf_file_name,
                'file_path'=>$shipment_pdf_file_path,
            ]
        );
        Log::debug(__METHOD__.':end---');
        return ['message' => $this->message, 'status' => $this->success,'data' => $data_array];
        // return ['message' => $this->message, 'status' => $this->status_code,'data' => ['file_name' => $file_name,'file_path'=>$file_path]];
    }
}
