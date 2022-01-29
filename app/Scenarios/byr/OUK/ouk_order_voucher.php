<?php

namespace App\Scenarios;

use Illuminate\Database\Eloquent\Model;
use App\Models\BYR\byr_order_voucher;
use App\Models\BYR\byr_order;
// use App\Models\BYR\byr_shop;

class ouk_order_voucher
{
    //
    public function exec($request,$sc)
    {
        $byr_order_id=$request->byr_order_id;
        // $can_info_query=Byr_order_detail::select('byr_order_details.item_name','byr_order_details.jan','byr_order_details.color','byr_order_details.voucher_number')
        // $can_info_query=Byr_order_detail::select('byr_order_details.*')
        // ->join('byr_orders','byr_order_details.byr_order_id','=','byr_orders.byr_order_id')
        // ->where('byr_orders.byr_order_id',$byr_order_id)
        // ->get();
        // $can_info_query=byr_order_detail::select('byr_order_details.*','byr_shops.shop_name_kana','byr_shops.shop_code',
        // 'cmn_connects.partner_code','byr_shipment_details.confirm_quantity','byr_shipment_details.revised_cost_price',
        // 'byr_shipment_details.revised_selling_price')
        // ->join('byr_orders','byr_order_details.byr_order_id','=','byr_orders.byr_order_id')
        // ->join('cmn_connects','cmn_connects.cmn_connect_id','=','byr_orders.cmn_connect_id')
        // ->join('byr_shops','byr_shops.byr_shop_id','=','byr_order_details.byr_shop_id')
        // ->join('byr_shipment_details','byr_shipment_details.byr_order_detail_id','=','byr_order_details.byr_order_detail_id')
        // ->where('byr_orders.byr_order_id',$byr_order_id)
        // ->get();
        $can_info_query=byr_order_voucher::select('byr_order_vouchers.*','byr_order_items.*',
        'cmn_connects.partner_code','byr_shipment_items.confirm_quantity','byr_shipment_items.revised_cost_price',
        'byr_shipment_items.revised_selling_price')
        ->join('byr_order_items','byr_order_items.byr_order_voucher_id','=','byr_order_vouchers.byr_order_voucher_id')
        ->join('byr_orders','byr_orders.byr_order_id','=','byr_order_vouchers.byr_order_id')
        ->join('cmn_connects','cmn_connects.cmn_connect_id','=','byr_orders.cmn_connect_id')
        // ->join('byr_shops','byr_shops.byr_shop_id','=','byr_order_details.byr_shop_id')
        ->join('byr_shipment_items','byr_shipment_items.byr_order_item_id','=','byr_order_items.byr_order_item_id')
        ->where('byr_order_vouchers.byr_order_id',$byr_order_id)
        ->get();
        // return $can_info_query;
        $collection = collect($can_info_query);
        $grouped = $collection->groupBy('voucher_number');
        $can_info=$grouped->toArray();

        $can_info_array=array();
        $total_order_qty=0;
        $total_selling_price=0;
        $total_cost_price=0;
        $total_confirm_quantity=0;
        foreach ($can_info as $key => $single_info) {
            foreach ($single_info as $key1 => $sum_val_array) {
                $total_order_qty+=$sum_val_array['order_unit_quantity'];
                $total_selling_price+=$sum_val_array['selling_price'];
                $total_cost_price+=$sum_val_array['cost_price'];
                if ($sum_val_array['order_unit_quantity']!=$sum_val_array['confirm_quantity']) {
                    $total_confirm_quantity+=$sum_val_array['confirm_quantity'];
                }
                // intval()order_unit_quantity
            }
            foreach ($single_info as $key2 => $nested_value) {
                $nested_value['total_order_qty']=$total_order_qty;
                $nested_value['total_selling_price']=$total_selling_price;
                $nested_value['total_cost_price']=$total_cost_price;
                $nested_value['total_confirm_quantity']=$total_confirm_quantity;
                $nested_value['order_unit_quantity']=intval($nested_value['order_unit_quantity']);
                $nested_value['cost_unit_price']=intval($nested_value['cost_unit_price']);
                if ($nested_value['order_unit_quantity']!=$nested_value['confirm_quantity']) {
                    $nested_value['confirm_quantity']=intval($nested_value['confirm_quantity']);
                    if ($nested_value['confirm_quantity']==0) {
                        $nested_value['confirm_quantity']="";
                    }
                }else{
                    $nested_value['confirm_quantity']="";
                }
                $nested_value['center_code']="";
                $nested_value['center_name']="";
                $other_info=\json_decode($nested_value['other_info']);
                if (!empty($other_info)) {
                    if ($other_info->center_flg==1) {
                        $nested_value['center_code']=$other_info->center_code;
                        $nested_value['center_name']=$other_info->center_name;
                    }
                }
                $nested_value['other_info']=$other_info;
                $single_info[$key2]=$nested_value;
            }
            $can_info_array[]=$single_info;
            $total_order_qty=0;
            $total_selling_price=0;
            $total_cost_price=0;
            $total_confirm_quantity=0;
        }
        return $can_info_array;
    }

    
}
