<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_shops', function (Blueprint $table) {
            $table->increments('byr_shop_id')->unsigned()->comment('byr_shop_id');
            $table->integer('byr_buyer_id')->unsigned()->comment('小売ID');
            $table->integer('slr_seller_id')->default(0)->unsigned()->comment('問屋ID');
            $table->string('shop_code',10)->comment('店コード');
            $table->string('shop_name',100)->comment('店名');
            $table->string('shop_name_kana',100)->comment('店名かな');
            $table->string('shop_postal_code',10)->comment('Customer shop postal code');
            $table->string('shop_address',200)->comment('Customer shop address');
            $table->string('phone',20)->comment('Customer shop phone number');
            $table->string('fax',20)->comment('Fax');
            $table->string('email',100)->comment('Customer shop email');
            $table->tinyInteger('delivery_cycle')->default(0)->comment('Delivery cycle');
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
        Schema::dropIfExists('byr_shops');
    }
}
