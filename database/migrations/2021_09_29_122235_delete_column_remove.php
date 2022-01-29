<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteColumnRemove extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('bms_orders', 'deleted')){
            Schema::table('bms_orders', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_invoices', 'deleted')){
            Schema::table('bms_invoices', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_receives', 'deleted')){
            Schema::table('bms_receives', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_returns', 'deleted')){
            Schema::table('bms_returns', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_shipments', 'deleted')){
            Schema::table('bms_shipments', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_payments', 'deleted')){
            Schema::table('bms_payments', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('bms_corrected_receives', 'deleted')){
            Schema::table('bms_corrected_receives', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('cmn_categories', 'is_deleted')){
            Schema::table('cmn_categories', function (Blueprint $table) {
                $table->dropColumn('is_deleted');
            });
        }
        if (Schema::hasColumn('data_orders', 'deleted')){
            Schema::table('data_orders', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_order_vouchers', 'deleted')){
            Schema::table('data_order_vouchers', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_order_items', 'deleted')){
            Schema::table('data_order_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_shipments', 'deleted')){
            Schema::table('data_shipments', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_shipment_items', 'deleted')){
            Schema::table('data_shipment_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_shipment_item_details', 'deleted')){
            Schema::table('data_shipment_item_details', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_shipment_vouchers', 'deleted')){
            Schema::table('data_shipment_vouchers', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('cmn_items', 'deleted')){
            Schema::table('cmn_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_receives', 'deleted')){
            Schema::table('data_receives', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_receive_vouchers', 'deleted')){
            Schema::table('data_receive_vouchers', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_receive_items', 'deleted')){
            Schema::table('data_receive_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_corrected_receives', 'deleted')){
            Schema::table('data_corrected_receives', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_corrected_receive_vouchers', 'deleted')){
            Schema::table('data_corrected_receive_vouchers', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_corrected_receive_items', 'deleted')){
            Schema::table('data_corrected_receive_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_returns', 'deleted')){
            Schema::table('data_returns', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_return_vouchers', 'deleted')){
            Schema::table('data_return_vouchers', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_return_items', 'deleted')){
            Schema::table('data_return_items', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_invoices', 'deleted')){
            Schema::table('data_invoices', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_invoice_pays', 'deleted')){
            Schema::table('data_invoice_pays', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_invoice_pay_details', 'deleted')){
            Schema::table('data_invoice_pay_details', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_payments', 'deleted')){
            Schema::table('data_payments', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_payment_pays', 'deleted')){
            Schema::table('data_payment_pays', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
        if (Schema::hasColumn('data_payment_pay_details', 'deleted')){
            Schema::table('data_payment_pay_details', function (Blueprint $table) {
                $table->dropColumn('deleted');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
