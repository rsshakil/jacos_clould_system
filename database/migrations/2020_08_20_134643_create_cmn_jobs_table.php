<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_jobs', function (Blueprint $table) {
            $table->increments('cmn_job_id')->unsigned()->comment('Common Job ID');
            $table->integer('cmn_connect_id')->unsigned()->comment('Common Connect ID');
            $table->enum('class',['order','shipment','invoice','payment','inventory','other'])->default('order')->comment('種別　発注/確定/請求/支払/棚卸/その他');
            $table->enum('vector',['to_jacos','from_jacos','in_jacos','other'])->default('to_jacos')->comment('0 : to jacos/1: from jacos /2:in jacos/3:other');
            $table->enum('use_case',['browser','biware','level3','selenium','other'])->default('level3')->comment('use_case');
            $table->integer('cmn_scenario_id')->unsigned()->nullable()->comment('Scenario ID');
            $table->boolean('is_active')->default(1)->comment('1 active / 0 not active');
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
        Schema::dropIfExists('cmn_jobs');
    }
}
