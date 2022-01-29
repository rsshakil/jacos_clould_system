<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_invoice_details', function (Blueprint $table) {
            $table->increments('byr_invoice_detail_id')->unsigned()->comment('byr invoice detail Id');
            $table->integer('byr_invoice_id')->unsigned()->comment('invoice Id');
            $table->integer('byr_shop_id')->unsigned()->comment('shop Id');
            $table->integer('byr_item_id')->unsigned()->comment('byr_item_id');
            $table->enum('order_type', ['normal','direct'])->default('normal')->comment('order type');
            $table->integer('category_code')->unsigned()->default('1')->comment('category code');
            $table->string('voucher_category', 10)->comment('voucher category')->nullable();
            $table->string('voucher_number', 20)->comment('voucher number')->nullable();
            $table->smallInteger('list_number')->unsigned()->default('0')->comment('list number');
            $table->smallInteger('delivery_service_code')->unsigned()->default('1')->comment('delivery service code');
            $table->enum('status', ['未確定','確定済み','未出荷','出荷中','出荷済み'])->default('未確定')->comment('invoice status');
            $table->bigInteger('jan')->unsigned()->default('0')->comment('jan code');
            $table->string('item_name', 200)->comment('item name')->nullable();
            $table->string('item_name_kana', 400)->comment('item name kana')->nullable();
            $table->string('spac', 200)->comment('spac')->nullable();
            $table->string('spec_kana', 400)->comment('spec kana')->nullable();
            $table->integer('inputs')->unsigned()->default('1')->comment('inputs');
            $table->string('size', 50)->comment('size')->nullable();
            $table->string('color', 50)->comment('color')->nullable();
            $table->enum('order_inputs', ['ケース', 'ボール', 'バラ'])->default('ケース')->comment('order inputs');
            $table->decimal('invoice_quantity', 10, 1)->default('0.0')->comment('invoice quantity');
            $table->date('delivery_date')->comment(' delivery date')->nullable();
            $table->string('sale_category', 50)->comment('sale_category')->nullable();
            $table->decimal('cost_unit_price', 10, 2)->default('0.00')->comment('cost unit price');
            $table->integer('cost_price')->unsigned()->default('0')->comment('cost price');
            $table->mediumInteger('selling_unit_price')->unsigned()->default('0')->comment('selling unit price');
            $table->integer('selling_price')->unsigned()->default('0')->comment('selling price');
            $table->enum('tax_category', ['指定無し','内税','外税','内税(軽減税率)','外税(軽減税率)','その他'])->default('指定無し')->comment('tax category');
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
        Schema::dropIfExists('byr_invoice_details');
    }
}
