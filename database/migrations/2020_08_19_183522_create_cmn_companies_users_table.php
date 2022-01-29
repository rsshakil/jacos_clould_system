<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnCompaniesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_companies_users', function (Blueprint $table) {
            $table->increments('cmn_companies_user_id')->unsigned()->comment('cmn_companies_user_id');
            $table->integer('cmn_company_id')->unsigned()->comment('cmn_company_id');
            $table->integer('adm_user_id')->unsigned()->comment('adm_user_id');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('last updated time');
            $table->foreign('adm_user_id')->references('id')->on('adm_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmn_companies_users');
    }
}
