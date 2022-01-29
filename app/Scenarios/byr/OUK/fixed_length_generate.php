<?php

namespace App\Scenarios\byr\OUK;

use App\Scenarios\ScenarioBase;

use App\Scenarios\Common;
use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\API\DATA\Data_Controller;
use Illuminate\Support\Facades\Log;

class fixed_length_generate extends ScenarioBase
{
    private $all_functions;
    private $common_class_obj;
    public function __construct()
    {
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
        $this->all_functions->folder_create(config('const.JCA_FILE_PATH'));
        $this->all_functions->folder_create(config('const.LV3_SHIPMENT_JCA_PATH'));
    }

    public function exec($request, $sc)
    {
        Log::debug(__METHOD__.':start---');
        if ($request->has('file_save_path')) {
            $file_save_path=$request->file_save_path;
        }else{
            $file_save_path=config('const.JCA_FILE_PATH');
        }
        if ($request->has('order_data')) {
            $order_data=$request->order_data;
        }else{
            $shipment_query= Data_Controller::get_shipment_data($request);
            // $csv_data_count = $shipment_query['total_data'];
            $order_data = $shipment_query['shipment_data'];
        }
        $data=[];
        foreach ($order_data as $key => $val) {
            // file head

            // 取引先コード取得
            // 桁あふれ桁少ないのを対応
            $tori_code = substr(str_pad($val['mes_lis_shi_par_sel_code'], 6, '0', STR_PAD_LEFT), -6);

            $file_head = 'A00'; //default value wich length is 3
            $file_head.= date('ymdHis', strtotime($val['sta_doc_creation_date_and_time'])); //datetime to date time string wich length is 6+6
            $file_head.= date('ymd', strtotime($val['sta_doc_creation_date_and_time'])); //datetime to date string wich length is 6;  //length is 6
            $file_head.= '82105578'; //default value wich length is 8
            $file_head.= $tori_code."HI"; //add HI with sel code wich length is 8
            $file_head.= '82105578'; //default value wich length is 8
            $file_head.= '128'; //default value wich length is 3
            $file_head .= str_repeat("0", 6); //0 added for 6 times which length is 6
            $file_head .= str_repeat("0", 5); //0 added for 5 times which length is 5
            $file_head .= str_repeat(" ", 15); //Fifteen space added which length is 15
            $file_head .= str_repeat(" ", 54); //Fifty four space added which length is 54
            // Total 128 Character
            // echo($file_head.PHP_EOL);

            // vouchers
            $voucher_head = 'B010'; //default value wich length is 4
            $voucher_head .= str_pad($val['mes_lis_shi_tra_trade_number'], 8, '0', STR_PAD_LEFT); //0 added before string until length is 8
            $voucher_head .= '000'; //0 added for 3 times which length is 3
            $voucher_head .= str_pad($val['mes_lis_shi_par_rec_code'], 6, '0', STR_PAD_LEFT); //0 added before string until length is 6
            $voucher_head .= str_pad($val['mes_lis_shi_tra_goo_major_category'], 4, '0', STR_PAD_LEFT); //0 added before string until length is 4
            $voucher_head .='50'; //default value wich length is 2
            $voucher_head .= date('ymd', strtotime($val['mes_lis_shi_tra_dat_order_date'])); //datetime to date string wich length is 6;  //length is 6
            $voucher_head .= date('ymd', strtotime($val['mes_lis_shi_tra_dat_delivery_date_to_receiver'])); //datetime to date string wich length is 6
            $voucher_head .= $tori_code; //0 added before string until length is 6
            $voucher_head .= '00'; //default value wich length is 2
            $voucher_head .= substr($val['mes_lis_shi_log_del_delivery_service_code'], -1); //substring from service_code (right) which length is 1
            $voucher_head .= $this->all_functions->mb_str_pad($val['mes_lis_shi_par_rec_name_sbcs'], 6); //space padding added after string until length is 6
            $voucher_head .= str_repeat(" ", 6); //6 space padding added with vouche string until length is 6
            $voucher_head .= $this->all_functions->mb_str_pad($val['mes_lis_shi_par_sel_name_sbcs'], 22); //space padding added after string until length is 22
            $voucher_head .= (($val['mes_lis_shi_tra_ins_goods_classification_code']=='01')?0: (($val['mes_lis_shi_tra_ins_goods_classification_code']=='03')?1:' ')); //if classification_code=01 then 0 else 03 then 1 else space
            $voucher_head .= ($val['mes_lis_shi_log_del_route_code']=='02'?1:' '); //space padding added after string until length is 1 if route code!=02 else 1
            $voucher_head .= str_pad($val['mes_lis_shi_par_shi_code'], 6, '0', STR_PAD_LEFT); //0 added before string until length is 6
            $voucher_head .= $this->all_functions->mb_str_pad($val['mes_lis_shi_par_shi_name_sbcs'], 22); //space padding added after string until length is 22
            $voucher_head .= str_repeat(" ", 16); //Sixteen space added which length is 16
            // Total 128 Character

            // items
            $items = 'D01'; //default value wich length is 3
            $items.= str_pad($val['mes_lis_shi_lin_lin_line_number'], 2, '0', STR_PAD_LEFT); //0 added before the string until length is 2
            $items.= $this->all_functions->mb_str_pad($val['mes_lis_shi_lin_ite_order_item_code'], 13); //space padding added after string until length is 13
            $items.= str_pad($val['mes_lis_shi_lin_qua_unit_multiple'], 4, '0', STR_PAD_LEFT); //0 added before the string until length is 4
            $items.= str_pad($val['mes_lis_shi_lin_qua_ord_num_of_order_units'], 4, '0', STR_PAD_LEFT); //0 added before the string until length is 4
            $items.= str_repeat(" ", 3); //Space added until length is 3
            $items.= str_pad(str_replace(".", "", $val['mes_lis_shi_lin_qua_ord_quantity']), 6, '0', STR_PAD_LEFT); //Remove dot from decimal value and added 0 before string until the length is 6
            $items.= str_repeat('0', 1); //0 added until length is 1
            $items.= str_pad(str_replace(".", "", $val['mes_lis_shi_lin_amo_item_net_price_unit_price']), 8, '0', STR_PAD_LEFT); //Remove dot from decimal value and added 0 before string until the length is 8
            $items.= str_repeat('0', 1); //0 added until length is 1
            $items.= str_pad($val['mes_lis_shi_lin_amo_item_selling_price_unit_price'], 6, '0', STR_PAD_LEFT); //0 added before the string until the length is 6
            $items.= str_repeat('0', 1); //0 added until length is 1
            $items.= str_pad($val['mes_lis_shi_lin_amo_item_net_price'], 9, '0', STR_PAD_LEFT); //0 added before the string until the length is 9
            $items.= str_repeat('0', 1); //0 added until length is 1
            $items.= str_pad($val['mes_lis_shi_lin_amo_item_selling_price'], 9, '0', STR_PAD_LEFT); //0 added before the string until the length is 9
            $items.= str_repeat(" ", 9); //Space added until length is 9
            $items.= $this->all_functions->mb_str_pad($val['mes_lis_shi_lin_ite_name_sbcs'], 25); //space padding added after string until length is 25
            $items.= str_repeat(" ", 23); //Space added until length is 23
            // Total 128 Character
            // echo($val['mes_lis_ord_lin_amo_item_net_price_unit_price'].PHP_EOL);

            $data[$file_head][$voucher_head][] = $items;
        }
        $string_data="";
        if (!empty($data)) {
            for ($i=0; $i < count($data); $i++) {
                $step0=array_keys($data)[$i];
                $string_data.=$step0; //If New line need please add .'\n' after this line
                $step0_data_array=$data[$step0];

                $step0_data_count=count($step0_data_array);

                for ($j=0; $j < $step0_data_count; $j++) {
                    $step1=array_keys($step0_data_array)[$j];
                    $string_data.=$step1; //If New line need please add .'\n' after this line

                    $step1_data_array=$step0_data_array[$step1];
                    $step1_data_count=count($step1_data_array);

                    for ($k=0; $k <$step1_data_count ; $k++) {
                        $step2=array_keys($step1_data_array)[$k];
                        // $string_data.=$step2."\n";
                        $string_data.=$step1_data_array[$step2]; //If New line need please add .'\n' after this line
                    }
                }
            }
        }
        $txt_file_name = $request->file_name?$request->file_name:(date('y-m-d').'_Text_File_'.time().".txt");
        $string_data=$this->all_functions->convert_from_utf8_to_sjis__recursively($string_data);

        Log::debug(__METHOD__.':end---');
        if ($string_data!=null) {
            \File::put(storage_path($file_save_path.'/'.$txt_file_name), $string_data);
            return [
                'status'=>1,
                'message'=>"File has been created",
                ];
        } else {
            return [
                'status'=>0,
                'message'=>"No file data found",
                ];
            // return response()->json(['status'=>0,'message'=>"No file data found"]);
        }
    }
}
