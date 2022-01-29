<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateLv3TriggerSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lv3_trigger_schedules', function (Blueprint $table) {
            $table->increments('lv3_trigger_schedule_id')->unsigned()->comment('Schedule ID');
            $table->integer('lv3_service_id')->unsigned()->comment('Service ID');
            $table->tinyInteger('day')->unsigned()->nullable()->comment('1-31');
            $table->tinyInteger('weekday')->unsigned()->default(0)->comment('0 - 1111111(127)');
            $table->time('time')->default('00:00:00')->comment('Time');
            $table->boolean('last_day')->default(0)->comment('Last day of month Ex: 0 or 1');
            $table->boolean('disabled')->default(0)->comment('0 or 1');
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
        Schema::dropIfExists('lv3_trigger_schedules');
    }
}
