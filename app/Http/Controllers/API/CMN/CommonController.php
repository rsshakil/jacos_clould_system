<?php

namespace App\Http\Controllers\API\CMN;

use App\Http\Controllers\API\AllUsedFunction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ADM\adm_user_details;
use App\Models\ADM\User;
use App\Models\BYR\byr_buyer;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use App\Models\SLR\slr_seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

use App\Models\DATA\ORD\data_order_voucher;
use App\Models\DATA\ORD\data_order;
use App\Models\DATA\ORD\data_order_item;

use App\Models\DATA\INVOICE\data_invoice;
use App\Models\DATA\INVOICE\data_invoice_pay;
use App\Models\DATA\INVOICE\data_invoice_pay_detail;

use App\Models\DATA\PAYMENT\data_payment;
use App\Models\DATA\PAYMENT\data_payment_pay;
use App\Models\DATA\PAYMENT\data_payment_pay_detail;

use App\Models\DATA\RCV\data_receive;
use App\Models\DATA\RCV\data_receive_voucher;
use App\Models\DATA\RCV\data_receive_item;

use App\Models\DATA\RTN\data_return;
use App\Models\DATA\RTN\data_return_voucher;
use App\Models\DATA\RTN\data_return_item;

use App\Models\DATA\SHIPMENT\data_shipment;
use App\Models\DATA\SHIPMENT\data_shipment_item;
use App\Models\DATA\SHIPMENT\data_shipment_item_detail;
use App\Models\DATA\SHIPMENT\data_shipment_voucher;

class CommonController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
        $this->all_used_fun->folder_create('app/'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH'));
    }
    public function get_byr_slr_company($cmn_company_id = null)
    {
        $buyer_info = byr_buyer::select('byr_buyers.byr_buyer_id', 'cmn_companies.cmn_company_id', 'cmn_companies.company_name as buyer_name')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'byr_buyers.cmn_company_id')
            ->get();
        $seller_info = slr_seller::select('slr_sellers.slr_seller_id', 'cmn_companies.cmn_company_id', 'cmn_companies.company_name as seller_name')
            ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'slr_sellers.cmn_company_id')
            ->get();
        return response()->json(['buyer_info' => $buyer_info, 'seller_info' => $seller_info]);
    }

    public function cmn_user_create(Request $request)
    {
        $adm_user_id = $request->adm_user_id;
        if ($adm_user_id == null) {
            $this->validate($request, [
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191|unique:adm_users',
                'password' => 'required|string|min:6',
                'cmn_company_id' => 'required',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|max:191',
                'email' => 'required|string|email|max:191',
                'cmn_company_id' => 'required',
            ]);
        }

        $cmn_company_id = $request->cmn_company_id;

        $user_info = $this->buyer_or_saller_user_store($request);
        if ($user_info['message'] == 'created') {
            cmn_companies_user::insert(['cmn_company_id' => $cmn_company_id, 'adm_user_id' => $user_info['last_user_id']]);
        }
        return response()->json($user_info);
    }
    /**
     * Get user id as buyer or saller id
     * @param  array $request_array with contain name,email,password
     * @return array user_id
     */
    public function buyer_or_saller_user_store($request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $adm_user_id = $request->adm_user_id;
        $cmn_company_id = $request->cmn_company_id;
        $cmn_company_info = cmn_company::select('company_type')->where('cmn_company_id',$cmn_company_id)->first();

        $user_array = array(
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        );

        $user_exist = User::where('email', $email)->first();
        if ($adm_user_id != null) {
            $exist_user_info = User::where('id', $adm_user_id)->first();
            if ($exist_user_info->email != $email) {
                if ($user_exist) {
                    return array('title' => "Exists!", 'message' => "exists", 'class_name' => 'error');
                }
            }
            if ($password == null) {
                $user_array['password'] = $exist_user_info->password;
            }
            User::where('id', $adm_user_id)->update($user_array);
            return array('title' => "Updated!", 'message' => "updated", 'class_name' => 'success');
        } else {
            if ($user_exist) {
                return array('title' => "Exists!", 'message' => "exists", 'class_name' => 'error');
            } else {
                $last_user_id = User::insertGetId($user_array);
                adm_user_details::insert(['user_id' => $last_user_id]);
                $user = User::findOrFail($last_user_id);
                if ($cmn_company_info->company_type=='seller') {
                    $user->assignRole(config('const.seller_role_name'));
                }else if($cmn_company_info->company_type=='buyer'){
                    $user->assignRole(config('const.buyer_role_name'));
                }
            }
            return array('title' => "Created!", 'message' => "created", 'class_name' => 'success', 'last_user_id' => $last_user_id);
        }
    }
    /**
     * get_logged_user_company_by_user_id
     *
     * @param  mixed $request
     * @return void
     */
    public function get_logged_user_company_by_user_id(Request $request)
    {
        Log::debug(__METHOD__.':start---');

        $adm_user_id = $request->user_id;
        $authUser = User::find($adm_user_id);
        $result = array();
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $result = $this->all_used_fun->get_user_info($adm_user_id);
        }
        Log::debug(__METHOD__.':end---');
        return response()->json(['userCompanyInfo' => $result]);
    }
    /**
     * Get user manual list
     *
     * @param  mixed $request
     * @return array
     */
    public function userManualDownload(Request $request)
    {
        Log::debug(__METHOD__.':start---');
        $download_file_url=null;
        $file_name=null;
        $path=storage_path('app'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH'));
        $files = array_diff(scandir($path), array('.', '..'));
        if (!empty($files)) {
            $download_file_url=Config::get('app.url')."storage/app".config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH').'/'.config('const.USER_MANUAL_FILE_NAME');
            $file_name=config('const.USER_MANUAL_FILE_NAME');
        }
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$file_name, 'url' => $download_file_url]);
    }
    public function getUserManual(Request $request){
        $download_file_url=null;
        $file_name=null;
        $path=storage_path('app'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH'));
        $files = array_diff(scandir($path), array('.', '..'));
        $filePath=storage_path('app'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH').'/'.config('const.USER_MANUAL_FILE_NAME'));
        $fileInfo = array();
        if(file_exists($filePath)){
            $download_file_url=Config::get('app.url')."storage/app".config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH').'/'.config('const.USER_MANUAL_FILE_NAME');
            $file_name=config('const.USER_MANUAL_FILE_NAME');
            $filePath=storage_path('app'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH').'/'.config('const.USER_MANUAL_FILE_NAME'));

            $file_create_date = date ("Y-m-d", filemtime($filePath));
            $fileInfo['file_name']=$file_name;
            $fileInfo['download_file_url']=$download_file_url;
            $fileInfo['file_create_date']=$file_create_date;
        }
        return response()->json(['message' => 'Success','status'=>1,'new_file_name'=>$file_name, 'url' => $download_file_url,'file_list'=>$fileInfo]);
    }
    public function zipFileUpdate(Request $request){
        $this->validate($request, [
            'zip_file'=>'required|mimes:zip'
        ]);
        $file_name=config('const.USER_MANUAL_FILE_NAME');
        $filePath=storage_path('app'.config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH').'/'.config('const.USER_MANUAL_FILE_NAME'));
        if ($request->hasFile('zip_file')) {
            if(file_exists($filePath)){
                unlink($filePath);
              }
            $request->file('zip_file')->storeAs(config('const.USER_MANUAL_UPLOAD_DOWNLOAD_PATH'), $file_name);
            return response()->json(['message' => 'Success']);

        }
    }

    public function cmnDataCount(Request $request){
        $jcode=config('const.DATA_TEST_JCODE');
        $getCmnConnectIdsInfo = cmn_company::select('cmn_connects.cmn_connect_id')
        ->join('slr_sellers','slr_sellers.cmn_company_id','=','cmn_companies.cmn_company_id')
        ->join('cmn_connects','cmn_connects.slr_seller_id','=','slr_sellers.slr_seller_id')
        ->where('cmn_companies.company_type','seller')->where('cmn_companies.jcode',$jcode)->get();
        $getCmnConnectIds=array();
        if($getCmnConnectIdsInfo){
            foreach($getCmnConnectIdsInfo as $vl){
                $getCmnConnectIds[]=$vl->cmn_connect_id;
            }
        }
        $data_order_count=0;
        $data_invoice_count=0;
        $data_payment_count=0;
        $data_return_count=0;
        $data_shipment_count=0;
        $data_receive_count=0;
        if(count($getCmnConnectIds)>0){
            $data_order_count = data_order::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
            $data_invoice_count = data_invoice::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
            $data_payment_count = data_payment::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
            $data_return_count = data_return::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
            $data_shipment_count = data_shipment::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
            $data_receive_count = data_receive::whereIn('cmn_connect_id',$getCmnConnectIds)->get()->count();
        }
        $result = array(
            array(
                'en'=>"order",
                'jp'=>"発注",
                'count'=>$data_order_count,
            ),
            array(
                'en'=>"shipment",
                'jp'=>"出荷",
                'count'=>$data_shipment_count,
            ),
            array(
                'en'=>"receive",
                'jp'=>"受領",
                'count'=>$data_receive_count,
            ),
            array(
                'en'=>"return",
                'jp'=>"返品",
                'count'=>$data_return_count,
            ),
            array(
                'en'=>"invoice",
                'jp'=>"請求",
                'count'=>$data_invoice_count,
            ),
            array(
                'en'=>"payment",
                'jp'=>"支払",
                'count'=>$data_payment_count,
            )
        );
        return response()->json(['result' => $result]);
    }
    public function cmnTestDataDelete(Request $request){
        //return $request->all();
        $jcode=config('const.DATA_TEST_JCODE');
        $getCmnConnectIdsInfo = cmn_company::select('cmn_connects.cmn_connect_id')
        ->join('slr_sellers','slr_sellers.cmn_company_id','=','cmn_companies.cmn_company_id')
        ->join('cmn_connects','cmn_connects.slr_seller_id','=','slr_sellers.slr_seller_id')
        ->where('cmn_companies.company_type','seller')->where('cmn_companies.jcode',$jcode)->get();
        $getCmnConnectIds=array();
        if($getCmnConnectIdsInfo){
            foreach($getCmnConnectIdsInfo as $vl){
                $getCmnConnectIds[]=$vl->cmn_connect_id;
            }
        }
        if(count($getCmnConnectIds)>0){
            if($request->en=='order'){

                $data_order_count = data_order::join('data_order_vouchers','data_order_vouchers.data_order_id','=','data_orders.data_order_id')
                ->join('data_order_items','data_order_items.data_order_voucher_id','=','data_order_vouchers.data_order_voucher_id')
                ->whereIn('data_orders.cmn_connect_id',$getCmnConnectIds)->get();
                $voucherEntityIds = $data_order_count->map( function($entity) {
                    return [$entity['data_order_voucher_id']];
                });
                $orderEntityIds = $data_order_count->map( function($entity) {
                    return [$entity['data_order_id']];
                });
                data_order::whereIn('data_order_id',$orderEntityIds)->delete();
                data_order_voucher::whereIn('data_order_voucher_id',$voucherEntityIds)->delete();
                data_order_item::whereIn('data_order_voucher_id',$voucherEntityIds)->delete();
            }else if($request->en=='invoice'){
                $data_invoice_count = data_invoice::
                join('data_invoice_pays','data_invoice_pays.data_invoice_id','=','data_invoices.data_invoice_id')
                ->join('data_invoice_pay_details','data_invoice_pay_details.data_invoice_pay_id','=','data_invoice_pays.data_invoice_pay_id')
                ->whereIn('data_invoices.cmn_connect_id',$getCmnConnectIds)->get();
                $payEntityIds = $data_invoice_count->map(function($entity) {
                    return [$entity['data_invoice_pay_id']];
                });
                $invoiceEntityIds = $data_invoice_count->map(function($entity) {
                    return [$entity['data_invoice_id']];
                });
                data_invoice::whereIn('data_invoice_id',$invoiceEntityIds)->delete();
                data_invoice_pay::whereIn('data_invoice_pay_id',$payEntityIds)->delete();
                data_invoice_pay_detail::whereIn('data_invoice_pay_id',$payEntityIds)->delete();
            }else if($request->en=='payment'){
                $data_payment_count = data_payment::
                join('data_payment_pays','data_payment_pays.data_payment_id','=','data_payments.data_payment_id')
                ->join('data_payment_pay_details','data_payment_pay_details.data_payment_pay_id','=','data_payment_pays.data_payment_pay_id')
                ->whereIn('data_payments.cmn_connect_id',$getCmnConnectIds)->get();
                $payEntityIds = $data_payment_count->map(function($entity) {
                    return [$entity['data_payment_pay_id']];
                });
                $entityIds = $data_payment_count->map(function($entity) {
                    return [$entity['data_payment_id']];
                });
                data_payment::whereIn('data_payment_id',$entityIds)->delete();
                data_payment_pay::whereIn('data_payment_pay_id',$payEntityIds)->delete();
                data_payment_pay_detail::whereIn('data_payment_pay_id',$payEntityIds)->delete();
            }else if($request->en=='return'){
                $data_return_count = data_return::
                join('data_return_vouchers','data_return_vouchers.data_return_id','=','data_returns.data_return_id')
                ->join('data_return_items','data_return_items.data_return_voucher_id','=','data_return_vouchers.data_return_voucher_id')
                ->whereIn('data_returns.cmn_connect_id',$getCmnConnectIds)->get();
                $entityIds = $data_return_count->map(function($entity) {
                    return [$entity['data_return_id']];
                });
                $dEntityIds = $data_return_count->map(function($entity) {
                    return [$entity['data_return_voucher_id']];
                });
                data_return::whereIn('data_return_id',$entityIds)->delete();
                data_return_voucher::whereIn('data_return_voucher_id',$dEntityIds)->delete();
                data_return_item::whereIn('data_return_voucher_id',$dEntityIds)->delete();
            }else if($request->en=='shipment'){
                $data_shipment_count = data_shipment::
                join('data_shipment_vouchers','data_shipment_vouchers.data_shipment_id','=','data_shipments.data_shipment_id')
                ->join('data_shipment_items','data_shipment_items.data_shipment_voucher_id','=','data_shipment_vouchers.data_shipment_voucher_id')
                ->join('data_shipment_item_details','data_shipment_item_details.data_shipment_item_id','=','data_shipment_items.data_shipment_item_id')
                ->whereIn('data_shipments.cmn_connect_id',$getCmnConnectIds)->get();
                $entityIds = $data_shipment_count->map(function($entity) {
                    return [$entity['data_shipment_id']];
                });
                $dEntityIds = $data_shipment_count->map(function($entity) {
                    return [$entity['data_shipment_voucher_id']];
                });
                $iEntityIds = $data_shipment_count->map(function($entity) {
                    return [$entity['data_shipment_item_id']];
                });
                data_shipment::whereIn('data_shipment_id',$entityIds)->delete();
                data_shipment_voucher::whereIn('data_shipment_voucher_id',$dEntityIds)->delete();
                data_shipment_item::whereIn('data_shipment_voucher_id',$dEntityIds)->delete();
                data_shipment_item_detail::whereIn('data_shipment_item_id',$iEntityIds)->delete();
            }else if($request->en=='receive'){
                $data_receive_count = data_receive::
                join('data_receive_vouchers','data_receive_vouchers.data_receive_id','=','data_receives.data_receive_id')
                ->join('data_receive_items','data_receive_items.data_receive_voucher_id','=','data_receive_vouchers.data_receive_voucher_id')
                ->whereIn('data_receives.cmn_connect_id',$getCmnConnectIds)->get();

                $entityIds = $data_receive_count->map(function($entity) {
                    return [$entity['data_receive_id']];
                });
                $dEntityIds = $data_receive_count->map(function($entity) {
                    return [$entity['data_receive_voucher_id']];
                });
                data_receive::whereIn('data_receive_id',$entityIds)->delete();
                data_receive_voucher::whereIn('data_receive_voucher_id',$dEntityIds)->delete();
                data_receive_item::whereIn('data_receive_voucher_id',$dEntityIds)->delete();

            }
        }
        return response()->json(['success' => '1']);
    }
}
