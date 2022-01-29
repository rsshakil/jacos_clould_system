<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateReceiveDatetimeAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_receives', function (Blueprint $table) {
            // $table->dateTime('receive_datetime')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
            $table->dateTime('receive_datetime')->default('CURRENT_TIMESTAMP')->change();
        });
        Schema::table('data_returns', function (Blueprint $table) {
            $table->dateTime('receive_datetime')->default('CURRENT_TIMESTAMP')->change();
        });
        Schema::table('data_payments', function (Blueprint $table) {
            $table->dateTime('receive_datetime')->default('CURRENT_TIMESTAMP')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('data_returns', function (Blueprint $table) {
        //     //
        // });
    }
}
