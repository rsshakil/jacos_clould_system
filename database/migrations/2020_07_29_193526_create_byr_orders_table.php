<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_orders', function (Blueprint $table) {
            $table->increments('byr_order_id')->comment('byr order id');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn connect id');
            $table->string('byr_name',50)->nullable()->comment('byr_name');
            $table->string('byr_name_kana',50)->nullable()->comment('byr_name_kana');
            $table->string('slr_name',50)->nullable()->comment('slr_name');
            $table->string('slr_name_kana',50)->nullable()->comment('slr_name_kana');
            $table->string('partner_code',50)->nullable()->comment('partner_code');
            // $table->enum('category', ['edi', 'manual'])->default('edi')->comment('order category');
            // $table->enum('status', ['未確定', '確定済み', '未出荷', '出荷中', '出荷済み'])->default('未確定')->comment('order status');
            $table->enum('manual', ['未確定', '確定済み', '未出荷', '出荷中', '出荷済み'])->default('未確定')->comment('manual');
            $table->dateTime('download_date')->comment('Time of creation')->nullable();
            $table->dateTime('receive_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
            $table->string('receive_file_path', 500)->comment('receive file path')->nullable();
            $table->mediumInteger('data_count')->unsigned()->default('0')->comment('totall order')->nullable();
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
        Schema::dropIfExists('byr_orders');
    }
}
