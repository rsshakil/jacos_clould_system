<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_order_items', function (Blueprint $table) {
            $table->increments('byr_order_item_id')->unsigned()->comment('byr order item id');
            $table->integer('byr_order_voucher_id')->unsigned()->comment('byr order id');
             $table->smallInteger('list_number')->unsigned()->default('0')->comment('list number');
            $table->enum('status', ['未確定', '確定済み', '未出荷', '出荷中', '出荷済み'])->default('未確定')->comment('order status');
            $table->bigInteger('jan')->unsigned()->default('0')->comment('jan code');
            $table->string('item_name', 200)->comment('item name')->nullable();
            $table->string('item_name_kana', 400)->comment('item name kana')->nullable();
            $table->string('spac', 200)->comment('spac')->nullable();
            $table->string('spec_kana', 400)->comment('spec kana')->nullable();
            $table->integer('inputs')->unsigned()->default('1')->comment('inputs');
            $table->string('size', 50)->comment('size')->nullable();
            $table->string('color', 50)->comment('color')->nullable();
            $table->string('weight', 50)->comment('color')->nullable();
            $table->enum('order_inputs', ['ケース', 'ボール', 'バラ'])->default('ケース')->comment('order lot inputs');
            $table->decimal('order_quantity', 10, 1)->default('0.0')->comment('order quantity');
            $table->decimal('order_unit_quantity', 10, 1)->default('0.0')->comment('order unit quantity');


            $table->decimal('cost_unit_price', 10, 2)->default('0.00')->comment('cost unit price');
            $table->integer('cost_price')->unsigned()->default('0')->comment('cost price');
            $table->mediumInteger('selling_unit_price')->unsigned()->default('0')->comment('selling unit price');
            $table->integer('selling_price')->unsigned()->default('0')->comment('selling price');
            $table->mediumInteger('tax_price')->unsigned()->default('0')->comment('selling unit price');
            $table->longText('other_info')->comment('other info')->nullable();
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
        Schema::dropIfExists('byr_order_items');
    }
}
