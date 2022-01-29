<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateSlrWareHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slr_ware_houses', function (Blueprint $table) {
            $table->increments('slr_ware_house_id')->unsigned()->comment('Ware house Id');
            $table->integer('slr_seller_id')->unsigned()->default(0)->comment('slr_seller_id');
            $table->integer('sub_jcode')->unsigned()->default(0)->comment('sub_jcode');
            $table->string('ware_house_name',80)->nullable()->comment('Ware house name');
            $table->string('ware_house_code',10)->nullable()->comment('ware_house_code');
            $table->string('postal_code',10)->nullable()->comment('postal_code');
            $table->string('address',200)->nullable()->comment('Ware house location');
            $table->string('phone',20)->nullable()->comment('Ware house phone number');
            $table->string('fax',20)->nullable()->comment('Fax');
            $table->string('email',100)->nullable()->comment('Ware house email');
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
        Schema::dropIfExists('slr_ware_houses');
    }
}
