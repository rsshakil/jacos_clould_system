<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_invoices', function (Blueprint $table) {
            $table->increments('byr_invoice_id')->unsigned()->comment('Byr invoice id');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn connect id');
            $table->dateTime('send_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('send_date');
            $table->string('send_file_path', 500)->comment('send file path')->nullable();
            $table->mediumInteger('data_count')->unsigned()->comment('totall')->nullable();
            $table->mediumInteger('request_amount')->unsigned()->default('0')->comment('request amount');
            $table->enum('status', ['請求済み','請求エラー'])->default('請求済み')->comment(' status');
            $table->date('start_date')->comment('start_date')->nullable();
            $table->date('end_date')->comment('end_date')->nullable();
            $table->dateTime('upload_date')->comment('upload_date')->nullable();
            $table->dateTime('download_date')->comment('download_date')->nullable();
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
        Schema::dropIfExists('byr_invoices');
    }
}
