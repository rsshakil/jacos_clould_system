<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnPdfPlatformCanvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_pdf_platform_canvas', function (Blueprint $table) {
            $table->increments('cmn_pdf_platform_canvas_id')->unsigned()->comment('Default ID');
            $table->integer('byr_buyer_id')->unsigned()->nullable()->comment('Buyer ID')->index();
            $table->string('canvas_name',100)->nullable()->comment('Canvas Name');
            $table->string('canvas_image',100)->nullable()->comment('Canvas live image');
            $table->string('canvas_bg_image',100)->nullable()->comment('Canvas Background Image');
            $table->json('canvas_objects')->nullable()->comment('Canvas Data');
            $table->string('line_gap',10)->default(28)->comment('Canvas line gap');
            $table->string('line_per_page',10)->default(26)->comment('Line per page');
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
        Schema::dropIfExists('cmn_pdf_platform_canvas');
    }
}
