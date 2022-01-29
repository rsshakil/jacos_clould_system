<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateAdmUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adm_user_details', function (Blueprint $table) {
            $table->increments('users_details_id')->comment('users_details_id');
            $table->integer('user_id')->unsigned()->comment('users_id');
			$table->string('first_name', 80)->comment('first_name')->nullable();
            $table->string('last_name', 80)->comment('last_name')->nullable();
            $table->string('phone', 20)->comment('Phone Number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female','Others'])->default('Male')->comment('Gender of Users');
            $table->string('postal_code', 40)->comment('postal_code')->nullable();
            $table->char('country', 2)->nullable();
            $table->char('language', 2)->nullable();
            $table->string('time_zone', 40)->comment('time_zone')->nullable();
            $table->string('citi_time_zone', 40)->comment('citime_zone')->nullable();
            $table->string('image', 240)->comment('Image of user')->nullable();
			$table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('Time of creation');
			$table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('Time of Update');
            $table->foreign('user_id')
                ->references('id')
                ->on('adm_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm_user_details');
    }
}
