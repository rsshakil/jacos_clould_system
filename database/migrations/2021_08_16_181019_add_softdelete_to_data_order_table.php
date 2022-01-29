<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToDataOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_orders', function (Blueprint $table) {
            // $table->softDeletes()->after('updated_at');
            $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
