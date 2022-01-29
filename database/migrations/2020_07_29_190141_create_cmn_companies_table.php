<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_companies', function (Blueprint $table) {
            $table->increments('cmn_company_id')->unsigned()->comment('cmn_company_id');
            $table->enum('company_type', ['seller', 'buyer'])->default('buyer')->comment('Company Type');
            $table->string('company_name', 200)->comment('company_name');
            $table->string('company_name_kana', 200)->comment('company_name_kana');
            $table->string('jcode', 10)->comment('jcode');
            $table->string('phone', 20)->comment('Company phone number');
            $table->string('fax', 20)->comment('Fax');
            $table->string('postal_code', 10)->comment('postal_code');
            $table->string('address', 200)->comment('Address');
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
        Schema::dropIfExists('cmn_companies');
    }
}
