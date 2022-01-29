<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCmnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cmn_items', function (Blueprint $table) {
            $table->increments('cmn_item_id')->unsigned()->comment('cmn_item_id');
            $table->integer('cmn_connect_id')->unsigned()->comment('cmn_connect_id');
            $table->string('mes_lis_pay_code', 13)->comment('支払法人コード');
            $table->string('mes_lis_pay_gln', 13)->comment('支払法人GLN');
            $table->string('mes_lis_pay_name', 20)->comment('支払法人名称');
            $table->string('mes_lis_pay_name_sbcs', 20)->comment('支払法人名称カナ');
            $table->string('mes_lis_buy_code', 13)->comment('発注者コード');
            $table->string('mes_lis_buy_gln', 13)->comment('発注者GLN');
            $table->string('mes_lis_buy_name', 20)->comment('発注者名称');
            $table->string('mes_lis_buy_name_sbcs', 20)->comment('発注者名称カナ');
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
            $table->string('mes_lis_ord_par_sel_ship_location_code', 4)->comment('出荷先コード');
            $table->string('mes_lis_ord_log_shi_gln', 13)->comment('出荷場所GLN');
            $table->string('mes_lis_ord_log_del_route_code', 2)->comment('納品経路');
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
            $table->string('mes_lis_ord_tra_ins_goods_classification_code',2)->comment('商品区分');
            $table->string('mes_lis_ord_tra_ins_order_classification_code',2)->comment('発注区分');
            $table->string('mes_lis_ord_tra_ins_ship_notification_request_code',2)->comment('出荷データ有無区分');
            $table->string('mes_lis_ord_tra_ins_private_brand_code',2)->comment('PB区分');
            $table->string('mes_lis_ord_tra_ins_temperature_code',2)->comment('配送温度区分');
            $table->string('mes_lis_ord_tra_ins_liquor_code',2)->comment('酒区分');
            $table->string('mes_lis_ord_tra_ins_trade_type_code',2)->comment('処理種別');
            $table->string('mes_lis_ord_tra_fre_package_code',2)->comment('パック区分');
            $table->string('mes_lis_ord_tra_fre_variable_measure_item_code',2)->comment('不定貫区分');
            $table->string('mes_lis_ord_tra_tax_tax_type_code',2)->comment('税区分');
            $table->string('mes_lis_ord_tra_tax_tax_rate',4)->comment('税率');
            $table->string('mes_lis_ord_tra_not_text',60)->comment('自由使用欄');
            $table->string('mes_lis_ord_tra_not_text_sbcs',60)->comment('自由使用欄半角カナ');
            $table->string('mes_lis_ord_lin_goo_minor_category',10)->comment('商品分類（小）');
            $table->string('mes_lis_ord_lin_goo_detailed_category',10)->comment('商品分類（細）');
            $table->string('mes_lis_ord_lin_ite_center_delivery_instruction_code',2)->comment('センター納品詳細指示');
            $table->string('mes_lis_ord_lin_ite_maker_code',13)->comment('メーカーコード');
            $table->string('mes_lis_ord_lin_ite_gtin',14)->comment('商品コード（ＧTIN）');
            $table->string('mes_lis_ord_lin_ite_order_item_code',14)->comment('商品コード（発注用）');
            $table->string('mes_lis_ord_lin_ite_ord_code_type',3)->comment('商品コード区分');
            $table->string('mes_lis_ord_lin_ite_supplier_item_code',14)->comment('商品コード（取引先）');
            $table->string('mes_lis_ord_lin_ite_name',25)->comment('商品名');
            $table->string('mes_lis_ord_lin_ite_name_sbcs',25)->comment('商品名カナ');
            $table->string('mes_lis_ord_lin_ite_ite_spec',25)->comment('規格');
            $table->string('mes_lis_ord_lin_ite_ite_spec_sbcs',25)->comment('規格カナ');
            $table->string('mes_lis_ord_lin_ite_col_color_code',10)->comment('カラーコード');
            $table->string('mes_lis_ord_lin_ite_col_description',20)->comment('カラー名称');
            $table->string('mes_lis_ord_lin_ite_col_description_sbcs',20)->comment('カラー名称カナ');
            $table->string('mes_lis_ord_lin_ite_siz_size_code',10)->comment('サイズコード');
            $table->string('mes_lis_ord_lin_ite_siz_description',30)->comment('サイズ名称');
            $table->string('mes_lis_ord_lin_ite_siz_description_sbcs',30)->comment('サイズ名称カナ');
            $table->string('mes_lis_ord_lin_fre_packing_quantity',4)->comment('入数');
            $table->string('mes_lis_ord_lin_fre_prefecture_code',3)->comment('都道府県コード');
            $table->string('mes_lis_ord_lin_fre_country_code',3)->comment('国コード');
            $table->string('mes_lis_ord_lin_fre_field_name',20)->comment('産地名');
            $table->string('mes_lis_ord_lin_fre_water_area_code',2)->comment('水域コード');
            $table->string('mes_lis_ord_lin_fre_water_area_name',20)->comment('水域名');
            $table->string('mes_lis_ord_lin_fre_area_of_origin',30)->comment('原産エリア');
            $table->string('mes_lis_ord_lin_fre_item_grade',8)->comment('等級');
            $table->string('mes_lis_ord_lin_fre_item_class',8)->comment('階級');
            $table->string('mes_lis_ord_lin_fre_brand',30)->comment('銘柄');
            $table->string('mes_lis_ord_lin_fre_item_pr',30)->comment('商品ＰＲ');
            $table->string('mes_lis_ord_lin_fre_bio_code',2)->comment('バイオ区分');
            $table->string('mes_lis_ord_lin_fre_breed_code',2)->comment('品種コード');
            $table->string('mes_lis_ord_lin_fre_cultivation_code',2)->comment('養殖区分');
            $table->string('mes_lis_ord_lin_fre_defrost_code',2)->comment('解凍区分');
            $table->string('mes_lis_ord_lin_fre_item_preservation_code',2)->comment('商品状態区分');
            $table->string('mes_lis_ord_lin_fre_item_shape_code',5)->comment('形状・部位');
            $table->string('mes_lis_ord_lin_fre_use',20)->comment('用途');
            $table->string('mes_lis_ord_lin_sta_statutory_classification_code',2)->comment('法定管理義務商材区分');
            $table->decimal('mes_lis_ord_lin_amo_item_net_price_unit_price',13,2)->comment('原単価');
            $table->integer('mes_lis_ord_lin_amo_item_selling_price_unit_price')->comment('売単価');
            $table->string('mes_lis_ord_lin_qua_unit_multiple',4)->comment('発注単位');
            $table->string('mes_lis_ord_lin_qua_unit_of_measure',2)->comment('発注単位コード');
            $table->string('mes_lis_ord_lin_qua_package_indicator',2)->comment('発注荷姿コード');
            $table->string('mes_lis_ord_lin_fre_unit_weight',15)->comment('取引単位重量');
            $table->string('mes_lis_ord_lin_fre_unit_weight_code',2)->comment('単価登録単位');
            $table->string('mes_lis_ord_lin_fre_item_weight',13)->comment('商品重量');
            $table->string('mes_lis_ord_lin_fre_order_weight',15)->comment('発注重量');
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
        Schema::dropIfExists('cmn_items');
    }
}
