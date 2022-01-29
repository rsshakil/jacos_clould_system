<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_categories', function (Blueprint $table) {
            $table->increments('cmn_category_id')->unsigned()->comment('category Id');
            $table->integer('parent_category_id')->unsigned()->default(0)->index()->comment('parent Id');
            $table->integer('byr_buyer_id')->unsigned()->default(0)->index()->comment('byr Id');
            $table->string('category_name',80)->comment('category Name');
            $table->string('category_code',12)->comment('category orgin Code');
            $table->string('image',240)->nullable()->comment('Image');
            $table->integer('level')->comment('1=majorcategory;2sub;3minor level');
            $table->boolean('is_deleted')->default(0)->comment('delete status');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
			$table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('Time of Update');
            $table->index(['parent_category_id', 'category_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmn_categories');
    }
}
