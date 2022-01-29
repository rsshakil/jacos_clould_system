<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrShipmentVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_shipment_vouchers', function (Blueprint $table) {
            $table->increments('byr_shipment_voucher_id')->unsigned()->comment('byr shipment voucher id');
            $table->integer('byr_shipment_id')->unsigned()->comment('cmn connect id');
            $table->integer('byr_order_voucher_id')->unsigned()->comment('byr order voucher id');
            $table->integer('revised_total_cost_price')->unsigned()->default('0')->comment('revised total cost price');
           $table->date('revised_delivery_date')->nullable();
            $table->dateTime('confirm_date')->comment('checked date')->nullable();
            $table->dateTime('print_out_date')->comment('print_date')->nullable();
            $table->dateTime('send_date')->comment('send_date')->nullable();
            $table->dateTime('last_updated_date')->comment('send_date')->nullable();
            $table->enum('ship_status', ['完納','一部完納','未納'])->default('完納')->comment('order status');
            $table->integer('update_by')->unsigned()->default('0')->comment('update_by');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('Time of Update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('byr_shipment_vouchers');
    }
}
