<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLv3TriggerFilePathsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lv3_trigger_file_paths', function (Blueprint $table) {
            $table->increments('lv3_trigger_file_path_id')->unsigned()->comment('File Path ID');
            $table->integer('lv3_service_id')->unsigned()->comment('Service ID');
            $table->string('check_folder_path',300)->nullable()->comment('Source path of order file');
            $table->string('moved_folder_path',300)->nullable()->comment('Moved path of order file');
            $table->string('api_url',300)->nullable()->comment('API URL');
            $table->string('api_folder_path',300)->nullable()->comment('API File Save Path');
            $table->boolean('path_execution_flag')->default(0)->comment('Path Execution Flag');
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
        Schema::dropIfExists('lv3_trigger_file_paths');
    }
}
