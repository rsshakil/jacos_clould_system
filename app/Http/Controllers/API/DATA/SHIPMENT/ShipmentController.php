<?php

namespace App\Http\Controllers\API\DATA\SHIPMENT;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\DATA\Data_Controller;
use App\Models\BYR\byr_buyer;
// use App\Exports\ShipmentCSVExport;
use App\Models\DATA\SHIPMENT\data_shipment;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;
use App\Http\Controllers\API\CMN\CmnScenarioController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Traits\Csv;
use App\Models\ADM\User;
use App\Scenarios\byr\OUK\data_csv_order;
use setasign\Fpdi\Tcpdf\Fpdi;

require_once base_path('vendor/tecnickcom/tcpdf/tcpdf.php');
use Symfony\Component\HttpFoundation\Response;
use tecnickcom\tcpdf\TCPDF_FONTS;

class ShipmentController extends Controller
{
    private $all_functions;
    private $data_controller;
    private $data_csv_order;
    public function __construct()
    {
        $this->all_functions = new AllUsedFunction();
        $this->data_csv_order = new data_csv_order();
        $this->data_controller = new Data_Controller();
        $this->all_functions->folder_create('app/'.config('const.SHIPMENT_SEND_CSV_PATH'));
        $this->all_functions->folder_create('app/'.config('const.SHIPMENT_MOVED_CSV_PATH'));
        $this->all_functions->folder_create('app/'.config('const.SHIPMENT_DOWNLOAD_CSV_PATH'));
    }
    public function sendShipmentData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $data_count=$request->data_count;
        $data_order_id=$request->data_order_id;
        $download_file_url='';
        $adm_user_id=$request->adm_user_id;
        $byr_buyer_id=$request->byr_buyer_id;
        $authUser = User::find($adm_user_id);
        $order_info=$request->order_info;
        $dateTime = date('Y-m-d H:i:s');
        $cmn_connect_id = '';
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info=$this->all_functions->get_user_info($adm_user_id, $byr_buyer_id);
            $cmn_connect_id = $cmn_company_info['cmn_connect_id'];
        }

        $csv_data_count = data_shipment_voucher::join('data_shipments as ds', 'ds.data_shipment_id', '=', 'data_shipment_vouchers.data_shipment_id')
            ->whereNotNull('data_shipment_vouchers.decision_datetime')
            ->whereNull('data_shipment_vouchers.send_datetime')
            // ->where('ds.cmn_connect_id', $cmn_connect_id)
            ->where('ds.data_order_id', $data_order_id)
            ->where('data_shipment_vouchers.mes_lis_shi_log_del_delivery_service_code', $order_info['mes_lis_shi_log_del_delivery_service_code'])
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_code', $order_info['mes_lis_shi_par_sel_code'])
            ->where('data_shipment_vouchers.mes_lis_shi_par_sel_name', $order_info['mes_lis_shi_par_sel_name'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_dat_delivery_date_to_receiver', $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_goo_major_category', $order_info['mes_lis_shi_tra_goo_major_category'])
            ->where('data_shipment_vouchers.mes_lis_shi_tra_ins_temperature_code', $order_info['mes_lis_shi_tra_ins_temperature_code'])->get()->count();

        if (!$data_count) {
            $request->request->add(['cmn_connect_id' => $cmn_connect_id]);

            // $new_file_name = $this->all_functions->downloadFileName($request, 'csv', '受注');
            $new_file_name = $this->all_functions->sendFileName($request, 'csv', 'shipment');

            data_shipment::where('data_order_id', $data_order_id)->update(['mes_mes_number_of_trading_documents'=>$csv_data_count]);
            $send_file_path = config('const.SHIPMENT_SEND_CSV_PATH')."/". $new_file_name;
            $download_file_url = Config::get('app.url')."storage/app".$send_file_path;
            // ==============
            $shipment_query = Data_Controller::get_shipment_data($request);
            // $csv_data_count = $shipment_query['total_data'];
            $shipment_data = $shipment_query['shipment_data'];

            // CSV create
            // UTF-8
            Csv::create(
                $send_file_path,
                $shipment_data,
                Data_Controller::shipmentCsvHeading()
            );
            // ==============
            // (new ShipmentCSVExport($request))->store(config('const.SHIPMENT_SEND_CSV_PATH').'/'.$new_file_name);
            data_shipment_voucher::whereNotNull('decision_datetime')
            ->whereNull('send_datetime')
            ->where('mes_lis_shi_log_del_delivery_service_code', $order_info['mes_lis_shi_log_del_delivery_service_code'])
            ->where('mes_lis_shi_par_sel_code', $order_info['mes_lis_shi_par_sel_code'])
            ->where('mes_lis_shi_par_sel_name', $order_info['mes_lis_shi_par_sel_name'])
            ->where('mes_lis_shi_tra_dat_delivery_date_to_receiver', $order_info['mes_lis_shi_tra_dat_delivery_date_to_receiver'])
            ->where('mes_lis_shi_tra_goo_major_category', $order_info['mes_lis_shi_tra_goo_major_category'])
            ->where('mes_lis_shi_tra_ins_temperature_code', $order_info['mes_lis_shi_tra_ins_temperature_code'])
            ->update(['send_datetime'=>$dateTime]);
            $cmn_connect_info=$this->all_functions->logInformation($byr_buyer_id);

            Log::info('[SHIPMENT][sendShipmentData]:'.$cmn_connect_info->buyer_name.','.$cmn_connect_info->seller_name.',partner_code:'.$cmn_connect_info->partner_code.',user_id:'.Auth::User()->id.',data_count:'.$csv_data_count.',file_path:'.$send_file_path);
        }
        Log::debug(__METHOD__.':end---');

        return response()->json(['message' => 'Success','status'=>1, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    public function downloadShipmentCsv(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        //ownloadType=2 for Fixed length
        $data_order_id=$request->data_order_id?$request->data_order_id:1;
        $downloadType=$request->downloadType;
        $csv_data_count =0;
        $retStatus=1;
        if ($downloadType==1) {
            // CSV Download
            $new_file_name = $this->all_functions->downloadFileName($request, 'csv', '受注');
            $download_file_url = Config::get('app.url')."storage/app".config('const.SHIPMENT_DOWNLOAD_CSV_PATH')."/". $new_file_name;

            // get shipment data query
            $shipment_query = Data_Controller::get_shipment_data($request);
            $csv_data_count = $shipment_query['total_data'];
            $shipment_data = $shipment_query['shipment_data'];
            if (!empty($shipment_data)) {
                // CSV create
                Csv::create(
                    config('const.SHIPMENT_DOWNLOAD_CSV_PATH')."/". $new_file_name,
                    $shipment_data,
                    Data_Controller::shipmentCsvHeading(),
                    config('const.CSV_FILE_ENCODE')
                );
            } else {
                $retStatus=0;
            }
        } elseif ($downloadType==2) {
            // JCA Download
            // $request = new \Illuminate\Http\Request();
            // $request->setMethod('POST');
            // $request=$this->request;
            $request->request->add(['scenario_id' => 6]);
            $request->request->add(['data_order_id' => $data_order_id]);
            $new_file_name =$this->all_functions->downloadFileName($request, 'txt', '受注');
            $download_file_url = Config::get('app.url')."storage/".config('const.JCA_FILE_PATH').'/'. $new_file_name;
            $request->request->add(['file_name' => $new_file_name]);
            // $request->request->remove('downloadType');
            // return $request->all();
            $cs = new CmnScenarioController();
            $ret = $cs->exec($request);
            $retStatus=$ret['status'];
        }
        Log::debug(__METHOD__.':end---');

        return response()->json(['message' => 'Success','status'=>$retStatus,'new_file_name'=>$new_file_name, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    public function pdfDownload(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        // return $request->all();
        $order_info=$request->order_info;
        $byr_buyer_id=$request->byr_buyer_id;
        $shipment_download_type=$request->shipment_download_type;
        $csv_data_count =0;
        $download_file_url=null;
        $pdf_file_names=null;
        if ($shipment_download_type=='order_pdf') {
            $cur_datetime=date('y-m-d H:i:s');
            // CSV Download
            $new_file_name = $this->all_functions->downloadPdfFileName($order_info, 'pdf', '発注明細書', $byr_buyer_id);
            // $new_file_name = $this->all_functions->downloadFileName($request, 'pdf', '受注');
            // $download_file_url = Config::get('app.url')."storage/app".config('const.PDF_SAVE_PATH')."/". $new_file_name;

            // get shipment data query
            $pdf_data_json = Data_Controller::getOrderPdfData($request);
            $pdf_datas=$pdf_data_json['report_arr_final'];
            $voucher_id_array=$pdf_data_json['voucher_id_array'];
            $csv_data_count = $pdf_data_json['raw_shipment_data']->count();

            $download_files=$this->pdfGenerate($pdf_datas, $new_file_name);
            $download_file_url=$download_files[0]['pdf_file_url'];
            $pdf_file_names=$download_files[0]['pdf_file_name'];

            foreach ($voucher_id_array as $key => $voucher_id) {
                data_shipment_voucher::where('data_order_voucher_id', $voucher_id)->update(
                    ['print_datetime'=>$cur_datetime]
                );
            }
        } elseif ($shipment_download_type=='picking_pdf') {
            mb_internal_encoding("UTF-8");
            if ($request->has('file_name')) {
                $new_file_name = $request->file_name;
            }else{
                $new_file_name = $this->all_functions->downloadPdfFileName($order_info, 'pdf', 'ピッキングリスト', $byr_buyer_id);
            }

            if ($request->has('order_data')) {
                $pdf_data_json = $request->order_data;
            }else{
                $shipment_query = Data_Controller::get_shipment_data($request);
                $csv_data_count = $shipment_query['total_data'];
                $pdf_data_json = $shipment_query['shipment_data'];
            }
            if ($request->has('file_save_path')) {
                $picking_pdf_save_path=$request->file_save_path;
            }else{
                $picking_pdf_save_path=config('const.PICKING_PDF_SAVE_PATH');
            }
            // get Picking pdf data;


            // sakaki

            // datacheck
            if (!$pdf_data_json) {
                return;
            }

            $data=array();
            $item_list = array();
            $shop_list = array();
            $item_total = array();
            foreach ($pdf_data_json as $key => $value) {

                // 訂正納品日
                $delivery_date = $value['mes_lis_shi_tra_dat_revised_delivery_date'];
                if (!$delivery_date) {
                    // 最終納品先納品日
                    $delivery_date = $value['mes_lis_shi_tra_dat_delivery_date_to_receiver'];
                }
                // item name
                if (!isset($item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['mes_lis_shi_lin_ite_name'])) {
                    $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']] = [
                        'mes_lis_shi_lin_ite_name' => $value['mes_lis_shi_lin_ite_name'],
                        'mes_lis_shi_lin_qua_unit_multiple' => $value['mes_lis_shi_lin_qua_unit_multiple'],

                    ];
                }

                // item info
                $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['shops'][$value['mes_lis_shi_par_shi_code']] = [
                        'mes_lis_shi_lin_qua_shi_quantity' => $value['mes_lis_shi_lin_qua_shi_quantity'],
                        'mes_lis_shi_lin_qua_shi_num_of_order_units' => $value['mes_lis_shi_lin_qua_shi_num_of_order_units'],
                ];

                // total
                if (!isset($item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_quantity'])) {
                    // total_quantity
                    $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_quantity'] =
                        $value['mes_lis_shi_lin_qua_shi_quantity'];
                    // total_unit
                    $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_unit'] =
                        $value['mes_lis_shi_lin_qua_shi_num_of_order_units'];
                } else {
                    // total_quantity
                    $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_quantity'] =
                        $value['mes_lis_shi_lin_qua_shi_quantity'] + $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_quantity'];
                    // total_unit
                    $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_unit'] =
                        $value['mes_lis_shi_lin_qua_shi_num_of_order_units'] + $item_list[$delivery_date][$value['mes_lis_shi_lin_ite_order_item_code']]['total_unit'];
                }

                $shop_list[$delivery_date][$value['mes_lis_shi_par_shi_code']] = [
                    // 'mes_lis_shi_par_shi_name' => $value['mes_lis_shi_par_shi_name'],
                    'mes_lis_shi_par_shi_name' => mb_substr($value['mes_lis_shi_par_shi_name'], 0, 4),
                    'mes_lis_shi_par_shi_code' => $value['mes_lis_shi_par_shi_code'],
                ];
                // $item_total[]
            }



            //  $data[page][
            //              company_name => $company_name,
            //              receive_date => $receive_date,
            //              shops => [[shop1_code,shop1_name],[shop2_code,shop2_name],...],
            //              items => [[item1_code,item1_name,total_quantity,total_unit,order_unit,
            //                           shop_item => [1,2,3,4,5,6,7,8,9,10]
            //                        ],[item2_code,item2_name,total_quantity,total_unit,order_unit,
            //                           shop_item => [1,2,3,4,5,6,7,8,9,10]
            //                        ],]
            //
            //

            $date_page = 0;
            $date_cnt = 0;
            $item_page = 0;
            $item_cnt = 0;
            $shop_page = 0;
            $shop_cnt = 0;
            $shop_start = 0;

            foreach ($item_list as $item_date => $items) {
                $data[$date_page][$shop_page][$item_page]['receive_date'] =  $item_date;                              // 納品日
                $data[$date_page][$shop_page][$item_page]['company_name'] = $pdf_data_json[0]['mes_lis_buy_name'];   // 社名


                for ($shop_cnt = 0; $shop_cnt<count($shop_list[$item_date])/10; $shop_cnt++) {

                    // shops
                    $shops = array_slice($shop_list[$item_date], $shop_start, 10);
                    $data[$date_page][$shop_page][$item_page]['receive_date'] =  $item_date;                              // 納品日
                    $data[$date_page][$shop_page][$item_page]['company_name'] = $pdf_data_json[0]['mes_lis_buy_name'];   // 社名
                    $data[$date_page][$shop_page][$item_page] ['shops'] = $shops;

                    // items
                    for ($item_cnt = 0;$item_cnt<count($items);$item_cnt++) {
                        if (($item_cnt !== 0) && ($item_cnt%30 === 0)) {
                            $item_page++;
                            // shops
                            $shops = array_slice($shop_list[$item_date], $shop_start, 10);
                            $data[$date_page][$shop_page][$item_page]['receive_date'] =  $item_date;                              // 納品日
                            $data[$date_page][$shop_page][$item_page]['company_name'] = $pdf_data_json[0]['mes_lis_buy_name'];   // 社名
                            $data[$date_page][$shop_page][$item_page] ['shops'] = $shops;
                        }
                        $item = array_keys($items)[$item_cnt];

                        $detail = $items[$item];

                        $shop_items = [];
                        foreach ($shops as $shop) {
                            $shop_code = $shop['mes_lis_shi_par_shi_code'];
                            if (isset($item_list[$item_date][$item]['shops'][$shop_code])) {
                                $shop_items[] = $item_list[$item_date][$item]['shops'][$shop_code]['mes_lis_shi_lin_qua_shi_quantity'];
                            } else {
                                $shop_items[] = '';
                            }
                        }

                        $data[$date_page][$shop_page][$item_page]['items'][] = [
                            'order_item_code'=>$item,
                            // 'item_name'=>$detail['mes_lis_shi_lin_ite_name'], // 商品名
                            'item_name'=>mb_substr($detail['mes_lis_shi_lin_ite_name'], 0, 20), // 商品名
                            'total_quantity'=>$detail['total_quantity'], // total_quantity
                            'total_unit'=>$detail['total_unit'], // total_unit
                            'unit_multiple'=>$detail['mes_lis_shi_lin_qua_unit_multiple'], // 入数
                            'shop_items' => $shop_items
                        ];
                        // $data[$date_page][$shop_page][$item_page]['items'][] = [
                        //     $item,
                        //     $detail['mes_lis_shi_lin_ite_name'],            // 商品名
                        //     $detail['total_quantity'],                      // total_quantity
                        //     $detail['total_unit'],                           // total_unit
                        //     $detail['mes_lis_shi_lin_qua_unit_multiple'],   // 入数
                        //     'shop_items' => $shop_items
                        // ];
                    }
                    $shop_start = $shop_start + 10;
                    $shop_page++;
                }

                $shop_start = 0;
                $date_page++;
            }
            $total_page=$item_page+$shop_page;
            // sakaki
            $download_files=$this->pickingPdfGenerate($data, $total_page, $new_file_name,$picking_pdf_save_path);
            $download_file_url=$download_files[0]['pdf_file_url'];
            $pdf_file_names=$download_files[0]['pdf_file_name'];
        }
        $log_info=$this->all_functions->logInformation($byr_buyer_id);
        Log::info($shipment_download_type.' PDF download: '.$log_info->buyer_name.','.$log_info->seller_name.','.$log_info->partner_code.','.'order'.','.Auth::User()->id.','.$csv_data_count);
        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$pdf_file_names, 'url' => $download_file_url,'csv_data_count'=>$csv_data_count]);
    }
    // Picking Pdf Function
    public function pickingPdfGenerate($pdf_datas=[], $total_page=0, $new_file_name=null,$picking_pdf_save_path=null)
    {
        Log::debug(__METHOD__.':start---');
        $pdf_file_paths=array();
        $x = 0;
        $y = 0;
        $current_page=1;

        $receipt=$this->all_functions->fpdfRet();
        // ==============================
        foreach ($pdf_datas as $pdf_datas_key => $pdf_data) {
            foreach ($pdf_data as $pdf_data_key => $shi_name_data) {
                foreach ($shi_name_data as $shi_name_data_key => $rec_name_data) {
                    $receipt=$this->pickingHeader($receipt, $rec_name_data, $current_page, $total_page, $x, $y);
                    $x=129;
                    $y=40;
                    // foreach ($rec_name_data[($shi_name_data_key*30)]['headings'] as $heading_key => $heading) {
                    foreach ($rec_name_data['shops'] as $heading_key => $heading) {
                        $x+=13.3;
                        $y+=4;
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(13.2, 4.25, $heading['mes_lis_shi_par_shi_name'], 0, 1, 'L', 0, '', 0);
                        $y+=4;
                        $receipt->SetXY($x, $y);
                        $receipt->Cell(13.2, 4.25, $heading['mes_lis_shi_par_shi_code'], 0, 1, 'L', 0, '', 0);
                        $y=40;
                    }
                    $x=0;
                    $y=48.2;
                    foreach ($rec_name_data['items'] as $data_key => $data) {
                        $y+=4.35;
                        $receipt->SetXY($x+18, $y);
                        $receipt->Cell(5, 4.1, ($data_key+1), 0, 1, 'C', 0, '', 0);
                        $receipt->SetXY($x+23.7, $y);
                        $receipt->Cell(59, 4.1, $data['item_name'], 0, 1, 'L', 0, '', 0);
                        $receipt->SetXY($x+82.5, $y);
                        $receipt->Cell(25.5, 4.1, $data['order_item_code'], 0, 1, 'C', 0, '', 0);
                        $receipt->SetXY($x+107.9, $y);
                        // $receipt->Cell(12, 4.1, $data['total_qty']==0?'':$data['total_qty'], 0, 1, 'C', 0, '', 0);
                        $receipt->Cell(12, 4.1, $data['total_quantity'], 0, 1, 'C', 0, '', 0);
                        $receipt->SetXY($x+120.2, $y);
                        $receipt->Cell(12.2, 4.1, $data["total_unit"]==0?'':$data['total_unit'], 0, 1, 'C', 0, '', 0);
                        $receipt->SetXY($x+132.5, $y);
                        $receipt->Cell(9.4, 4.1, $data['unit_multiple'], 0, 1, 'C', 0, '', 0);
                        $x=129.4;
                        foreach ($data['shop_items'] as $item_key => $qty_position) {
                            $x+=13;
                            $receipt->SetXY($x, $y);
                            $receipt->Cell(12.8, 4.5, round($qty_position, 1)==0?'':round($qty_position, 1), 0, 1, 'R', 0, '', 0);
                        }
                        $x=0;
                    }
                    $x=0;
                    $y=0;
                    $current_page+=1;
                }
            }
        }
        $pdf_file_path= $this->all_functions->pdfFileSave($receipt, $new_file_name, $picking_pdf_save_path);
        array_push($pdf_file_paths, $pdf_file_path);
        Log::debug(__METHOD__.':end---');
        return $pdf_file_paths;
    }
    // Picking Pdf Header
    public function pickingHeader($receipt, $pdf_datas, $current_page=1, $total_page=0, $x, $y)
    {
        Log::debug(__METHOD__.':start---');
        $receipt-> AddPage();
        $receipt->setSourceFile(storage_path(config('const.BLANK_PICKING_PDF_PATH')));
        $tplIdx = $receipt->importPage(1);
        $receipt->UseTemplate($tplIdx, null, null, null, null, true);
        $receipt->SetXY($x+240, $y + 25.1);
        $receipt->Cell(10, 0, $current_page, 0, 1, 'R', 0, '', 0);
        $receipt->SetXY($x+250.5, $y + 25.1);
        $receipt->Cell(10, 0, $total_page, 0, 1, 'L', 0, '', 0);
        $receipt->SetXY($x+37.5, $y + 35.1);
        $receipt->Cell(45, 0, $pdf_datas['company_name'], 0, 1, 'L', 0, '', 0);
        $receipt->SetXY($x+232, $y + 35.2);
        $receive_date=$pdf_datas['receive_date'];
        $receipt->Cell(45, 0, date('Y年m月d日', strtotime($receive_date)), 0, 1, 'L', 0, '', 0);
        Log::debug(__METHOD__.':end---');
        return $receipt;
    }
    // PDF Function
    public function pdfGenerate($pdf_datas=[], $new_file_name=null,$file_save_path=null)
    {
        Log::debug(__METHOD__.':start---');
        $pdf_file_paths=array();
        $x = 0;
        $y = 0;
        $odd_even=0;
        $data_count=0;
        $first_page=0;
        if ($file_save_path==null) {
            $order_pdf_save_path=config('const.SHIPMENT_PDF_SAVE_PATH');
        }else{
            $order_pdf_save_path=$file_save_path;
        }

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
        $pdf_file_path= $this->all_functions->pdfFileSave($receipt, $new_file_name, $order_pdf_save_path);
        array_push($pdf_file_paths, $pdf_file_path);
        // return 0;
        Log::debug(__METHOD__.':end---');
        return $pdf_file_paths;
        // return $response;
    }
    // PDF Function End

    public function deletedownloadedshipmentCsv($fileUrl)
    {
        Log::debug(__METHOD__.':start---');
        $path = storage_path().'/app'.config('const.SHIPMENT_DOWNLOAD_CSV_PATH')."/". $fileUrl;
        unlink($path);
        Log::debug(__METHOD__.':end---');
        return response()->json(['message' => 'Success','status'=>1]);
    }
    public function shipmentUpdate(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $byr_buyer_id=$request->byr_buyer_id;
        $buter_info=byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        // return $buter_info;
        $shipment_reason_code_array=json_decode($buter_info->setting_information, true)['shipments']['mes_lis_shi_lin_qua_sto_reason_code'];
        $file_name = time().'-'.$request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs(config('const.SHIPMENT_CSV_UPDATE_PATH'), $file_name);
        Log::debug('save path:'.$path);

        $received_path = storage_path().'/app//'.config('const.SHIPMENT_CSV_UPDATE_PATH').'/'.$file_name;
        // フォーマット変換

        $dataArr = $this->all_functions->csvReader($received_path, 1);
        $update_status=$this->data_controller->shipmentUpdateArray($dataArr, $file_name, $shipment_reason_code_array);
        $ret = json_decode($update_status->getContent(), true);
        if ($ret['status']===$this->error) {
            unlink(storage_path().'/app/'.$path);
        }
        Log::debug(__METHOD__.':end---');
        return $update_status;
    }

    public function update_shipment_item_details(Request $request)
    {
        Log::debug(__METHOD__.':start---');
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
        if (isset($updated_date)) {
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
        Log::debug(__METHOD__.':end---');
        return response()->json(['success' => '1']);
    }

    public function update_shipment_item_details_from_search(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $items = $request->items;
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
        if (isset($updated_date)) {
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
        if ($result) {
            $allIsfull =array();
            $allIsZero =array();
            $allIsNotZero =array();
            foreach ($result as $vl) {
                if ($vl->mes_lis_shi_lin_qua_shi_quantity==0) {
                    $allIsZero[]=$vl;
                }
                if ($vl->mes_lis_shi_lin_qua_shi_num_of_order_units==$vl->mes_lis_shi_lin_qua_ord_num_of_order_units) {
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
            if ($totalRows==$allIsZeroLength) {
                $update_status = '未納';
            }
            if ($totalRows==$allIsfullLength) {
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
        Log::debug(__METHOD__.':end---');
        return response()->json(['success' => '1']);
    }

    public function update_shipment_item_detail_form_data(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $items = $request->items;
        $updateArry = array(
            'mes_lis_shi_lin_qua_shi_quantity'=>$items['mes_lis_shi_lin_qua_shi_quantity'],
            'mes_lis_shi_lin_qua_shi_num_of_order_units'=>$items['mes_lis_shi_lin_qua_shi_num_of_order_units'],
            'mes_lis_shi_lin_fre_order_weight'=>$items['mes_lis_shi_lin_fre_order_weight'],
            'mes_lis_shi_lin_amo_item_net_price_unit_price'=>$items['mes_lis_shi_lin_amo_item_net_price_unit_price'],
            'mes_lis_shi_lin_amo_item_net_price'=>$items['mes_lis_shi_lin_amo_item_net_price'],
            'mes_lis_shi_lin_qua_sto_reason_code'=>$items['mes_lis_shi_lin_qua_sto_reason_code']
        );
        data_shipment_item::where('mes_lis_shi_lin_ite_supplier_item_code', $items['mes_lis_shi_lin_ite_supplier_item_code'])->update($updateArry);
        Log::debug(__METHOD__.':end---');
        return response()->json(['success' => '1']);
    }
    public function get_all_shipment_item_by_search(Request $request)
    {
        Log::debug(__METHOD__.':start---');
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
        //$url_q = $request->url_q;

        // $major_category = $request->major_category;
        // $delivery_service_code = $request->delivery_service_code;
        // $delivery_date = $request->delivery_date;
        // $temperature_code = $request->temperature_code;
        // $mes_lis_shi_lin_ite_order_item_code = $request->order_item_code;

        $result = data_shipment_item::select(
            'data_shipment_items.mes_lis_shi_lin_ite_order_item_code',
            'data_shipment_items.mes_lis_shi_lin_ite_gtin',
            'data_shipment_items.mes_lis_shi_lin_ite_name',
            'data_shipment_items.mes_lis_shi_lin_ite_ite_spec',
            'data_shipment_items.mes_lis_shi_lin_fre_field_name'
        )
            ->leftJoin('data_shipment_vouchers as dsv', 'dsv.data_shipment_voucher_id', '=', 'data_shipment_items.data_shipment_voucher_id')
            ->join('data_shipments as ds', 'ds.data_shipment_id', '=', 'dsv.data_shipment_id')
            ->where('ds.data_order_id', $data_order_id)
            ->where('dsv.mes_lis_shi_log_del_delivery_service_code', $mes_lis_shi_log_del_delivery_service_code)
             ->where('dsv.mes_lis_shi_par_sel_code', $mes_lis_shi_par_sel_code)
            // ->where('dsv.mes_lis_shi_par_sel_name', $mes_lis_shi_par_sel_name)
            ->where('dsv.mes_lis_shi_tra_dat_delivery_date_to_receiver', $mes_lis_shi_tra_dat_delivery_date_to_receiver)
            ->where('dsv.mes_lis_shi_tra_goo_major_category', $mes_lis_shi_tra_goo_major_category)
            ->where('dsv.mes_lis_shi_tra_ins_temperature_code', $mes_lis_shi_tra_ins_temperature_code);
        // ->where('dsv.mes_lis_shi_tra_trade_number', $mes_lis_shi_tra_trade_number);
        // ->where('ds.receive_datetime', $receive_datetime);

        if ($mes_lis_shi_lin_ite_gtin) {
            $result=$result->where('data_shipment_items.mes_lis_shi_lin_ite_gtin', $mes_lis_shi_lin_ite_gtin);
        }
        if ($mes_lis_shi_lin_ite_order_item_code) {
            $result=$result->where('data_shipment_items.mes_lis_shi_lin_ite_order_item_code', $mes_lis_shi_lin_ite_order_item_code);
        }
        $result=$result->whereNull('dsv.decision_datetime');

        // $result=$result->groupBy('dsv.mes_lis_shi_tra_trade_number');
        $result=$result->groupBy('data_shipment_items.mes_lis_shi_lin_ite_order_item_code');
        $result=$result->orderBy('data_shipment_items.'.$sort_by, $sort_type)
        ->paginate($per_page);

        Log::debug(__METHOD__.':end---');
        return response()->json(['order_item_lists' => $result]);
    }
    public function decessionData(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $dateTime = null;
        $date_null = $request->date_null;
        if (!$date_null) {
            $dateTime = date('Y-m-d H:i:s');
        }
        $data_shipment_voucher_ids = $request->update_id;
        if ($data_shipment_voucher_ids) {
            foreach ($data_shipment_voucher_ids as $id) {
                if (!$date_null) {
                    data_shipment_voucher::where('data_shipment_voucher_id', $id)
                    ->update([
                        'decision_datetime' => $dateTime,
                        // 'mes_lis_shi_tra_dat_transfer_of_ownership_date' => $dateTime
                        ]);
                } else {
                    data_shipment_voucher::where('data_shipment_voucher_id', $id)
                    ->whereNull('send_datetime')
                    ->update([
                        'decision_datetime' => null,
                        // 'mes_lis_shi_tra_dat_transfer_of_ownership_date' => null
                        ]);
                }
            }
        }
        Log::debug(__METHOD__.':end---');
        return response()->json(['success' => '1','status'=>1]);
    }
}
