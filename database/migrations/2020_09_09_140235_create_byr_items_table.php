<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateByrItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('byr_items', function (Blueprint $table) {
            $table->increments('byr_item_id')->unsigned()->comment('byr Item Id');
            $table->integer('byr_shop_id')->unsigned()->comment('shop Id');
            $table->integer('byr_buyer_id')->unsigned()->comment('byr Id');
            $table->integer('cmn_category_id')->unsigned()->comment('Category Id');
            $table->integer('cmn_maker_id')->unsigned()->comment('Maker Id');
            $table->smallInteger('case_inputs')->default(1)->comment('Case Inputs');
            $table->smallInteger('ball_inputs')->default(1)->comment('Ball Inputs');
            $table->string('jan',15)->comment('Vendor Jan');
            $table->string('name',100)->comment('Jan Name');
            $table->string('name_kana',100)->comment('jan kana Name');
            $table->string('spec',100)->comment('jan spec');
            $table->string('spec_kana',100)->comment('jan spec kana');
            $table->string('color',20)->comment('color');
            $table->string('size',30)->comment('size');
            $table->enum('tax', ['included_tax', 'excluded_tax'])->default('excluded_tax')->comment('Vendor item tax');
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
        Schema::dropIfExists('byr_items');
    }
}
