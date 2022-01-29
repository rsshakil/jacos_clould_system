<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateByrShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_shipment_items', function (Blueprint $table) {
            $table->increments('byr_shipment_item_id')->unsigned()->comment('byr shipment item id');
            $table->integer('byr_shipment_voucher_id')->unsigned()->comment('cmn shipment voucher id');
            $table->integer('byr_order_item_id')->unsigned()->comment('byr order detail item id');
            $table->decimal('order_quantity', 10, 1)->default('0.0')->comment('order quantity');
            $table->decimal('confirm_quantity', 10, 1)->default('0.0')->comment('confirm quanity');
            $table->decimal('confirm_unit_quantity', 10, 1)->default('0.0')->comment('confirm quanity');
            $table->decimal('delivery_quantity', 10, 1)->default('0.0')->comment('delivery quantity');
            $table->decimal('stock_out_quantity', 10, 1)->default('0.0')->comment('stock out quantity');
            $table->decimal('damage_quantity', 10, 1)->default('0.0')->comment('damage quantity');
            $table->string('lack_reason', 100)->comment('lack reason')->nullable();
            $table->date('revised_delivery_date')->nullable();
            $table->decimal('revised_cost_unit_price', 10, 2)->default('0.00')->comment('revised cost unit price');
            $table->integer('revised_cost_price')->unsigned()->default('0')->comment('revised cost price');
            $table->mediumInteger('revised_selling_unit_price')->unsigned()->default('0')->comment('revised selling unit price');
            $table->integer('revised_selling_price')->unsigned()->default('0')->comment('revised selling price');
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
        Schema::dropIfExists('byr_shipment_items');
    }
}
