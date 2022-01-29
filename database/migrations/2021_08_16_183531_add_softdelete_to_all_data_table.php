<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftdeleteToAllDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('data_corrected_receives', function (Blueprint $table) {
            Schema::table('data_corrected_receives', function (Blueprint $table) {
                // $table->softDeletes()->after('updated_at');
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('data_corrected_receive_items', function (Blueprint $table) {
            Schema::table('data_corrected_receive_items', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_corrected_receive_vouchers', function (Blueprint $table) {
            Schema::table('data_corrected_receive_vouchers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_invoices', function (Blueprint $table) {
            Schema::table('data_invoices', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_invoice_pays', function (Blueprint $table) {
            Schema::table('data_invoice_pays', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_invoice_pay_details', function (Blueprint $table) {
            Schema::table('data_invoice_pay_details', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_order_items', function (Blueprint $table) {
            Schema::table('data_order_items', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_order_vouchers', function (Blueprint $table) {
            Schema::table('data_order_vouchers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_payments', function (Blueprint $table) {
            Schema::table('data_payments', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_payment_pays', function (Blueprint $table) {
            Schema::table('data_payment_pays', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_payment_pay_details', function (Blueprint $table) {
            Schema::table('data_payment_pay_details', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_receives', function (Blueprint $table) {
            Schema::table('data_receives', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_receive_items', function (Blueprint $table) {
            Schema::table('data_receive_items', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_receive_vouchers', function (Blueprint $table) {
            Schema::table('data_receive_vouchers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_returns', function (Blueprint $table) {
            Schema::table('data_returns', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_return_items', function (Blueprint $table) {
            Schema::table('data_return_items', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_return_vouchers', function (Blueprint $table) {
            Schema::table('data_return_vouchers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_shipments', function (Blueprint $table) {
            Schema::table('data_shipments', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_shipment_items', function (Blueprint $table) {
            Schema::table('data_shipment_items', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_shipment_item_details', function (Blueprint $table) {
            Schema::table('data_shipment_item_details', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        Schema::table('data_shipment_vouchers', function (Blueprint $table) {
            Schema::table('data_shipment_vouchers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
                // $table->softDeletes()->after('updated_at');
            });
        });
        // New Added
        Schema::table('byr_buyers', function (Blueprint $table) {
            Schema::table('byr_buyers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('slr_sellers', function (Blueprint $table) {
            Schema::table('slr_sellers', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_companies', function (Blueprint $table) {
            Schema::table('cmn_companies', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_companies_users', function (Blueprint $table) {
            Schema::table('cmn_companies_users', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_connects', function (Blueprint $table) {
            Schema::table('cmn_connects', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_scenarios', function (Blueprint $table) {
            Schema::table('cmn_scenarios', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_scenario_histories', function (Blueprint $table) {
            Schema::table('cmn_scenario_histories', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
        Schema::table('cmn_categories', function (Blueprint $table) {
            Schema::table('cmn_categories', function (Blueprint $table) {
                $table->dateTime('deleted_at')->nullable()->comment('Soft Delete')->after('updated_at');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('data_corrected_receives', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_corrected_receive_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_corrected_receive_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_invoice_pays', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_invoice_pay_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_order_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_order_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_payment_pays', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_payment_pay_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_receives', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_receive_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_receive_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_returns', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_return_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_return_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_shipments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_shipment_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_shipment_item_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('data_shipment_vouchers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
