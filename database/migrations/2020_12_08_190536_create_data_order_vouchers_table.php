<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDataOrderVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_order_vouchers', function (Blueprint $table) {
            $table->increments('data_order_voucher_id')->unsigned()->comment('data_order_voucher_id');
            $table->integer('data_order_id')->unsigned()->comment('data_order_id');
            $table->dateTime('check_datetime')->nullable()->comment('確認日時');
            $table->string('mes_lis_ord_tra_trade_number', 10)->comment('取引番号（発注・返品）');
            $table->string('mes_lis_ord_tra_additional_trade_number', 10)->comment('取引付属番号');
            $table->string('mes_lis_ord_par_shi_code', 13)->comment('直接納品先コード');
            $table->string('mes_lis_ord_par_shi_gln', 13)->comment('直接納品先GLN');
            $table->string('mes_lis_ord_par_shi_name', 20)->comment('直接納品先名称');
            $table->string('mes_lis_ord_par_shi_name_sbcs', 20)->comment('直接納品先名称カナ');
            $table->string('mes_lis_ord_par_rec_code', 13)->comment('最終納品先コード');
            $table->string('mes_lis_ord_par_rec_gln', 13)->comment('最終納品先GLN');
            $table->string('mes_lis_ord_par_rec_name', 20)->comment('最終納品先名称');
            $table->string('mes_lis_ord_par_rec_name_sbcs', 20)->comment('最終納品先名称カナ');
            $table->string('mes_lis_ord_par_tra_code', 13)->comment('計上部署コード');
            $table->string('mes_lis_ord_par_tra_gln', 13)->comment('計上部署GLN');
            $table->string('mes_lis_ord_par_tra_name', 20)->comment('計上部署名称');
            $table->string('mes_lis_ord_par_tra_name_sbcs', 20)->comment('計上部署名称（カナ）');
            $table->string('mes_lis_ord_par_dis_code', 13)->comment('陳列場所コード');
            $table->string('mes_lis_ord_par_dis_name', 20)->comment('陳列場所名称');
            $table->string('mes_lis_ord_par_dis_name_sbcs', 20)->comment('陳列場所名称カナ');
            $table->string('mes_lis_ord_par_pay_code', 13)->comment('請求取引先コード');
            $table->string('mes_lis_ord_par_pay_gln', 13)->comment('請求取引先GLN');
            $table->string('mes_lis_ord_par_pay_name', 20)->comment('請求取引先名');
            $table->string('mes_lis_ord_par_pay_name_sbcs', 20)->comment('請求取引先名カナ');
            $table->string('mes_lis_ord_par_sel_code', 13)->comment('取引先コード');
            $table->string('mes_lis_ord_par_sel_gln', 13)->comment('取引先GLN');
            $table->string('mes_lis_ord_par_sel_name', 20)->comment('取引先名称');
            $table->string('mes_lis_ord_par_sel_name_sbcs', 20)->comment('取引先名称カナ');
            $table->string('mes_lis_ord_par_sel_branch_number', 2)->comment('枝番');
            $table->string('mes_lis_ord_par_sel_ship_location_code', 4)->comment('出荷先コード');
            $table->string('mes_lis_ord_log_shi_gln', 13)->comment('出荷場所GLN');
            $table->string('mes_lis_ord_log_del_route_code', 2)->comment('納品経路');
            $table->string('mes_lis_ord_log_del_delivery_service_code', 2)->comment('便No');
            $table->string('mes_lis_ord_log_del_stock_transfer_code', 2)->comment('通過在庫区分');
            $table->string('mes_lis_ord_log_del_delivery_code', 2)->comment('納品区分');
            $table->string('mes_lis_ord_log_del_delivery_time', 10)->comment('指定納品時刻');
            $table->string('mes_lis_ord_log_del_transportation_code', 2)->comment('輸送手段');
            $table->string('mes_lis_ord_log_log_barcode_print', 13)->comment('バーコード情報');
            $table->string('mes_lis_ord_log_log_category_name_print1', 5)->comment('カテゴリー名称1（印字用）');
            $table->string('mes_lis_ord_log_log_category_name_print2', 5)->comment('カテゴリー名称2（印字用）');
            $table->string('mes_lis_ord_log_log_receiver_abbr_name', 5)->comment('最終納品先略称（印字用）');
            $table->string('mes_lis_ord_log_log_text', 60)->comment('ラベル自由使用欄（印字用）');
            $table->string('mes_lis_ord_log_log_text_sbcs', 60)->comment('ラベル自由使用欄半角カナ（印字用）');
            $table->string('mes_lis_ord_tra_goo_major_category', 10)->comment('商品分類（大）');
            $table->string('mes_lis_ord_tra_goo_sub_major_category', 10)->comment('商品分類（中）');
            $table->date('mes_lis_ord_tra_dat_order_date')->comment('発注日');
            $table->date('mes_lis_ord_tra_dat_delivery_date')->comment('直接納品先納品日');
            $table->date('mes_lis_ord_tra_dat_delivery_date_to_receiver')->comment('最終納品先納品日');
            $table->date('mes_lis_ord_tra_dat_transfer_of_ownership_date')->comment('計上日');
            $table->date('mes_lis_ord_tra_dat_campaign_start_date')->comment('販促開始日');
            $table->date('mes_lis_ord_tra_dat_campaign_end_date')->comment('販促終了日');
            $table->date('mes_lis_ord_tra_dat_valid_until_date')->comment('取引（発注・返品）データ有効日');
            $table->string('mes_lis_ord_tra_ins_goods_classification_code', 2)->comment('商品区分');
            $table->string('mes_lis_ord_tra_ins_order_classification_code', 2)->comment('発注区分');
            $table->string('mes_lis_ord_tra_ins_ship_notification_request_code', 2)->comment('出荷データ有無区分');
            $table->string('mes_lis_ord_tra_ins_private_brand_code', 2)->comment('PB区分');
            $table->string('mes_lis_ord_tra_ins_temperature_code', 2)->comment('配送温度区分');
            $table->string('mes_lis_ord_tra_ins_liquor_code', 2)->comment('酒区分');
            $table->string('mes_lis_ord_tra_ins_trade_type_code', 2)->comment('処理種別');
            $table->string('mes_lis_ord_tra_ins_paper_form_less_code', 2)->comment('伝票レス区分');
            $table->string('mes_lis_ord_tra_fre_trade_number_request_code', 2)->comment('取引番号区分');
            $table->string('mes_lis_ord_tra_fre_package_code', 2)->comment('パック区分');
            $table->string('mes_lis_ord_tra_fre_variable_measure_item_code', 2)->comment('不定貫区分');
            $table->string('mes_lis_ord_tra_tax_tax_type_code', 2)->comment('税区分');
            $table->decimal('mes_lis_ord_tra_tax_tax_rate', 3, 1)->comment('税率');
            $table->string('mes_lis_ord_tra_not_text', 60)->comment('自由使用欄');
            $table->string('mes_lis_ord_tra_not_text_sbcs', 60)->comment('自由使用欄半角カナ');
            $table->integer('mes_lis_ord_tot_tot_net_price_total')->unsigned()->comment('原価金額合計');
            $table->integer('mes_lis_ord_tot_tot_selling_price_total')->unsigned()->comment('売価金額合計');
            $table->integer('mes_lis_ord_tot_tot_tax_total')->unsigned()->comment('税額合計金額');
            $table->integer('mes_lis_ord_tot_tot_item_total')->comment('数量合計');
            $table->integer('mes_lis_ord_tot_tot_unit_total')->comment('発注単位数量合計');
            $table->decimal('mes_lis_ord_tot_fre_unit_weight_total', 13, 3)->comment('重量合計');
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
        Schema::dropIfExists('data_order_vouchers');
    }
}
