<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDataPaymentPayDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_payment_pay_details', function (Blueprint $table) {
            $table->increments('data_payment_pay_detail_id')->unsigned()->comment('data_payment_pay_detail_id');
            $table->integer('data_payment_pay_id')->unsigned()->comment('data_payment_pay_id');
            $table->string('mes_lis_pay_lin_lin_trade_number_reference', 20)->comment('取引番号（発注・返品）');
            $table->string('mes_lis_pay_lin_lin_issue_classification_code', 20)->comment('発行区分');
            $table->string('mes_lis_pay_lin_lin_sequence_number', 20)->comment('連番');
            $table->string('mes_lis_pay_lin_tra_code', 13)->comment('計上部署コード');
            $table->string('mes_lis_pay_lin_tra_gln', 13)->comment('計上部署GLN');
            $table->string('mes_lis_pay_lin_tra_name', 20)->comment('計上部署名称');
            $table->string('mes_lis_pay_lin_tra_name_sbcs', 20)->comment('計上部署名称（カナ）');
            $table->string('mes_lis_pay_lin_sel_code', 13)->comment('取引先コード');
            $table->string('mes_lis_pay_lin_sel_gln', 13)->comment('取引先GLN');
            $table->string('mes_lis_pay_lin_sel_name', 20)->comment('取引先名称');
            $table->string('mes_lis_pay_lin_sel_name_sbcs', 20)->comment('取引先名称カナ');
            $table->string('mes_lis_pay_lin_det_goo_major_category', 20)->comment('商品分類（大）');
            $table->string('mes_lis_pay_lin_det_goo_sub_major_category', 20)->comment('商品分類（中）');
            $table->date('mes_lis_pay_lin_det_transfer_of_ownership_date')->comment('計上日');
            $table->date('mes_lis_pay_lin_det_pay_out_date')->comment('支払日');
            $table->integer('mes_lis_pay_lin_det_amo_requested_amount')->unsigned()->comment('請求金額');
            $table->string('mes_lis_pay_lin_det_amo_req_plus_minus', 10)->comment('請求金額符号');
            $table->integer('mes_lis_pay_lin_det_amo_payable_amount')->unsigned()->comment('支払金額');
            $table->string('mes_lis_pay_lin_det_amo_pay_plus_minus', 10)->comment('支払金額符号');
            $table->string('mes_lis_pay_lin_det_amo_optional_amount', 20)->comment('金額(小売自由使用)');
            $table->string('mes_lis_pay_lin_det_amo_opt_plus_minus', 10)->comment('金額符号(小売自由使用)');
            $table->integer('mes_lis_pay_lin_det_amo_tax')->comment('税額合計金額');
            $table->string('mes_lis_pay_lin_det_trade_type_code', 13)->comment('処理種別');
            $table->string('mes_lis_pay_lin_det_balance_carried_code', 13)->comment('請求区分');
            $table->string('mes_lis_pay_lin_det_creditor_unsettled_code', 13)->comment('未払買掛区分');
            $table->string('mes_lis_pay_lin_det_verification_result_code', 13)->comment('照合結果');
            $table->string('mes_lis_pay_lin_det_pay_code', 13)->comment('支払内容');
            $table->string('mes_lis_pay_lin_det_det_code', 13)->comment('支払内容（個別）');
            $table->string('mes_lis_pay_lin_det_det_meaning', 20)->comment('支払内容（個別名称）');
            $table->string('mes_lis_pay_lin_det_det_meaning_sbcs', 20)->comment('支払内容（個別名称カナ）');
            $table->string('mes_lis_pay_lin_det_payment_method_code', 13)->comment('支払方法区分');
            $table->string('mes_lis_pay_lin_det_tax_tax_type_code', 13)->comment('税区分');
            $table->decimal('mes_lis_pay_lin_det_tax_tax_rate', 3, 1)->comment('税率');
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
        Schema::dropIfExists('data_payment_pay_details');
    }
}
