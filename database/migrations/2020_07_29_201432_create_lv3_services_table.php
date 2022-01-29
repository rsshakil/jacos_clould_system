<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLv3ServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lv3_services', function (Blueprint $table) {
            $table->increments('lv3_service_id')->unsigned()->comment('Service ID');
            $table->integer('cmn_connect_id')->unsigned()->default(0)->comment('cmn_connect_id');
            $table->integer('adm_user_id')->unsigned()->default(0)->comment('User ID');
            $table->string('service_name',50)->nullable()->comment('Service Name');
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
        Schema::dropIfExists('lv3_services');
    }
}
