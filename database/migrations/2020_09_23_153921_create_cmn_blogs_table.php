<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateCmnBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_blogs', function (Blueprint $table) {
            $table->increments('cmn_blog_id')->unsigned()->comment('cmn blog Id');
            $table->integer('blog_by')->unsigned()->comment('author id');
            $table->integer('cmn_company_id')->nullable()->comment('company id');
            $table->string('blog_title', 500)->comment('voucher category')->nullable();
            $table->string('feature_img', 500)->comment('blog_img')->nullable();
            $table->longText('blog_content')->comment('blog content')->nullable();
            $table->enum('blog_status', ['published','unpublished'])->default('published')->comment('blog status');
            $table->enum('is_top_blog', ['1','0'])->default('0')->comment('1 top;0 active');
            $table->enum('is_delete', ['1','0'])->default('0')->comment('1 delete;0 active');
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
        Schema::dropIfExists('cmn_blogs');
    }
}
