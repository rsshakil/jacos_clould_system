<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('byr_buyers', function (Blueprint $table) {
            $table->increments('byr_buyer_id')->unsigned()->comment('byr_buyer_id');
            $table->unsignedInteger('cmn_company_id');
            $table->foreign('cmn_company_id')->references('cmn_company_id')->on('cmn_companies')->onDelete('cascade');;
            $table->string('super_code',4)->comment('Super Code');
            $table->integer('adm_role_id')->default(0)->unsigned()->comment('Admin role id');
            $table->json('setting_information')->nullable()->comment('Setting Information Json');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('last updated time');
            $table->index(['cmn_company_id','adm_role_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('byr_buyers', function(Blueprint $table){
            $table->dropForeign('cmn_company_id'); //
        });
        Schema::dropIfExists('byr_buyers');

    }
}
