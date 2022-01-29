<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnScenariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_scenarios', function (Blueprint $table) {
            $table->increments('cmn_scenario_id')->unsigned()->comment('cmn_job_scenario_id');
            $table->integer('byr_buyer_id')->unsigned()->default(0)->comment('byr_buyer_id　0は共通');
            $table->integer('slr_seller_id')->unsigned()->default(0)->comment('slr_seller_id　0は共通');
            $table->integer('adm_role_id')->unsigned()->nullable()->comment('Admin Role');
            $table->enum('class',['order','shipment','invoice','payment','receive','return','inventory','other'])->default('order')->comment('class');
            $table->enum('vector',['to_jacos','from_jacos','in_jacos','other'])->comment('処理方向');
            $table->string('name',50)->comment('シナリオ名');
            $table->string('description',200)->comment('説明');
            $table->string('file_path',200)->comment('ファイルパス');
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
        Schema::dropIfExists('cmn_scenarios');
    }
}
