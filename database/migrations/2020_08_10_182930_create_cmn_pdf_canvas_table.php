<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnPdfCanvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_pdf_canvas', function (Blueprint $table) {
            $table->increments('cmn_pdf_canvas_id')->unsigned()->comment('Default ID');
            // $table->string('canvas_id',30)->comment('Canvas random ID');
            $table->integer('byr_buyer_id')->unsigned()->nullable()->comment('Buyer ID')->index();
            $table->enum('class', ['order','shipment','receive','invoice','corrected_receive','return','payment','inventory','other'])
            ->default('order')->comment('class');
            $table->string('canvas_name',100)->nullable()->comment('Canvas Name');
            $table->string('canvas_image',100)->nullable()->comment('Canvas live image');
            $table->string('canvas_bg_image',100)->nullable()->comment('Canvas Background Image');
            $table->json('canvas_objects')->nullable()->comment('Canvas Data');
            // $table->json('position_values')->nullable()->comment('Canvas objects position value');
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
        Schema::dropIfExists('cmn_pdf_canvas');
    }
}
