<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateDataPaymentPaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_payment_pays', function (Blueprint $table) {
            $table->increments('data_payment_pay_id')->unsigned()->comment('data_payment_pay_id');
            $table->integer('data_payment_id')->unsigned()->comment('data_payment_id');
            $table->dateTime('check_datetime')->nullable()->comment('check_datetime');
            $table->string('mes_lis_buy_code', 13)->comment('発注者コード');
            $table->string('mes_lis_buy_gln', 13)->comment('発注者GLN');
            $table->string('mes_lis_buy_name', 20)->comment('発注者名称');
            $table->string('mes_lis_buy_name_sbcs', 20)->comment('発注者名称カナ');
            $table->string('mes_lis_pay_pay_code', 13)->comment('請求取引先コード');
            $table->string('mes_lis_pay_pay_id', 20)->comment('請求書番号');
            $table->string('mes_lis_pay_pay_gln', 13)->comment('請求取引先GLN');
            $table->string('mes_lis_pay_pay_name', 20)->comment('請求取引先名');
            $table->string('mes_lis_pay_pay_name_sbcs', 20)->comment('請求取引先名カナ');
            $table->date('mes_lis_pay_per_begin_date')->comment('対象期間開始');
            $table->date('mes_lis_pay_per_end_date')->comment('対象期間終了');
            $table->smallInteger('deleted')->unsigned()->default(1)->comment('削除フラグ');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('登録日時');
			$table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_payment_pays');
    }
}
