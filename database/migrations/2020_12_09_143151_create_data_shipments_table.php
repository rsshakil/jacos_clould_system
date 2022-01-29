<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDataShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_shipments', function (Blueprint $table) {
            $table->increments('data_shipment_id')->unsigned()->comment('data_shipment_id');
            $table->integer('data_order_id')->unsigned()->comment('data_order_id');
            $table->integer('cmn_connect_id')->unsigned()->nullable()->comment('cmn_connect_id');
            $table->dateTime('upload_datetime')->nullable()->comment('アップロード日時');
            $table->string('upload_file_path', 200)->nullable()->comment('upload_file_path');
            $table->dateTime('send_datetime')->nullable()->comment('送信日時');
            $table->string('send_file_path', 200)->nullable()->comment('送信ファイルパス');
            $table->string('sta_sen_identifier', 30)->comment('送信者ＩＤ');
            $table->string('sta_sen_ide_authority', 10)->comment('送信者ＩＤ発行元');
            $table->string('sta_rec_identifier', 20)->comment('受信者ＩＤ');
            $table->string('sta_rec_ide_authority', 10)->comment('受信者ＩＤ発行元');
            $table->string('sta_doc_standard', 20)->comment('標準名称');
            $table->string('sta_doc_type_version', 10)->comment('バージョン');
            $table->string('sta_doc_instance_identifier', 50)->comment('インスタンスＩＤ');
            $table->string('sta_doc_type', 30)->comment('メッセージ種');
            $table->dateTime('sta_doc_creation_date_and_time')->comment('作成日時');
            $table->string('sta_bus_scope_type', 20)->comment('タイプ');
            $table->string('sta_bus_scope_instance_identifier', 20)->comment('テスト区分・最終送信先');
            $table->string('sta_bus_scope_identifier', 20)->comment('テスト区分・最終送信先ＩＤ');
            $table->string('mes_ent_unique_creator_identification', 80)->comment('メッセージ識別ＩＤ');
            $table->string('mes_mes_sender_station_address', 8)->comment('送信者ステーションアドレス');
            $table->string('mes_mes_ultimate_receiver_station_address', 8)->comment('最終受信者ステーションアドレス');
            $table->string('mes_mes_immediate_receiver_station_addres', 8)->comment('直接受信者ステーションアドレス');
            $table->string('mes_mes_number_of_trading_documents', 7)->comment('取引数');
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
            $table->smallInteger('deleted')->default(1)->comment('削除フラグ');
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
        Schema::dropIfExists('data_shipments');
    }
}
