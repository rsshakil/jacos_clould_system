<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_shipments', function (Blueprint $table) {
            $table->increments('byr_shipment_id')->comment('byr shipment id');
            $table->integer('byr_order_id')->unsigned()->default('0')->comment('byr order id');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn connect id');
            $table->enum('category', ['edi', 'manual'])->default('edi')->comment('order category');
            $table->dateTime('send_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('send of creation');
            $table->dateTime('upload_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('send of creation');
            $table->string('send_file_path', 500)->comment('send file path')->nullable();
            $table->mediumInteger('data_count')->unsigned()->default('0')->comment('totall order');
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
        Schema::dropIfExists('byr_shipments');
    }
}
