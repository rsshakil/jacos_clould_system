<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_receives', function (Blueprint $table) {
            $table->increments('byr_receive_id')->unsigned()->comment('Buyer receive id');
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
        Schema::dropIfExists('byr_receives');
    }
}
