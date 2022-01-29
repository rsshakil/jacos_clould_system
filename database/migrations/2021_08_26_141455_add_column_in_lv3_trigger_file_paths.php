<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInLv3TriggerFilePaths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lv3_trigger_file_paths', function (Blueprint $table) {
            $table->boolean('api_execution_flag')->default(0)->after('path_execution_flag')->comment('api_execution_flag');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lv3_trigger_file_paths', function (Blueprint $table) {
            //
        });
    }
}
