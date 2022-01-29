<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateSlrSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slr_sellers', function (Blueprint $table) {
            $table->increments('slr_seller_id')->unsigned()->comment('slr_seller_id');
            $table->unsignedInteger('cmn_company_id');
            $table->foreign('cmn_company_id')->references('cmn_company_id')->on('cmn_companies')->onDelete('cascade');
            $table->integer('adm_role_id')->unsigned()->default(0)->comment('adm_role_id');
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
        Schema::table('slr_sellers', function(Blueprint $table){
            $table->dropForeign('cmn_company_id'); //
        });
        Schema::dropIfExists('slr_sellers');
    }
}
