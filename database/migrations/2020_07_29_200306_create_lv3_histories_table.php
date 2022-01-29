<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLv3HistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lv3_histories', function (Blueprint $table) {
            $table->increments('lv3_history_id')->unsigned()->comment('lv3_history_id');
            $table->integer('lv3_service_id')->unsigned()->default(0)->comment('Service ID');
            $table->enum('execute_type',['自動','手動'])->default('自動')->comment('Execute Type');
            $table->dateTime('execute_date')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('execute_date');
            $table->enum('status',['Success','Error','Pending'])->default('Success')->comment('Execute status');
            $table->string('message',200)->comment('History message');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
			$table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('last updated time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lv3_histories');
    }
}
