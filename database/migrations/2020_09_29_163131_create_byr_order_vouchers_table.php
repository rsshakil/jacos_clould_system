<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrOrderVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_order_vouchers', function (Blueprint $table) {
            $table->increments('byr_order_voucher_id')->unsigned()->comment('byr order voucher id');
            $table->integer('byr_order_id')->unsigned()->comment('byr order id');
            $table->string('route_code', 50)->comment('route code')->nullable();
            $table->string('ship_code', 50)->comment('shop_code')->nullable();
            $table->string('ship_name', 50)->comment('shop_name')->nullable();
            $table->string('ship_name_kana', 50)->comment('shop_name_kana')->nullable();
            $table->string('receiver_code', 50)->comment('receiver_code')->nullable();
            $table->string('receiver_name', 50)->comment('receiver_name')->nullable();
            $table->string('receiver_name_kana', 50)->comment('receiver_name_kana')->nullable();
            $table->integer('category_code')->unsigned()->default('1')->comment('category code');
            $table->string('voucher_category', 10)->comment('voucher category')->nullable();
            $table->string('sale_category', 50)->comment('sale category')->nullable();
            $table->string('tax_type', 50)->comment('sale category')->nullable();
            $table->string('temperature', 50)->comment('temperature')->nullable();
            $table->string('voucher_number', 20)->comment('voucher number')->nullable();
            $table->smallInteger('delivery_service_code')->unsigned()->default('1')->comment('delivery service code');
            $table->tinyInteger('tax_rate')->unsigned()->default('0')->comment('tax rate');

            $table->date('order_date')->nullable()->comment('order date');
            $table->date('expected_delivery_date')->comment('expected delivery date')->nullable();
            $table->integer('total_cost_price')->unsigned()->default('0')->comment('total cost price');
            $table->integer('total_selling_price')->unsigned()->default('0')->comment('total selling price');
            $table->longText('other_info')->comment('other info')->nullable();
            $table->dateTime('checked_date')->comment('checked date')->nullable();
            $table->dateTime('print_date')->comment('print_date')->nullable();

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
        Schema::dropIfExists('byr_order_vouchers');
    }
}
