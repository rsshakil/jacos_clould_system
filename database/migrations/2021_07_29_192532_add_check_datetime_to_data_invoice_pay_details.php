<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCheckDatetimeToDataInvoicePayDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_invoice_pay_details', function (Blueprint $table) {
            $table->dateTime('check_datetime')->after('decision_datetime')->nullable()->comment('check_datetime');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_invoice_pay_details', function (Blueprint $table) {
            $table->dropColumn('check_datetime');
        });
    }
}
