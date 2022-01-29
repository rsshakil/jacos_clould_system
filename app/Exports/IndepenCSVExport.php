<?php

namespace App\Exports;

use App\Models\BYR\byr_order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Http\Controllers\API\AllUsedFunction;
use Maatwebsite\Excel\Excel;

class IndepenCSVExport implements FromQuery,WithHeadings,WithMapping,ShouldAutoSize
{
    use Exportable;
    private $byr_order_id;
    private $all_functions;
    public function __construct($byr_order_id)
    {
        $this->byr_order_id = $byr_order_id;
        $this->all_functions = new AllUsedFunction();
    }
    public function headings(): array
    {
        return [
            'Super Code','Buyer Name Kana',
            'Receiver Code', 'Receiver Name Kana', 'Voucher Number','Constant','Partner Code',
            'Saler Name Kana','Category Code','Voucher Category',
            'Order Date', 'Expected Delivery Date',
            'Delivery Service Code','Revised Delivery Date',
            'List Number', 'Jan','Item Name Kana','Size',
            'Spec Kana','Color','Tax Rate','Order Inputs','Inputs',
            'Order Quantity','Cost Price','Total Cost Price',
            'Selling Price','Total Selling Price',
            
        ];
    }
    public function query()
    {
        $all_indepen_data=byr_order::select(
            'byr_orders.byr_name_kana',
            'byr_orders.partner_code',
            'byr_orders.slr_name_kana',

            'byr_order_vouchers.receiver_code',
            'byr_order_vouchers.receiver_name_kana',
            'byr_order_vouchers.voucher_number',
            'byr_order_vouchers.category_code',
            'byr_order_vouchers.voucher_category',
            'byr_order_vouchers.order_date',
            'byr_order_vouchers.expected_delivery_date',
            'byr_order_vouchers.delivery_service_code',
            'byr_order_vouchers.tax_rate',
            'byr_order_vouchers.total_cost_price',
            'byr_order_vouchers.total_selling_price',

            'byr_order_items.list_number',
            'byr_order_items.jan',
            'byr_order_items.item_name_kana',
            'byr_order_items.size',
            'byr_order_items.spec_kana',
            'byr_order_items.color',
            'byr_order_items.order_inputs',
            'byr_order_items.inputs',
            'byr_order_items.order_quantity',
            'byr_order_items.cost_price',
            'byr_order_items.selling_price',
            
            'byr_buyers.super_code',

            'byr_shipment_vouchers.revised_delivery_date',
        )
        ->join('byr_order_vouchers','byr_order_vouchers.byr_order_id','=','byr_orders.byr_order_id')
        ->join('byr_order_items','byr_order_items.byr_order_voucher_id','=','byr_order_vouchers.byr_order_voucher_id')
        ->join('cmn_connects','byr_orders.cmn_connect_id','=','cmn_connects.cmn_connect_id')
        ->join('byr_buyers','byr_buyers.byr_buyer_id','=','cmn_connects.byr_buyer_id')
        ->join('byr_shipment_vouchers','byr_shipment_vouchers.byr_order_voucher_id','=','byr_order_vouchers.byr_order_voucher_id')
        ->where('byr_orders.byr_order_id', $this->byr_order_id)
        ->orderBy("byr_orders.byr_order_id");
        return $all_indepen_data;
    }
    public function map($all_indepen_data): array
    {
        return [
            $all_indepen_data->super_code,
            $this->all_functions->mb_str_pad($all_indepen_data->byr_name_kana, 20),
            str_pad($all_indepen_data->receiver_code, 6, '0', STR_PAD_LEFT),
            $all_indepen_data->receiver_name_kana,
            $all_indepen_data->voucher_number,
            '00',
            $all_indepen_data->partner_code,
            $this->all_functions->mb_str_pad($all_indepen_data->slr_name_kana, 20),
            $all_indepen_data->category_code,
            $all_indepen_data->voucher_category,
            date('ymd', strtotime($all_indepen_data->order_date)),
            date('ymd', strtotime($all_indepen_data->expected_delivery_date)),
            str_pad($all_indepen_data->delivery_service_code, 3, '0', STR_PAD_LEFT),
            date('ymd', strtotime($all_indepen_data->revised_delivery_date)),
            str_pad($all_indepen_data->list_number, 2, '0', STR_PAD_LEFT),
            str_pad($all_indepen_data->jan, 13, '0', STR_PAD_LEFT),
            $this->all_functions->mb_str_pad($all_indepen_data->item_name_kana, 25),
            str_pad($all_indepen_data->size, 5, '0', STR_PAD_LEFT),
            str_pad($all_indepen_data->spec_kana, 5, '0', STR_PAD_LEFT),
            str_pad($all_indepen_data->color, 7, '0', STR_PAD_LEFT),
            $all_indepen_data->tax_rate,
            $all_indepen_data->order_inputs=='ケース'?'CS':($all_indepen_data->order_inputs=='ボール'?'BL':str_repeat(" ",2)),
            str_pad($all_indepen_data->inputs, 7, '0', STR_PAD_LEFT),
            str_pad(str_replace(".", "", $all_indepen_data->order_quantity), 6, '0', STR_PAD_LEFT),
            $all_indepen_data->cost_price,
            $all_indepen_data->total_cost_price,
            $all_indepen_data->selling_price,
            $all_indepen_data->total_selling_price,
        ];
    }
}
