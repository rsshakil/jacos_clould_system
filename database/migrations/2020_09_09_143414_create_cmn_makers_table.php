<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnMakersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_makers', function (Blueprint $table) {
            $table->increments('cmn_maker_id')->unsigned()->comment('Maker Id');
            $table->integer('cmn_shop_id')->unsigned()->comment('shop Id');
            $table->integer('byr_buyer_id')->unsigned()->comment('byr Id');
            $table->string('maker_name',100)->comment('Maker Name');
            $table->string('maker_name_kana',100)->comment('Maker Name kana');
            $table->string('maker_code',10)->comment('Maker Code');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
			$table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('Time of Update');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmn_makers');
    }
}
