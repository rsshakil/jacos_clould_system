<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class CreateBmsCorrectedReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bms_corrected_receives', function (Blueprint $table) {
            $table->increments('bms_corrected_receive_id')->unsigned()->comment('bms_corrected_receive');
            $table->integer('byr_corrected_receive_id')->unsigned()->comment('byr_corrected_receive_id');
            $table->string('sta_sen_identifier', 30)->comment('送信者ＩＤ');
            $table->string('sta_sen_ide_authority', 10)->comment('送信者ＩＤ発行元');
            $table->string('sta_rec_identifier',20)->comment('受信者ＩＤ');
            $table->string('sta_rec_ide_authority', 10)->comment('受信者ＩＤ発行元');
            $table->string('sta_doc_standard', 20)->comment('標準名称');
            $table->string('sta_doc_type_ersion', 10)->comment('バージョン');
            $table->string('sta_doc_instance_identifier', 50)->comment('インスタンスＩＤ');
            $table->string('sta_doc_type', 10)->comment('メッセージ種');
            $table->dateTime('sta_doc_creation_date_and_time')->comment('作成日時');
            $table->string('sta_bus_scope_type', 20)->comment('タイプ');
            $table->string('sta_bus_scope_instance_identifier', 20)->comment('テスト区分ＩＤ');
            $table->string('sta_bus_scope_identifier', 20)->comment('最終送信先ＩＤ');
            $table->string('mes_ent_unique_creator_identification', 80)->comment('メッセージ識別ＩＤ');
            $table->string('mes_mes_sender_station_address', 8)->comment('送信者ステーションアドレス');
            $table->string('mes_mes_ultimate_receiver_station_address', 8)->comment('最終受信者ステーションアドレス');
            $table->string('mes_mes_immediate_receiver_station_addres', 8)->comment('直接受信者ステーションアドレス');
            $table->string('mes_mes_number_of_trading_documents', 7)->comment('取引数');
            $table->string('mes_mes_sys_key', 20)->comment('キー');
            $table->string('mes_mes_sys_value', 20)->comment('値');
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

            $table->string('mes_lis_acc_tra_trade_number', 10)->comment('取引番号（発注・返品）');
            $table->string('mes_lis_acc_tra_additional_trade_number', 10)->comment('取引付属番号');
            $table->string('mes_lis_acc_fre_shipment_number', 11)->comment('出荷者管理番号');
            $table->string('mes_lis_acc_par_shi_code', 13)->comment('直接納品先コード');
            $table->string('mes_lis_acc_par_shi_gln', 13)->comment('直接納品先GLN');
            $table->string('mes_lis_acc_par_shi_name', 20)->comment('直接納品先名称');
            $table->string('mes_lis_acc_par_shi_name_sbcs', 20)->comment('直接納品先名称カナ');
            $table->string('mes_lis_acc_par_rec_code', 13)->comment('最終納品先コード');
            $table->string('mes_lis_acc_par_rec_gln', 13)->comment('最終納品先GLN');
            $table->string('mes_lis_acc_par_rec_name', 20)->comment('最終納品先名称');
            $table->string('mes_lis_acc_par_rec_name_sbcs', 20)->comment('最終納品先名称カナ');
            $table->string('mes_lis_acc_par_tra_code', 13)->comment('計上部署コード');
            $table->string('mes_lis_acc_par_tra_gln', 13)->comment('計上部署GLN');
            $table->string('mes_lis_acc_par_tra_name', 20)->comment('計上部署名称');
            $table->string('mes_lis_acc_par_tra_name_sbcs', 20)->comment('計上部署名称（カナ）');
            $table->string('mes_lis_acc_par_pay_code', 13)->comment('請求取引先コード');
            $table->string('mes_lis_acc_par_pay_gln', 13)->comment('請求取引先GLN');
            $table->string('mes_lis_acc_par_pay_name', 20)->comment('請求取引先名');
            $table->string('mes_lis_acc_par_pay_name_sbcs', 20)->comment('請求取引先名カナ');
            $table->string('mes_lis_acc_par_sel_code', 13)->comment('取引先コード');
            $table->string('mes_lis_acc_par_sel_gln', 13)->comment('取引先GLN');
            $table->string('mes_lis_acc_par_sel_name', 20)->comment('取引先名称');
            $table->string('mes_lis_acc_par_sel_name_sbcs', 20)->comment('取引先名称カナ');
            $table->string('mes_lis_acc_par_sel_branch_number', 2)->comment('枝番');
            $table->string('mes_lis_acc_par_sel_ship_location_code', 4)->comment('出荷先コード');
            $table->string('mes_lis_acc_tra_goo_major_category', 20)->comment('商品分類（大）');
            $table->string('mes_lis_acc_tra_goo_sub_major_category', 20)->comment('商品分類（中）');
            $table->date('mes_lis_acc_tra_dat_transfer_of_ownership_date')->comment('計上日');
            $table->string('mes_lis_acc_tra_ins_goods_classification_code',2)->comment('商品区分');
            $table->string('mes_lis_acc_tra_ins_order_classification_code',2)->comment('発注区分');
            $table->string('mes_lis_acc_tra_ins_ship_notification_request_code',2)->comment('出荷データ有無区分');
            $table->string('mes_lis_acc_tra_ins_eos_code',2)->comment('EOS区分');
            $table->string('mes_lis_acc_tra_ins_private_brand_code',2)->comment('PB区分');
            $table->string('mes_lis_acc_tra_ins_temperature_code',2)->comment('配送温度区分');
            $table->string('mes_lis_acc_tra_ins_liquor_code',2)->comment('酒区分');
            $table->string('mes_lis_acc_tra_ins_trade_type_code',2)->comment('処理種別');
            $table->string('mes_lis_acc_tra_ins_paper_form_less_code',2)->comment('伝票レス区分');
            $table->string('mes_lis_acc_tra_ins_delivery_fee_exemption_code',2)->comment('配送料免除区分');
            $table->string('mes_lis_acc_tra_fre_trade_number_request_code',2)->comment('取引番号区分');
            $table->string('mes_lis_acc_tra_fre_package_code',2)->comment('パック区分');
            $table->string('mes_lis_acc_tra_fre_variable_measure_item_code',2)->comment('不定貫区分');
            $table->string('mes_lis_acc_tra_tax_tax_type_code',2)->comment('税区分');
            $table->string('mes_lis_acc_tra_tax_tax_rate',6)->comment('税率');
            $table->string('mes_lis_acc_tra_not_text',60)->comment('自由使用欄');
            $table->string('mes_lis_acc_tra_not_text_sbcs',60)->comment('自由使用欄半角カナ');
            $table->integer('mes_lis_acc_tot_tot_net_price_total')->comment('原価金額合計');
            $table->integer('mes_lis_acc_tot_tot_selling_price_total')->comment('売価金額合計');
            $table->integer('mes_lis_acc_tot_tot_tax_total')->comment('税額合計金額');
            $table->integer('mes_lis_acc_tot_tot_item_total')->comment('数量合計');
            $table->integer('mes_lis_acc_tot_tot_unit_total')->comment('発注単位数量合計');
            $table->integer('mes_lis_acc_tot_fre_unit_weight_total')->comment('重量合計');

            $table->string('mes_lis_acc_lin_lin_line_number',4)->comment('取引明細番号（発注・返品）');
            $table->string('mes_lis_acc_lin_lin_additional_line_number',4)->comment('取引付属明細番号');
            $table->string('mes_lis_acc_lin_fre_trade_number',10)->comment('元取引番号');
            $table->string('mes_lis_acc_lin_fre_line_number',10)->comment('元取引明細番号');
            $table->string('mes_lis_acc_lin_fre_shipment_line_number',10)->comment('出荷者管理明細番号');
            $table->string('mes_lis_acc_lin_goo_minor_category',10)->comment('商品分類（小）');
            $table->string('mes_lis_acc_lin_goo_detailed_category',10)->comment('商品分類（細）');
            $table->string('mes_lis_acc_lin_reason_code',10)->comment('返品・値引理由コード');
            $table->string('mes_lis_acc_lin_ite_maker_code',13)->comment('メーカーコード');
            $table->string('mes_lis_acc_lin_ite_gtin',14)->comment('商品コード（ＧTIN）');
            $table->string('mes_lis_acc_lin_ite_order_item_code',14)->comment('商品コード（発注用）');
            $table->string('mes_lis_acc_lin_ite_ord_code_type',3)->comment('商品コード区分');
            $table->string('mes_lis_acc_lin_ite_supplier_item_code',14)->comment('商品コード（取引先）');
            $table->string('mes_lis_acc_lin_ite_name',25)->comment('商品名');
            $table->string('mes_lis_acc_lin_ite_name_sbcs',25)->comment('商品名カナ');
            $table->string('mes_lis_acc_lin_fre_shipment_item_code',10)->comment('商品コード（出荷元）');
            $table->string('mes_lis_acc_lin_ite_ite_spec',25)->comment('規格');
            $table->string('mes_lis_acc_lin_ite_ite_spec_sbcs',25)->comment('規格カナ');
            $table->string('mes_lis_acc_lin_ite_col_color_code',10)->comment('カラーコード');
            $table->string('mes_lis_acc_lin_ite_col_description',20)->comment('カラー名称');
            $table->string('mes_lis_acc_lin_ite_col_description_sbcs',20)->comment('カラー名称カナ');
            $table->string('mes_lis_acc_lin_ite_siz_size_code',10)->comment('サイズコード');
            $table->string('mes_lis_acc_lin_ite_siz_description',30)->comment('サイズ名称');
            $table->string('mes_lis_acc_lin_ite_siz_description_sbcs',30)->comment('サイズ名称カナ');
            $table->string('mes_lis_acc_lin_fre_packing_quantity',20)->comment('入数');
            $table->string('mes_lis_acc_lin_fre_prefecture_code',20)->comment('都道府県コード');
            $table->string('mes_lis_acc_lin_fre_country_code',20)->comment('国コード');
            $table->string('mes_lis_acc_lin_fre_field_name',20)->comment('産地名');
            $table->string('mes_lis_acc_lin_fre_water_area_code',2)->comment('水域コード');
            $table->string('mes_lis_acc_lin_fre_water_area_name',20)->comment('水域名');
            $table->string('mes_lis_acc_lin_fre_area_of_origin',20)->comment('原産エリア');
            $table->string('mes_lis_acc_lin_fre_item_grade',20)->comment('等級');
            $table->string('mes_lis_acc_lin_fre_item_class',20)->comment('階級');
            $table->string('mes_lis_acc_lin_fre_brand',20)->comment('銘柄');
            $table->string('mes_lis_acc_lin_fre_item_pr',20)->comment('商品ＰＲ');
            $table->string('mes_lis_acc_lin_fre_bio_code',20)->comment('バイオ区分');
            $table->string('mes_lis_acc_lin_fre_breed_code',20)->comment('品種コード');
            $table->string('mes_lis_acc_lin_fre_cultivation_code',20)->comment('養殖区分');
            $table->string('mes_lis_acc_lin_fre_defrost_code',20)->comment('解凍区分');
            $table->string('mes_lis_acc_lin_fre_item_preservation_code',20)->comment('商品状態区分');
            $table->string('mes_lis_acc_lin_fre_item_shape_code',20)->comment('形状・部位');
            $table->string('mes_lis_acc_lin_fre_use',20)->comment('用途');
            $table->string('mes_lis_acc_lin_sta_statutory_classification_code',20)->comment('法定管理義務商材区分');
            $table->string('mes_lis_acc_lin_amo_item_net_price')->comment('原価金額');
            $table->string('mes_lis_acc_lin_amo_item_net_price_unit_price',20)->comment('原単価');
            $table->string('mes_lis_acc_lin_amo_item_selling_price')->comment('売価金額');
            $table->string('mes_lis_acc_lin_amo_item_selling_price_unit_price')->comment('売単価');
            $table->string('mes_lis_acc_lin_amo_item_tax',20)->comment('税額');
            $table->string('mes_lis_acc_lin_qua_rev_quantity',10)->comment('訂正数量（バラ）');
            $table->string('mes_lis_acc_lin_fre_unit_weight_code',20)->comment('単価登録単位');
            $table->string('mes_lis_acc_lin_fre_item_weight',20)->comment('商品重量');
            $table->string('mes_lis_acc_lin_fre_received_weight',20)->comment('受領重量');
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
        Schema::dropIfExists('bms_corrected_receives');
    }
}
