<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDataShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_shipment_items', function (Blueprint $table) {
            $table->increments('data_shipment_item_id')->unsigned()->comment('data_shipment_item_id');
            $table->integer('data_shipment_voucher_id')->unsigned()->comment('data_shipment_voucher_id');
            $table->string('mes_lis_shi_lin_lin_line_number', 4)->comment('取引明細番号（発注・返品）');
            $table->string('mes_lis_shi_lin_lin_additional_line_number', 4)->comment('取引付属明細番号');
            $table->string('mes_lis_shi_lin_fre_trade_number', 10)->comment('元取引番号');
            $table->string('mes_lis_shi_lin_fre_line_number', 4)->comment('元取引明細番号');
            $table->string('mes_lis_shi_lin_fre_shipment_line_number', 4)->default('')->comment('出荷者管理明細番号');
            $table->string('mes_lis_shi_lin_goo_minor_category', 10)->comment('商品分類（小）');
            $table->string('mes_lis_shi_lin_goo_detailed_category', 10)->comment('商品分類（細）');
            $table->date('mes_lis_shi_lin_ite_scheduled_date')->comment('配達予定日');
            $table->date('mes_lis_shi_lin_ite_deadline_date')->comment('納品期限');
            $table->string('mes_lis_shi_lin_ite_center_delivery_instruction_code', 2)->comment('センター納品詳細指示');
            $table->string('mes_lis_shi_lin_fre_interim_price_code', 2)->default('')->comment('仮伝フラグ');
            $table->string('mes_lis_shi_lin_ite_maker_code', 13)->comment('メーカーコード');
            $table->string('mes_lis_shi_lin_ite_gtin', 14)->comment('商品コード（ＧTIN）');
            $table->string('mes_lis_shi_lin_ite_order_item_code', 14)->comment('商品コード（発注用）');
            $table->string('mes_lis_shi_lin_ite_ord_code_type', 3)->comment('商品コード区分');
            $table->string('mes_lis_shi_lin_ite_supplier_item_code', 14)->comment('商品コード（取引先）');
            $table->string('mes_lis_shi_lin_ite_name', 25)->comment('商品名');
            $table->string('mes_lis_shi_lin_ite_name_sbcs', 25)->comment('商品名カナ');
            $table->string('mes_lis_shi_lin_fre_shipment_item_code', 14)->default('')->comment('商品コード（出荷元）');
            $table->string('mes_lis_shi_lin_ite_ite_spec', 25)->comment('規格');
            $table->string('mes_lis_shi_lin_ite_ite_spec_sbcs', 25)->comment('規格カナ');
            $table->string('mes_lis_shi_lin_ite_col_color_code', 10)->comment('カラーコード');
            $table->string('mes_lis_shi_lin_ite_col_description', 20)->comment('カラー名称');
            $table->string('mes_lis_shi_lin_ite_col_description_sbcs', 20)->comment('カラー名称カナ');
            $table->string('mes_lis_shi_lin_ite_siz_size_code', 10)->comment('サイズコード');
            $table->string('mes_lis_shi_lin_ite_siz_description', 30)->comment('サイズ名称');
            $table->string('mes_lis_shi_lin_ite_siz_description_sbcs', 30)->comment('サイズ名称カナ');
            $table->string('mes_lis_shi_lin_fre_packing_quantity', 4)->comment('入数');
            $table->string('mes_lis_shi_lin_fre_prefecture_code', 3)->comment('都道府県コード');
            $table->string('mes_lis_shi_lin_fre_country_code', 3)->comment('国コード');
            $table->string('mes_lis_shi_lin_fre_field_name', 20)->comment('産地名');
            $table->string('mes_lis_shi_lin_fre_water_area_code', 2)->comment('水域コード');
            $table->string('mes_lis_shi_lin_fre_water_area_name', 20)->comment('水域名');
            $table->string('mes_lis_shi_lin_fre_area_of_origin', 30)->comment('原産エリア');
            $table->string('mes_lis_shi_lin_fre_item_grade', 8)->comment('等級');
            $table->string('mes_lis_shi_lin_fre_item_class', 8)->comment('階級');
            $table->string('mes_lis_shi_lin_fre_brand', 30)->comment('銘柄');
            $table->string('mes_lis_shi_lin_fre_item_pr', 30)->comment('商品ＰＲ');
            $table->string('mes_lis_shi_lin_fre_bio_code', 2)->comment('バイオ区分');
            $table->string('mes_lis_shi_lin_fre_breed_code', 2)->comment('品種コード');
            $table->string('mes_lis_shi_lin_fre_cultivation_code', 2)->comment('養殖区分');
            $table->string('mes_lis_shi_lin_fre_defrost_code', 2)->comment('解凍区分');
            $table->string('mes_lis_shi_lin_fre_item_preservation_code', 2)->comment('商品状態区分');
            $table->string('mes_lis_shi_lin_fre_item_shape_code', 5)->comment('形状・部位');
            $table->string('mes_lis_shi_lin_fre_use', 20)->comment('用途');
            $table->string('mes_lis_shi_lin_sta_statutory_classification_code', 2)->comment('法定管理義務商材区分');
            $table->integer('mes_lis_shi_lin_amo_item_net_price')->comment('原価金額');
            $table->decimal('mes_lis_shi_lin_amo_item_net_price_unit_price', 13, 2)->comment('原単価');
            $table->integer('mes_lis_shi_lin_amo_item_selling_price')->comment('売価金額');
            $table->integer('mes_lis_shi_lin_amo_item_selling_price_unit_price')->comment('売単価');
            $table->integer('mes_lis_shi_lin_amo_item_tax')->comment('税額');
            $table->string('mes_lis_shi_lin_qua_unit_multiple', 4)->comment('発注単位');
            $table->string('mes_lis_shi_lin_qua_unit_of_measure', 2)->comment('発注単位コード');
            $table->string('mes_lis_shi_lin_qua_package_indicator', 2)->comment('発注荷姿コード');
            $table->decimal('mes_lis_shi_lin_qua_ord_quantity', 9, 1)->comment('発注数量（バラ）');
            $table->integer('mes_lis_shi_lin_qua_ord_num_of_order_units')->comment('発注数量（発注単位数）');
            $table->decimal('mes_lis_shi_lin_qua_shi_quantity', 9, 1)->default(0)->comment('出荷数量（バラ）');
            $table->integer('mes_lis_shi_lin_qua_shi_num_of_order_units')->default(0)->comment('出荷数量（発注単位数）');
            $table->decimal('mes_lis_shi_lin_qua_sto_quantity', 9, 1)->default(0)->comment('欠品数量(バラ数)');
            $table->integer('mes_lis_shi_lin_qua_sto_num_of_order_units')->default(0)->comment('欠品数量(発注単位数)');
            $table->string('mes_lis_shi_lin_qua_sto_reason_code', 5)->default('')->comment('欠品区分');
            $table->decimal('mes_lis_shi_lin_fre_unit_weight', 10, 3)->default(0)->comment('取引単位重量');
            $table->string('mes_lis_shi_lin_fre_unit_weight_code', 2)->comment('単価登録単位');
            $table->decimal('mes_lis_shi_lin_fre_item_weight', 10, 3)->default(0)->comment('商品重量');
            $table->decimal('mes_lis_shi_lin_fre_order_weight', 10, 3)->default(0)->comment('発注重量');
            $table->decimal('mes_lis_shi_lin_fre_shipment_weight', 10, 3)->comment('出荷重量');
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
        Schema::dropIfExists('data_shipment_items');
    }
}
