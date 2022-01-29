<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnTblColSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_tbl_col_settings', function (Blueprint $table) {
            $table->increments('cmn_tbl_col_setting_id')->unsigned()->comment('users_details_id');
            $table->string('url_slug', 120)->comment('page_url_name')->nullable();
            $table->longText('content_setting')->comment('content_setting')->nullable();
            $table->integer('update_by')->unsigned()->default('0')->comment('update_by');
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
        Schema::dropIfExists('cmn_tbl_col_settings');
    }
}
