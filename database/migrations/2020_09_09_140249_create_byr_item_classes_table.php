<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrItemClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_item_classes', function (Blueprint $table) {
            $table->increments('byr_item_class_id')->unsigned()->comment('byr Item class Id');
            $table->integer('byr_item_id')->unsigned()->comment('byr item Id');
            $table->enum('order_class', ['basic', 'sale','spot'])->default('basic')->comment('byr Class');
            $table->decimal('cost_price',9,2)->comment(' shop Price');
            $table->decimal('shop_price',9,2)->comment(' shop Price');
            $table->date('start_date')->comment('basic Start Date');
            $table->date('end_date')->nullable()->comment('basic End Date');
            $table->enum('order_point_inputs', ['ケース', 'ボール','バラ'])->default('ケース')->comment(' Order Point Inputs');
            $table->smallInteger('order_point_quantity')->nullable()->comment(' Order Point Quantity');
            $table->enum('order_lot_inputs', ['ケース', 'ボール','バラ'])->default('ケース')->comment(' Order Lot Inputs');
            $table->smallInteger('order_lot_quantity')->nullable()->comment(' Order Lot Quantity');
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
        Schema::dropIfExists('byr_item_classes');
    }
}
