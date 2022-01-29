<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('is-auth', function () {
    $auth = Auth::user();
    // \Log::info($auth);
    return $auth;
    // return response()->json(['user'=>$auth]);
    // return $auth;
});
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Authentication Route
Route::group(['middleware'=>'ApiMiddleWire'], function () {
    Route::apiResources(
        [
        'role' => 'API\ADM\RoleController',
        'permission' => 'API\ADM\PermissionController',
        'users' => 'API\ADM\UserController',
    ]
    );

    Route::apiResources(
        [
        // 'byrorders' => 'API\Byr_orderController',
        'byrshipments' => 'API\DATA\SHIPMENT\ShipmentController',
    ]
    );

    Route::apiResources(
        [
        'get_all_buyer' => 'API\BYR\ByrController',
    ]
    );
    Route::apiResources(
        [
        'master_item' => 'API\DATA\ORDER\OrderItemController',
    ]
    );
    Route::get('/all_users_roles', 'API\ADM\AssignRoleModel@allUsersAndRoles');
    Route::get('/get_roles/{id}', 'API\ADM\AssignRoleModel@getRoleById');
    Route::post('/assign_role_to_user', 'API\ADM\AssignRoleModel@assignModelRole');

    Route::get('/all_users_permissions', 'API\ADM\AssignPermissionModel@allUsersAndPermissions');
    Route::get('/get_permission_model/{id?}', 'API\ADM\AssignPermissionModel@getPermissionModel');
    Route::post('/assign_permission_to_user', 'API\ADM\AssignPermissionModel@assignPermissionToModel');

    Route::post('/get_permission_for_roles', 'API\ADM\AssignPermissionModel@getPermissionsByRole');
    Route::post('/permissions_by_users', 'API\ADM\AssignPermissionModel@getPermissionsByUser');
    Route::post('/change_password', 'API\ADM\UserController@changePassword');

    Route::get('/user_details/{user_id}', 'API\ADM\UserController@userDetails');
    Route::post('/users_update', 'API\ADM\UserController@update');

    Route::post('/get_byr_list_order', 'API\DATA\ORDER\OrderController@getByrListWithOrder');

    // Route::get('/home_lang_data', 'API\LanguageController@homeLangData');

    // Route::post('/permission_check', 'API\ADM\PermissionController@check');


    Route::get('/slr_job_list_by_seller_id/{slr_seller_id}', 'API\CMN\CmnJobController@slr_job_list_by_seller_id');

    Route::get('/cmn_company_user_list/{cmn_company_id?}', 'API\BYR\ByrController@cmn_company_user_list');
    // Route::get('/company_seller_user_list/{cmn_company_id}', 'API\BYR\ByrController@company_seller_user_list');
    Route::post('/company_partner_list/{cmn_company_id?}', 'API\BYR\ByrController@company_partner_list');
    Route::get('/get_byr_slr_company/{cmn_company_id?}', 'API\CMN\CommonController@get_byr_slr_company');

    Route::get('/get_scenario_list', 'API\CMN\CmnScenarioController@get_scenario_list');
    Route::post('/get_scenario_history_list', 'API\CMN\CmnScenarioController@getScenarioHistoryList');
    Route::post('/slr_company_create', 'API\SLR\SlrController@createSeller');
    Route::post('/seller_delete', 'API\SLR\SlrController@sellerDelete');
    Route::post('/seller_user_delete', 'API\SLR\SlrController@sellerUserDelete');
    Route::get('/slr_management/{adm_user_id}', 'API\SLR\SlrController@slr_management');

    Route::post('/get_order_list', 'API\DATA\ORDER\OrderController@orderList');
    Route::post('/get_order_customer_code_list', 'API\DATA\ORDER\OrderController@get_order_customer_code_list');


    Route::post('/buyerJsonSetting', 'API\BYR\ByrController@buyerJsonSetting');
    Route::get('/get_all_company_list/{adm_user_id}', 'API\BYR\ByrController@get_all_company_list');
    // Shipment
    Route::post('update_shipment_detail_bycurrentdatetime', 'API\DATA\SHIPMENT\ShipmentController@decessionData');
    Route::post('/send_shipment_data', 'API\DATA\SHIPMENT\ShipmentController@sendShipmentData');
    Route::post('/downloadcsvshipment_confirm', 'API\DATA\SHIPMENT\ShipmentController@downloadShipmentCsv');
    Route::post('/order_pdf_download', 'API\DATA\SHIPMENT\ShipmentController@pdfDownload');
    Route::post('/update_shipment_item_details', 'API\DATA\SHIPMENT\ShipmentController@update_shipment_item_details');
    Route::post('/update_shipment_item_details_from_search', 'API\DATA\SHIPMENT\ShipmentController@update_shipment_item_details_from_search');
    Route::post('/update_shipment_item_detail_form_data', 'API\DATA\SHIPMENT\ShipmentController@update_shipment_item_detail_form_data');
    Route::post('/get_all_shipment_item_by_search', 'API\DATA\SHIPMENT\ShipmentController@get_all_shipment_item_by_search');
    Route::get('/deletedownloadedshipmentCsv/{fileUrl}', 'API\DATA\SHIPMENT\ShipmentController@deletedownloadedshipmentCsv');
    // Shipment end
    Route::post('/cmn_user_create', 'API\CMN\CommonController@cmn_user_create');
    Route::post('/slr_seller_user_create', 'API\BYR\ByrController@slr_seller_user_create');
    Route::post('/create_buyer', 'API\BYR\ByrController@createBuyer');
    // Route::post('/slr_company_create', 'API\BYR\ByrController@slr_company_create');
    Route::post('/get_byr_info_by_data_order_id', 'API\BYR\ByrController@getByrByOrderId');

    // Route::post('/bms_order_save/{job_id}', 'API\BmsOrderController@store');
    Route::get('/get_data_order_byr_order_id/{byr_order_id}', 'API\DATA\ORDER\OrderController@getOrderById');
    Route::post('item_master_exec', 'API\CMN\CmnJobController@exec');
    Route::get('get_all_master_item/{adm_user_id}', 'API\DATA\ORDER\OrderItemController@masterItem');
    // receive
    Route::post('data_receive_list', 'API\DATA\RECEIVE\ReceiveController@orderReceiveList');
    Route::post('data_receive_detail_list', 'API\DATA\RECEIVE\ReceiveController@orderReceiveDetailList');
    Route::post('data_receive_detail_list_pagination', 'API\DATA\RECEIVE\ReceiveController@data_receive_detail_list_pagination');
    Route::post('data_receive_item_detail_list', 'API\DATA\RECEIVE\ReceiveController@orderReceiveItemDetailList');
    Route::post('receive_download', 'API\DATA\RECEIVE\ReceiveController@receiveDownload');
    Route::post('/get_receive_customer_code_list', 'API\DATA\RECEIVE\ReceiveController@get_receive_customer_code_list');
    Route::post('/get_voucher_detail_popup1_receive', 'API\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup1_receive');
    Route::post('/get_voucher_detail_popup2_receive', 'API\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup2_receive');
    Route::post('/get_voucher_detail_popup3_receive', 'API\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup3_receive');

    // return
    Route::post('data_return_list', 'API\DATA\RTN\ReturnController@returnList');
    Route::post('data_return_detail_list', 'API\DATA\RTN\ReturnController@returnDetailList');
    Route::post('data_return_detail_list_pagination', 'API\DATA\RTN\ReturnController@data_return_detail_list_pagination');
    Route::post('data_return_item_detail_list', 'API\DATA\RTN\ReturnController@returnItemDetailList');
    Route::post('return_download', 'API\DATA\RTN\ReturnController@returnDownload');
    Route::post('/get_return_customer_code_list', 'API\DATA\RTN\ReturnController@get_return_customer_code_list');

    Route::get('corrected_receive_list/{adm_user_id}', 'API\DATA\RECEIVE\ReceiveController@correctedReceiveList');
    // Payment Start
    Route::post('get_payment_list', 'API\DATA\PAYMENT\PaymentController@getPaymentList');
    Route::post('get_payment_detail_list', 'API\DATA\PAYMENT\PaymentController@get_payment_detail_list');
    Route::post('get_payment_item_detail_list', 'API\DATA\PAYMENT\PaymentController@get_payment_item_detail_list');
    Route::post('payment_download', 'API\DATA\PAYMENT\PaymentController@paymentDownload');
    Route::post('/get_payment_customer_code_list', 'API\DATA\PAYMENT\PaymentController@get_payment_customer_code_list');
    Route::post('/get_payment_trade_code_list', 'API\DATA\PAYMENT\PaymentController@get_payment_trade_code_list');
    Route::post('/unpaid_payment_list', 'API\DATA\PAYMENT\PaymentController@unpaidPaymentPist');
    Route::post('payment_unpaid_data_download', 'API\DATA\PAYMENT\PaymentController@paymentUnpaidDataDownload');
    Route::post('payment_pdf_download', 'API\DATA\PAYMENT\PaymentController@paymentPdfDownload');

    Route::get('get_byr_return_list/{adm_user_id}', 'API\DATA\RTN\ReturnController@getReturnItemList');
    Route::post('get_all_cat_list', 'API\CMN\CmnCategoryController@get_all_cat_list');

    // Invoice
    Route::post('get_all_invoice_list', 'API\DATA\INVOICE\InvoiceController@get_all_invoice_list');
    Route::post('invoiceInsert', 'API\DATA\INVOICE\InvoiceController@invoiceInsert');
    Route::post('exec_invoice_schedular', 'API\DATA\INVOICE\InvoiceController@execInvoiceSchedular');
    Route::post('update_invoice_detail', 'API\DATA\INVOICE\InvoiceController@update_invoice_detail');
    Route::post('delete_invoice_detail', 'API\DATA\INVOICE\InvoiceController@delete_invoice_detail');
    Route::post('get_invoice_details_list', 'API\DATA\INVOICE\InvoiceController@invoiceDetailsList');
    Route::post('get_voucher_detail_popup2_invoice', 'API\DATA\INVOICE\InvoiceController@get_voucher_detail_popup2_invoice');
    Route::post('invoice_compare_data', 'API\DATA\INVOICE\InvoiceController@invoiceCompareData');
    Route::post('invoice_compare_data_download', 'API\DATA\INVOICE\InvoiceController@invoiceCompareDataDownload');
    Route::post('invoice_compare_item', 'API\DATA\INVOICE\InvoiceController@invoiceCompareItem');
    Route::post('send_invoice_data', 'API\DATA\INVOICE\InvoiceController@sendInvoiceData');
    Route::post('update_invoice_decession_datetime', 'API\DATA\INVOICE\InvoiceController@decessionData');
    Route::post('update_invoice_check_datetime', 'API\DATA\INVOICE\InvoiceController@checkdatetimeData');
    Route::post('update_invoice_pay_details_amount', 'API\DATA\INVOICE\InvoiceController@amountDataUpdate');
    Route::post('download_invoice', 'API\DATA\INVOICE\InvoiceController@downloadInvoice');
    Route::get('get_all_invoice_by_voucher_number/{voucher_number}', 'API\DATA\INVOICE\InvoiceController@get_all_invoice_by_voucher_number');
    Route::post('/get_invoice_customer_code_list', 'API\DATA\INVOICE\InvoiceController@get_invoice_customer_code_list');
    // Stock
    Route::post('/get_stock_item_list', 'API\DATA\STOCK\StockItemController@stockItemList');
    Route::post('/get_stock_item_code_list', 'API\DATA\STOCK\StockItemController@stockItemCodeList');

    //
    Route::post('/cmn_category_create', 'API\CMN\CmnCategoryController@store');
    Route::post('/uploadByrCategoryCsv', 'API\CMN\CmnCategoryController@uploadByrCategoryCsv');

    // Canvas
    Route::post('/load_canvas_setting_data', 'API\CANVAS\CanvasController@canvasSettingData');
    Route::post('/canvas_data_save', 'API\CANVAS\CanvasController@canvasDataSave');
    Route::post('/load_canvas_data', 'API\CANVAS\CanvasController@canvasAllData');
    Route::post('/delete_canvas', 'API\CANVAS\CanvasController@deleteCanvasData');

    Route::post('/shipment_update', 'API\DATA\SHIPMENT\ShipmentController@shipmentUpdate');

    Route::post('/get_permissions_for_buyer', 'API\BYR\ByrController@getPermissionForBuyer');
    Route::post('/get_seller_list', 'API\SLR\SlrController@getSellerList');
    Route::post('/buyer_partner_create', 'API\BYR\ByrController@buyerPartnerCreate');
    Route::post('/buyer_partner_delete', 'API\BYR\ByrController@buyerPartnerDelete');
    // Route::post('/order_create_fixed_length', 'API\BmsOrderController@orderCreateDeleteFixedLength');
    // Route::post('/delete_old_files', 'API\BmsOrderController@orderCreateDeleteFixedLength');
    // Pdf Platform
    Route::post('/load_pdf_platform_canvas_setting_data', 'API\PDF\PdfPlatformSettingController@canvasSettingData');
    Route::post('/pdf_platform_canvas_data_save', 'API\PDF\PdfPlatformSettingController@canvasDataSave');
    Route::post('load_pdf_platform_canvas_data', 'API\PDF\PdfPlatformController@pdfPlatformAllData');
    Route::post('/delete_pdf_platform_canvas', 'API\PDF\PdfPlatformSettingController@deleteCanvasData');
    // Pdf Platform End
    // Blog
    Route::post('/blog_create', 'API\CMN\CmnBlogController@store');
    Route::get('/get_all_blog_list', 'API\CMN\CmnBlogController@index');
    Route::get('/get_all_published_blog_list', 'API\CMN\CmnBlogController@get_all_published_blog_list');
    Route::get('/get_signle_top_blog', 'API\CMN\CmnBlogController@get_signle_top_blog');
    Route::get('/get_user_top_blog', 'API\CMN\CmnBlogController@get_user_top_blog');
    Route::get('/get_user_top_blog_by_byr_id/{byr_buyer_id}', 'API\CMN\CmnBlogController@get_user_top_blog_by_byr_id');
    Route::post('/update_blog_infos', 'API\CMN\CmnBlogController@update_blog_infos');
    Route::post('/ckeditor_file_up', 'API\CMN\CmnBlogController@ckeditor_file_up');

    Route::post('/get_byr_order_data_by_slr', 'API\DATA\ORDER\OrderController@getByrOrderDataBySlr');
    Route::post('/get_logged_user_company_by_user_id', 'API\CMN\CommonController@get_logged_user_company_by_user_id');
    Route::post('/order_details', 'API\DATA\ORDER\OrderController@orderDetails');
    Route::post('/order_detail_paginations', 'API\DATA\ORDER\OrderController@order_detail_paginations');
    Route::post('/get_voucher_detail_popup1', 'API\DATA\ORDER\OrderController@get_voucher_detail_popup1');
    Route::post('/get_voucher_detail_popup2', 'API\DATA\ORDER\OrderController@get_voucher_detail_popup2');
    Route::post('/get_voucher_detail_popup3', 'API\DATA\ORDER\OrderController@get_voucher_detail_popup3');

    Route::post('/get_voucher_detail_popup1_return', 'API\DATA\RTN\ReturnController@get_voucher_detail_popup1_return');
    Route::post('/get_voucher_detail_popup2_return', 'API\DATA\RTN\ReturnController@get_voucher_detail_popup2_return');
    Route::post('/get_voucher_detail_popup3_return', 'API\DATA\RTN\ReturnController@get_voucher_detail_popup3_return');
    Route::get('/order_item_details/{data_shipment_voucher_id}', 'API\DATA\ORDER\OrderItemController@orderItemDetails');
    Route::post('/shipment_item_detail_search', 'API\DATA\ORDER\OrderItemController@shipmentItemDetailSearch');
    Route::get('slr_job_list_all', 'API\CMN\CmnJobController@index');
    //get user company byr slr list
    Route::post('/get_user_company_byr_slr_list', 'API\ADM\UserController@get_user_company_byr_slr_list');
    Route::get('/get_selected_byr_info/{byr_buyer_id}', 'API\BYR\ByrController@get_selected_byr_info');
    Route::post('/update_cmn_connects_optional', 'API\CMN\CmnConnectsController@update_cmn_connects_optional');
    Route::post('/update_cmn_connects_optionalAllJson', 'API\CMN\CmnConnectsController@update_cmn_connects_optionalAllJson');
    Route::get('/get_allInvoiceJsonSetting_info', 'API\CMN\CmnConnectsController@get_allInvoiceJsonSetting_info');
    Route::post('/get_partner_fax_list', 'API\CMN\CmnConnectsController@get_partner_fax_list');
    // User Manual
    Route::post('/download_user_manual', 'API\CMN\CommonController@userManualDownload');
    Route::post('/getUserManual', 'API\CMN\CommonController@getUserManual');
    Route::post('/zip_file_update', 'API\CMN\CommonController@zipFileUpdate');

    // Byr Menu Url
    Route::post('/get_slr_order_list', 'API\BYR\DATA\ORDER\SlrOrderController@slrOrderList');
    Route::post('/get_slr_customer_code_list', 'API\BYR\DATA\ORDER\SlrOrderController@slrCustomerCodeList');
    Route::post('/slr_order_details', 'API\BYR\DATA\ORDER\SlrOrderController@slrOrderDetails');
    Route::post('/slr_get_voucher_shi_code_list', 'API\BYR\DATA\ORDER\SlrOrderController@getVoucherShiCodeList');
    Route::post('/slr_get_voucher_rec_code_list', 'API\BYR\DATA\ORDER\SlrOrderController@getVoucherRecCodeList');
    Route::post('/slr_get_voucher_item_code_list', 'API\BYR\DATA\ORDER\SlrOrderController@getVoucherItemCodeList');
    Route::post('/slr_shipment_item_detail_search', 'API\BYR\DATA\ORDER\SlrOrderController@shipmentItemDetailSearch');
    Route::post('/order_delete_or_retrive', 'API\BYR\DATA\ORDER\SlrOrderController@orderDeleteRetrive');
    // Shipment
    Route::post('slr_shipment_download', 'API\BYR\DATA\SHIPMENT\SlrShipmentController@slrShipmentDownload');
    Route::post('slr_order_pdf_download', 'API\BYR\DATA\SHIPMENT\SlrShipmentController@slrPdfDownload');
    Route::post('slr_get_shipment_item_search', 'API\BYR\DATA\SHIPMENT\SlrShipmentController@getShipmentItemSearch');
    Route::post('slr_update_shipment_item_details', 'API\BYR\DATA\SHIPMENT\SlrShipmentController@update_shipment_item_details');
    Route::post('slr_update_shipment_item_details_from_search', 'API\BYR\DATA\SHIPMENT\SlrShipmentController@update_shipment_item_details_from_search');

    //slr receive
    Route::post('slr_data_receive_list', 'API\BYR\DATA\RECEIVE\ReceiveController@orderReceiveList');
    Route::post('slr_data_receive_detail_list', 'API\BYR\DATA\RECEIVE\ReceiveController@orderReceiveDetailList');
    Route::post('slr_data_receive_detail_list_pagination', 'API\BYR\DATA\RECEIVE\ReceiveController@data_receive_detail_list_pagination');
    Route::post('slr_data_receive_item_detail_list', 'API\BYR\DATA\RECEIVE\ReceiveController@orderReceiveItemDetailList');
    Route::post('slr_receive_download', 'API\BYR\DATA\RECEIVE\ReceiveController@receiveDownload');
    Route::post('/slr_get_receive_customer_code_list', 'API\BYR\DATA\RECEIVE\ReceiveController@get_receive_customer_code_list');
    Route::post('/slr_get_voucher_detail_popup1_receive', 'API\BYR\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup1_receive');
    Route::post('/slr_get_voucher_detail_popup2_receive', 'API\BYR\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup2_receive');
    Route::post('/slr_get_voucher_detail_popup3_receive', 'API\BYR\DATA\RECEIVE\ReceiveController@get_voucher_detail_popup3_receive');
    Route::post('/receive_delete_or_retrive', 'API\BYR\DATA\RECEIVE\ReceiveController@receiveDeleteRetrive');
    // slr_return
    Route::post('slr_data_return_list', 'API\BYR\DATA\RTN\ReturnController@returnList');
    Route::post('slr_data_return_detail_list', 'API\BYR\DATA\RTN\ReturnController@returnDetailList');
    Route::post('slr_data_return_detail_list_pagination', 'API\BYR\DATA\RTN\ReturnController@data_return_detail_list_pagination');
    Route::post('slr_data_return_item_detail_list', 'API\BYR\DATA\RTN\ReturnController@returnItemDetailList');
    Route::post('slr_return_download', 'API\BYR\DATA\RTN\ReturnController@returnDownload');
    Route::post('slr_get_return_customer_code_list', 'API\BYR\DATA\RTN\ReturnController@get_return_customer_code_list');
    Route::post('slr_get_voucher_detail_popup1_return', 'API\BYR\DATA\RTN\ReturnController@get_voucher_detail_popup1_return');
    Route::post('slr_get_voucher_detail_popup2_return', 'API\BYR\DATA\RTN\ReturnController@get_voucher_detail_popup2_return');
    Route::post('slr_get_voucher_detail_popup3_return', 'API\BYR\DATA\RTN\ReturnController@get_voucher_detail_popup3_return');
    Route::post('return_delete_or_retrive', 'API\BYR\DATA\RTN\ReturnController@returnDeleteRetrive');

    // slr_Payment
    Route::post('slr_get_payment_list', 'API\BYR\DATA\PAYMENT\PaymentController@getPaymentList');
    Route::post('slr_get_payment_detail_list', 'API\BYR\DATA\PAYMENT\PaymentController@get_payment_detail_list');
    Route::post('slr_get_payment_item_detail_list', 'API\BYR\DATA\PAYMENT\PaymentController@get_payment_item_detail_list');
    Route::post('slr_payment_download', 'API\BYR\DATA\PAYMENT\PaymentController@paymentDownload');
    Route::post('/slr_get_payment_customer_code_list', 'API\BYR\DATA\PAYMENT\PaymentController@get_payment_customer_code_list');
    Route::post('/slr_get_payment_trade_code_list', 'API\BYR\DATA\PAYMENT\PaymentController@get_payment_trade_code_list');
    Route::post('/slr_unpaid_payment_list', 'API\BYR\DATA\PAYMENT\PaymentController@unpaidPaymentPist');
    Route::post('slr_payment_unpaid_data_download', 'API\BYR\DATA\PAYMENT\PaymentController@paymentUnpaidDataDownload');
    Route::post('payment_delete_or_retrive', 'API\BYR\DATA\PAYMENT\PaymentController@paymentDeleteRetrive');

    //slr Invoice
    Route::post('slr_get_all_invoice_list', 'API\BYR\DATA\INVOICE\InvoiceController@get_all_invoice_list');
    Route::post('slr_get_invoice_details_list', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceDetailsList');
    Route::post('slr_get_voucher_detail_popup2_invoice', 'API\BYR\DATA\INVOICE\InvoiceController@get_voucher_detail_popup2_invoice');
    Route::post('slr_download_invoice', 'API\BYR\DATA\INVOICE\InvoiceController@downloadInvoice');
    Route::post('slr_invoice_compare_data', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceCompareData');
    Route::post('slr_invoice_compare_data_download', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceCompareDataDownload');
    Route::post('slr_invoice_compare_item', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceCompareItem');
    Route::post('/slr_get_invoice_customer_code_list', 'API\BYR\DATA\INVOICE\InvoiceController@getInvoiceCustomerCodeList');
    Route::post('/invoice_delete_or_retrive', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceDeleteRetrive');
    Route::post('/invoice_details_delete_or_retrive', 'API\BYR\DATA\INVOICE\InvoiceController@invoiceDetailsDeleteRetrive');

    // Stock
    Route::post('/slr_get_stock_item_list', 'API\BYR\DATA\STOCK\SlrStockItemController@stockItemList');
    Route::post('/slr_get_stock_item_code_list', 'API\BYR\DATA\STOCK\SlrStockItemController@stockItemCodeList');
    Route::post('/slr_get_partner_code_list', 'API\BYR\DATA\STOCK\SlrStockItemController@slrStockItemPartnerCodeList');

    //super Admin data test
    Route::post('/super_admin_data_count_list', 'API\CMN\CommonController@cmnDataCount');
    Route::post('/super_admin_test_data_delete', 'API\CMN\CommonController@cmnTestDataDelete');

});
// scenario exec
Route::post('scenario_exec', 'API\CMN\CmnScenarioController@exec');
// Job Exec
Route::post('job_exec', 'API\CMN\CmnJobController@exec');
// Level3
// Route::post('get_shipment_file', 'API\LV3\Level3Controller@getShipmentFile');
// Route::post('get_invoice_file', 'API\LV3\Level3Controller@getInvoiceFile');
Route::post('delete_service', 'API\LV3\Level3Controller@deleteService');
Route::post('/job_list', 'API\LV3\Level3Controller@job_list');
Route::post('get_user_login', 'API\LV3\Level3Controller@userLogin');
Route::post('history_url', 'API\LV3\Level3Controller@historyData');
Route::post('get_customer', 'API\LV3\Level3Controller@getCustomer');
// Route::post('delete_customer', 'API\LV3\Level3Controller@deleteCustomer');
Route::post('add_service', 'API\LV3\Level3Controller@addService');
Route::post('get_schedule_data', 'API\LV3\Level3Controller@scheduleData');
Route::post('set_schedule_data', 'API\LV3\Level3Controller@setScheduleData');
Route::post('set_file_path', 'API\LV3\Level3Controller@setFilePath');
Route::post('set_job_data', 'API\LV3\Level3Controller@setJobData');
Route::post('lv3_schedule_data', 'API\LV3\Level3Controller@lv3ScheduleData');
Route::post('get_service_data', 'API\LV3\Level3Controller@getServiceData');
Route::post('history_create', 'API\LV3\Level3Controller@historyCreate');
Route::post('job_scenario', 'API\LV3\Level3Controller@jobScenario');
