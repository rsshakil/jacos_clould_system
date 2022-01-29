<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLv3TriggerFilePaths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lv3_trigger_file_paths', function (Blueprint $table) {
            $table->renameColumn('api_url', 'api_scenario');
        });
        Schema::table('lv3_trigger_file_paths', function (Blueprint $table) {
            $table->integer('api_scenario')->unsigned()->nullable()->comment('Scenario ID')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
