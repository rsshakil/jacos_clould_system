<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_payments', function (Blueprint $table) {
            $table->increments('byr_payment_id')->unsigned()->comment('Buyer payment id');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn connect id');
            $table->dateTime('receive_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('receive_date');
            $table->string('receive_file_path', 500)->comment('receive file path')->nullable();
            $table->mediumInteger('data_count')->unsigned()->comment('totall order')->nullable();
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
        Schema::dropIfExists('byr_payments');
    }
}
