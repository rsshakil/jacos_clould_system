<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCmnScenarioHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_scenario_histories', function (Blueprint $table) {
            $table->increments('cmn_scenario_history_id')->unsigned()->comment('cmn_scenario_history_id');
            $table->integer('cmn_scenario_id')->unsigned()->comment('cmn_scenario_id');
            $table->integer('adm_user_id')->unsigned()->nullable()->comment('adm_user_id');
            $table->dateTime('exec_datetime')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('exec_datetime');
            $table->enum('data', ['order','receive','return','invoice','payment'])->default('order')->nullable()->comment('data');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn_connect_id');
            $table->integer('data_id')->unsigned()->nullable()->comment('data_id');
            $table->enum('status', ['success','error'])->default('success')->nullable()->comment('status');
            $table->string('information', 1000)->comment('information');
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
        Schema::dropIfExists('cmn_scenario_histories');
    }
}
