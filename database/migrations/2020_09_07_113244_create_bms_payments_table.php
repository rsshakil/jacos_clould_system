<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateBmsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_payments', function (Blueprint $table) {
            $table->increments('bms_payment_id')->unsigned()->comment('bms_payment_id');
            $table->integer('byr_payment_id')->unsigned()->comment('byr_payment_id');
            // $table->string('file_name',100)->comment('発注ファイル名');
			// $table->string('customer_id', 6)->comment('取引先ID');
            $table->string('sta_sen_identifier', 10)->comment('送信者ＩＤ');
            $table->string('sta_sen_ide_authority', 10)->comment('送信者ＩＤ発行元');
            $table->string('sta_rec_identifier',10)->comment('受信者ＩＤ');
            $table->string('sta_rec_ide_authority', 10)->comment('受信者ＩＤ発行元');
            $table->string('sta_doc_standard', 20)->comment('標準名称');
            $table->string('sta_doc_type_version', 10)->comment('バージョン');
            $table->string('sta_doc_instance_id', 10)->comment('インスタンスＩＤ');
            $table->string('sta_doc_type', 10)->comment('メッセージ種');
            $table->string('sta_doc_creation_date_and_time', 10)->comment('作成日時');
            $table->string('sta_bus_scope_instance_identifier', 20)->comment('テスト区分ＩＤ');
            $table->string('sta_bus_scope_type', 10)->comment('タイプ');
            $table->string('sta_bus_scope_identifier', 20)->comment('最終送信先ＩＤ');
            $table->string('mes_ent_unique_creator_identification', 80)->comment('メッセージ識別ＩＤ');
            $table->string('mes_mes_sender_station_address', 8)->comment('送信者ステーションアドレス');
            $table->string('mes_mes_ultimate_receiver_station_address', 8)->comment('最終受信者ステーションアドレス');
            $table->string('mes_mes_immediate_receiver_station_addres', 8)->comment('直接受信者ステーションアドレス');
            $table->string('mes_mes_number_of_trading_documents', 7)->comment('取引数');
            $table->string('mes_mes_system_info', 20)->comment('システム情報');
            $table->string('mes_mes_sys_key', 20)->comment('システム情報キー');
            $table->string('mes_mes_sys_value', 20)->comment('システム情報値');
            $table->string('mes_lis_con_version', 20)->comment('バージョン番号');
            $table->string('mes_lis_doc_version', 20)->comment('バージョン番号');
            $table->string('mes_lis_ext_namespace', 80)->comment('名前空間');
            $table->string('mes_lis_ext_version', 20)->comment('バージョン');
            $table->string('mes_lis_pay_code', 13)->comment('支払法人コード');
            $table->string('mes_lis_pay_gln', 13)->comment('支払法人GLN');
            $table->string('mes_lis_pay_name', 20)->comment('支払法人名称');
            $table->string('mes_lis_pay_name_sbcs', 20)->comment('支払法人名称カナ');
            $table->string('mes_lis_buy_code', 13)->comment('発注者コード');
            $table->string('mes_lis_buy_gln', 13)->comment('発注者GLN');
            $table->string('mes_lis_buy_name', 20)->comment('発注者名称');
            $table->string('mes_lis_buy_name_sbcs', 20)->comment('発注者名称カナ');
            $table->string('mes_lis_pay_pay_id', 10)->comment('請求書番号');
            $table->string('mes_lis_pay_pay_code', 13)->comment('請求取引先コード');
            $table->string('mes_lis_pay_pay_gln', 13)->comment('請求取引先GLN');
            $table->string('mes_lis_pay_pay_name', 20)->comment('請求取引先名');
            $table->string('mes_lis_pay_pay_name_sbcs', 20)->comment('請求取引先名カナ');
            $table->date('mes_lis_pay_per_beg_in_date')->comment('対象期間開始');
            $table->date('mes_lis_pay_per_end_date')->comment('対象期間終了');
            $table->string('mes_lis_pay_lin_lin_trade_number_reference', 10)->comment('取引番号（発注・返品）');
            $table->string('mes_lis_pay_lin_lin_issue_classification_code', 10)->comment('発行区分');
            $table->string('mes_lis_pay_lin_lin_sequence_number', 10)->comment('連番');
            $table->string('mes_lis_pay_lin_tra_code', 13)->comment('計上部署コード');
            $table->string('mes_lis_pay_lin_tra_gln', 13)->comment('計上部署GLN');
            $table->string('mes_lis_pay_lin_tra_name', 20)->comment('計上部署名称');
            $table->string('mes_lis_pay_lin_tra_name_sbcs', 20)->comment('計上部署名称（カナ）');
            $table->string('mes_lis_pay_lin_sel_code', 13)->comment('取引先コード');
            $table->string('mes_lis_pay_lin_sel_gln', 13)->comment('取引先GLN');
            $table->string('mes_lis_pay_lin_sel_name', 20)->comment('取引先名称');
            $table->string('mes_lis_pay_lin_sel_name_sbcs', 20)->comment('取引先名称カナ');
            $table->string('mes_lis_pay_lin_det_goo_major_category', 10)->comment('商品分類（大）');
            $table->string('mes_lis_pay_lin_det_goo_sub_category', 10)->comment('商品分類（中）');
            $table->date('mes_lis_pay_lin_det_transfer_of_ownership_date')->comment('計上日');
            $table->date('mes_lis_pay_lin_det_pay_out_date')->comment('請求金額');
            $table->string('mes_lis_pay_lin_det_amo_requested_amount', 11)->comment('請求金額符号');
            $table->string('mes_lis_pay_lin_det_amo_req_plus_minus', 13)->comment('税額合計金額');
            $table->string('mes_lis_pay_lin_det_amo_payable_amount', 11)->comment('mes_lis_pay_lin_det_amo_payable_amount');
            $table->string('mes_lis_pay_lin_det_amo_pay_plus_minus', 10)->comment('mes_lis_pay_lin_det_amo_pay_plus_minus');
            $table->string('mes_lis_pay_lin_det_amo_optional_amount', 11)->comment('mes_lis_pay_lin_det_amo_optional_amount');
            $table->string('mes_lis_pay_lin_det_amo_opt_plus_minus', 10)->comment('mes_lis_pay_lin_det_amo_opt_plus_minus');
            $table->string('mes_lis_pay_lin_det_amo_tax', 10)->comment('mes_lis_pay_lin_det_amo_tax');
            $table->string('mes_lis_pay_lin_det_trade_type_code', 10)->comment('mes_lis_pay_lin_det_trade_type_code');
            $table->string('mes_lis_pay_lin_det_balance_carried_code', 10)->comment('請求区分');
            $table->string('mes_lis_pay_lin_det_creditor_unsettled_code', 10)->comment('未払買掛区分');
            $table->string('mes_lis_pay_lin_det_verification_result_code', 10)->comment('mes_lis_pay_lin_det_verification_result_code');
            $table->string('mes_lis_pay_lin_det_pay_code', 10)->comment('支払内容');
            $table->string('mes_lis_pay_lin_det_det_code', 10)->comment('mes_lis_pay_lin_det_det_code');
            $table->string('mes_lis_pay_lin_det_det_meaning', 30)->comment('mes_lis_pay_lin_det_det_meaning');
            $table->string('mes_lis_pay_lin_det_det_meaning_sbcs', 30)->comment('mes_lis_pay_lin_det_det_meaning_sbcs');
            $table->string('mes_lis_pay_lin_det_payment_method_code', 10)->comment('mes_lis_pay_lin_det_payment_method_code');
            $table->string('mes_lis_pay_lin_det_tax_tax_type_code', 10)->comment('税区分');
            $table->string('mes_lis_pay_lin_det_tax_tax_rate', 10)->comment('税率');
            $table->smallInteger('deleted')->unsigned()->comment('削除フラグ');
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
        Schema::dropIfExists('bms_payments');
    }
}
