<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLv3JobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lv3_jobs', function (Blueprint $table) {
            $table->increments('lv3_job_id')->unsigned()->comment('Job ID');
            $table->integer('lv3_service_id')->unsigned()->comment('Service ID');
            $table->boolean('job_execution_flag')->default(0)->comment('Job Execution Flag');
            $table->enum('execution', ['scenario', 'batch'])->default('scenario')->comment('Which path execute. Ex: API/Batch');
            $table->integer('cmn_scenario_id')->unsigned()->nullable()->comment('Scenario ID');
            $table->string('batch_file_path', 300)->nullable()->comment('batch_file_path');
            $table->integer('next_service_id')->unsigned()->nullable()->comment('Next Service ID');
            $table->string('append', 1000)->nullable()->comment('Append data(json)');
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
        Schema::dropIfExists('lv3_jobs');
    }
}
