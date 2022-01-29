<?php

namespace App\Scenarios;
use App\Scenarios\Common;
use App\Models\BYR\byr_order;
use App\Http\Controllers\API\AllUsedFunction;

class indepen_fixed_length_generate
{
    private $all_functions;
    private $common_class_obj;
    public function __construct()
    {
        $this->common_class_obj = new Common();
        $this->all_functions = new AllUsedFunction();
    }

    public function exec($request,$sc)
    {
        $byr_order_id=$request->order_id;
        $all_indepen_data=byr_order::select(
            'byr_orders.byr_name',
            'byr_orders.byr_name_kana',
            'byr_order_vouchers.voucher_number',
            'byr_order_vouchers.ship_code',
            'byr_order_vouchers.category_code',
            'byr_order_vouchers.voucher_category',
            'byr_order_vouchers.receiver_code',
            'byr_order_vouchers.order_date',
            'byr_order_vouchers.expected_delivery_date',
            'byr_order_vouchers.receiver_name_kana',
            'byr_order_vouchers.sale_category',
            'byr_order_vouchers.delivery_service_code',
            'byr_order_vouchers.total_cost_price',
            'byr_order_vouchers.total_selling_price',
            'byr_order_items.list_number',
            'byr_order_items.jan',
            'byr_order_items.item_name_kana',
            'byr_order_items.spec_kana',
            'byr_order_items.color',
            'byr_order_items.size',
            'byr_order_items.inputs',
            'byr_order_items.order_unit_quantity',
            'byr_order_items.order_inputs',
            'byr_order_items.order_quantity',
            'byr_order_items.cost_unit_price',
            'byr_order_items.selling_unit_price',
            'byr_order_items.cost_price',
            'byr_order_items.selling_price',
        )
        ->join('byr_order_vouchers','byr_order_vouchers.byr_order_id','=','byr_orders.byr_order_id')
        ->join('byr_order_items','byr_order_items.byr_order_voucher_id','=','byr_order_vouchers.byr_order_voucher_id')
        ->where('byr_orders.byr_order_id', $byr_order_id)->get();

        $data=[];
        foreach ($all_indepen_data as $key => $val) {
            \Log::debug($key.':'.$val);
            // file head
            $file_head = 'H'; //default value wich length is 1
            $file_head .= str_repeat("0",6); //0 added for 6 times which length is 6
            $file_head.= $val['voucher_number']; // length is 7
            $file_head .= str_pad($val['ship_code'], 6, '0', STR_PAD_LEFT); //0 added before string until length is 6
            $file_head .= str_pad($val['category_code'], 2, '0', STR_PAD_LEFT); //0 added before string until length is 2
            $file_head.= $val['voucher_category']; // length is 2
            $file_head .= str_pad($val['receiver_code'], 6, '0', STR_PAD_LEFT); //0 added before string until length is 6
            $file_head.= date('ymd', strtotime($val['order_date'])); //datetime to date string wich length is 6
            $file_head.= date('ymd', strtotime($val['expected_delivery_date'])); //datetime to date string wich length is 6
            $file_head .= $this->all_functions->mb_str_pad($val['byr_name_kana'], 20); //space padding added after string until length is 20
            $file_head .= $this->all_functions->mb_str_pad($val['receiver_name_kana'], 20); //space padding added after string until length is 20
            $file_head .= $this->all_functions->mb_str_pad($val['slr_name_kana'], 20); //space padding added after string until length is 20
            $file_head .= str_repeat(" ",17); //Seventeen space added which length is 17
            $file_head .= $this->all_functions->mb_str_pad($val['sale_category'], 2); //space padding added after string until length is 2
            $file_head .= str_pad($val['delivery_service_code'], 3, '0', STR_PAD_LEFT); //0 added before string until length is 3
            $file_head .= str_repeat(" ",4); //Seventeen space added which length is 4
            // Total 128 Character
            // vouchers
            $order_inputs=$val['order_inputs']=='ケース'?'CS':($val['order_inputs']=='ボール'?'BL':str_repeat(" ",2));
            $voucher_head = 'D'; //default value wich length is 1
            $voucher_head .= str_repeat("0",6); //0 added for 6 times which length is 6
            $voucher_head.= $val['voucher_number']; // length is 7
            $voucher_head .= str_pad($val['list_number'], 2, '0', STR_PAD_LEFT); //0 added before string until length is 8
            $voucher_head .= str_pad($val['jan'], 13, '0', STR_PAD_LEFT); //0 added before string until length is 8
            $voucher_head .= str_repeat(" ",15); //Seventeen space added which length is 15
            $voucher_head .= $this->all_functions->mb_str_pad($val['item_name_kana'], 25); //space padding added after string until length is 25
            $voucher_head .= str_pad($val['spec_kana'], 5, '0', STR_PAD_LEFT); //0 added before string until length is 5
            $voucher_head .= str_repeat(" ",5); //Seventeen space added which length is 5
            $voucher_head .= str_pad($val['color'], 7, '0', STR_PAD_LEFT); //0 added before string until length is 7
            $voucher_head .= str_pad($val['size'], 5, '0', STR_PAD_LEFT); //0 added before string until length is 5
            $voucher_head .= str_pad($val['inputs'], 7, '0', STR_PAD_LEFT); //0 added before string until length is 7
            $voucher_head .= str_pad(str_replace(".", "", $val['order_unit_quantity']), 5, '0', STR_PAD_LEFT); //Remove dot from decimal value and added 0 before string until the length is 5
            $voucher_head.= $order_inputs; // length is 2 //may be problem
            $voucher_head .= str_pad(str_replace(".", "", $val['order_quantity']), 6, '0', STR_PAD_LEFT); //Remove dot from decimal value and added 0 before string until the length is 6
            $voucher_head .= str_pad(str_replace(".", "", $val['cost_unit_price']), 8, '0', STR_PAD_LEFT); //Remove dot from decimal value and added 0 before string until the length is 8
            $voucher_head .= str_pad($val['selling_unit_price'], 6, '0', STR_PAD_LEFT); //0 added before string until length is 6
            $voucher_head .= str_repeat(" ",3); //Seventeen space added which length is 3
            // Total 128 Character

            $data[$file_head][] = $voucher_head;
        }
        // return $data;
        $string_data="";
        for ($i=0; $i < count($data); $i++) {
            $step0=array_keys($data)[$i];
            $string_data.=$step0; //If New line need please add .'\n' after this line

            $step0_data_array=$data[$step0];
            $step0_data_count=count($step0_data_array);

            for ($j=0; $j < $step0_data_count; $j++) {
                $step1=array_keys($step0_data_array)[$j];
                $string_data.=$step0_data_array[$step1]; //If New line need please add .'\n' after this line
            }
        }
        $txt_file_name=date('y-m-d').'_Text_File_'.time().".txt";
        $string_data=$this->all_functions->convert_from_utf8_to_sjis__recursively($string_data);
        $string_data=$this->common_class_obj->sjis_2_ebcdic(null,$string_data);

        if ($string_data!=null) {
            \File::put(storage_path(config('const.INDEPEN_FILE_PATH').$txt_file_name), $string_data);
            return response()->json(['message'=>"File has been created",'url'=>\Config('app.url').'storage/'.config('const.INDEPEN_FILE_PATH').$txt_file_name]);
        }else{
            return response()->json(['message'=>"No file data found"]);
        }
    }



}
