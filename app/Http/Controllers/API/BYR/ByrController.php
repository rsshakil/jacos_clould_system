<?php

namespace App\Http\Controllers\API\BYR;

use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\Controller;
use App\Models\BYR\byr_buyer;
use App\Models\SLR\slr_seller;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use App\Models\CMN\cmn_connect;
use App\Models\DATA\ORD\data_order;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;

class ByrController extends Controller
{
    private $all_used_fun;

    public function __construct()
    {
        $this->all_used_fun = new AllUsedFunction();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = DB::table('cmn_companies')
            ->join('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->select('byr_buyers.super_code', 'cmn_companies.jcode', 'cmn_companies.company_name', 'cmn_companies.cmn_company_id', 'byr_buyers.byr_buyer_id')
            ->groupBy('cmn_companies.cmn_company_id')
            ->get();
        return response()->json(['company_list' => $result]);
    }
    public function get_all_company_list($adm_user_id)
    {
        $companies = cmn_company::select('byr_buyers.byr_buyer_id', 'byr_buyers.super_code', 'cmn_companies.*')
            ->join('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->get();
        return response()->json(['companies' => $companies]);
    }

    public function cmn_company_user_list($cmn_company_id = null)
    {

        $buyer_users = array();
        $company_name = null;
        if ($cmn_company_id != null) {
            $buyer_users = cmn_companies_user::select('adm_users.*', 'byr_buyers.super_code', 'cmn_companies.jcode')
                ->join('adm_users', 'adm_users.id', '=', 'cmn_companies_users.adm_user_id')
                ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
                ->leftJoin('byr_buyers', 'byr_buyers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
                ->where('cmn_companies_users.cmn_company_id', $cmn_company_id)
                ->get();
            if ($cmn_company_id != 'undefined') {
                $company_info = cmn_company::select('company_name')->where('cmn_company_id', $cmn_company_id)->first();
                $company_name = $company_info->company_name;
            }
        }
        return response()->json(['user_list' => $buyer_users, 'company_name' => $company_name]);
    }

    public function company_partner_list(Request $request)
    {
        // return $cmn_company_id;
        $company_name = null;
        $cmn_company_id = $request->cmn_company_id;
        $sort_by = $request->sort_by;
        $sort_type = $request->sort_type;
        $table_name='ccom.';
        if ($sort_by=="partner_code") {
            $table_name='cc.';
        }
        if ($cmn_company_id==null) {
            $result = DB::table('byr_buyers as bb')
            ->select('ss.slr_seller_id', 'bb.byr_buyer_id','bb.super_code', 'cc.cmn_connect_id',
            'cc.partner_code', 'cc.is_active', 'ccom.company_name', 'ccom.jcode')
                ->join('cmn_connects as cc', 'bb.byr_buyer_id', '=', 'cc.byr_buyer_id')
                ->join('slr_sellers as ss', 'ss.slr_seller_id', '=', 'cc.slr_seller_id')
                ->join('cmn_companies as ccom', 'ss.cmn_company_id', '=', 'ccom.cmn_company_id');
        }else{
            $company_info = cmn_company::select('company_type','company_name')->where('cmn_company_id', $cmn_company_id)->first();
            if ($company_info->company_type=='seller') {
                $result = slr_seller::select('slr_sellers.slr_seller_id', 'cc.cmn_connect_id',
                'cc.partner_code','cc.is_active','ccom.company_name', 'ccom.jcode')
                    ->join('cmn_connects as cc', 'slr_sellers.slr_seller_id', '=', 'cc.slr_seller_id')
                    ->join('byr_buyers as bb', 'bb.byr_buyer_id', '=', 'cc.byr_buyer_id')
                    ->join('cmn_companies as ccom', 'bb.cmn_company_id', '=', 'ccom.cmn_company_id')
                    ->where('slr_sellers.cmn_company_id', $cmn_company_id);
            }else if($company_info->company_type=='buyer'){
                $result = byr_buyer::select('byr_buyers.byr_buyer_id','byr_buyers.super_code','cc.cmn_connect_id',
                      'cc.partner_code', 'cc.is_active','ccom.company_name', 'ccom.jcode')
                        ->join('cmn_connects as cc', 'byr_buyers.byr_buyer_id', '=', 'cc.byr_buyer_id')
                        ->join('slr_sellers as ss', 'ss.slr_seller_id', '=', 'cc.slr_seller_id')
                        ->join('cmn_companies as ccom', 'ccom.cmn_company_id', '=', 'ss.cmn_company_id')
                        ->where('byr_buyers.cmn_company_id', $cmn_company_id);
            }
            // $result =$result->groupBy('cc.partner_code');
            $company_name = $company_info->company_name;
        }
        $result =$result->orderBy($table_name.$sort_by, $sort_type)->get();
        // New
        return response()->json(['partner_list' => $result, 'company_name' => $company_name]);
    }

    public function getPermissionForBuyer(Request $request)
    {
        $cmn_company_id = $request->cmn_company_id;
        $selected_permission_array = array();
        $permission_array = Permission::select('id as permission_id', 'name as permission_name')->where('name', 'like', 'byr_' . '%')->get();
        if ($cmn_company_id != null) {
            $byr_buyer_info = byr_buyer::select('adm_role_id')->where('cmn_company_id', $cmn_company_id)->first();
            $role_id = $byr_buyer_info->adm_role_id;

            $role = Role::findById($role_id);
            $selected_permission_array = $role->getAllPermissions();
        }
        return response()->json(['permission_array' => $permission_array, 'selected_permission_array' => $selected_permission_array]);
    }

    public function buyerPartnerCreate(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'cmn_company_id' => 'required|integer',
            'partner_code' => 'required',
            'selected_sellers' => 'required|array',
        ]);
        $cmn_company_id = $request->cmn_company_id;
        $cmn_connect_id = $request->cmn_connect_id;
        $partner_code = $request->partner_code;
        $selected_sellers = $request->selected_sellers;
        $slr_seller_id = $selected_sellers['slr_seller_id'];
        $byr_buyer_id =0;
            if ($cmn_company_id!=null) {
                $company_info = cmn_company::select('company_type','company_name')->where('cmn_company_id', $cmn_company_id)->first();
                if ($company_info->company_type=='seller') {
                    $seller_id_info = slr_seller::select('slr_seller_id')->where('cmn_company_id', $cmn_company_id)->first();
                    $slr_seller_id = $seller_id_info->slr_seller_id;
                    // $byr_buyer_id =0;
                }else if($company_info->company_type=='buyer'){
                    $buyer_id_info = byr_buyer::select('byr_buyer_id')->where('cmn_company_id', $cmn_company_id)->first();
                    $byr_buyer_id = $buyer_id_info->byr_buyer_id;
                    // $slr_seller_id = $selected_sellers['slr_seller_id'];
                }
            }

        // $byr_buyer_id = $buyer_id_info->byr_buyer_id;

        $partner_array = array(
            'byr_buyer_id' => $byr_buyer_id,
            'partner_code' => $partner_code,
            'slr_seller_id' => $slr_seller_id,
            'is_active' => 1,
        );
        if ($cmn_connect_id != null) {
            cmn_connect::where('cmn_connect_id', $cmn_connect_id)->update($partner_array);
            return response()->json(['title' => "Updated!", 'message' => "updated", 'class_name' => 'success']);
        } else {
            cmn_connect::insert($partner_array);
            return response()->json(['title' => "Created!", 'message' => "created", 'class_name' => 'success']);
        }

    }
    public function buyerPartnerDelete(Request $request)
    {
        $cmn_connect_id = $request->cmn_connect_id;
            cmn_connect::where('cmn_connect_id', $cmn_connect_id)->delete();
            return response()->json(['title' => "Deleted!", 'message' => "Deleted", 'class_name' => 'success']);


    }

    public function createBuyer(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required|string|max:191',
            'super_code' => 'required|string|max:20',
            'jcode' => 'required|string|min:3',
            'postal_code' => 'required|string|min:3',
            'address' => 'required|string|min:3',
        ]);
        $cmn_company_id = $request->cmn_company_id;
        $company_name = $request->company_name;
        $jcode = $request->jcode;
        $super_code = $request->super_code;
        $postal_code = $request->postal_code;
        $address = $request->address;
        $selected_permissions = $request->selected_permissions;
        $buyer_company_array = array(
            'company_type' => 'buyer',
            'company_name' => $company_name,
            'jcode' => $jcode,
            'postal_code' => $postal_code,
            'address' => $address,
        );
        if ($cmn_company_id != null) {
            cmn_company::where('cmn_company_id', $cmn_company_id)->update($buyer_company_array);
            $adm_role_info = byr_buyer::where('cmn_company_id', $cmn_company_id)->first();
            $adm_role_id = $adm_role_info->adm_role_id;
            $this->all_used_fun->assignPermissionToRole($adm_role_id, $selected_permissions);
            byr_buyer::where('cmn_company_id', $cmn_company_id)->update(['super_code' => $super_code]);
            return response()->json(['title' => "Updated!", 'message' => "updated", 'class_name' => 'success']);

        } else {
            $role_last_id = Role::insertGetId(['name' => 'byr' . $jcode, 'guard_name' => 'web', 'role_description' => 'byr' . $jcode, 'is_system' => 0]);
            $this->all_used_fun->assignPermissionToRole($role_last_id, $selected_permissions);
            $cmn_company_id = cmn_company::insertGetId($buyer_company_array);
            byr_buyer::insert(['cmn_company_id' => $cmn_company_id, 'super_code' => $super_code, 'adm_role_id' => $role_last_id]);
            return response()->json(['title' => "Created!", 'message' => "created", 'class_name' => 'success']);
        }
    }
    public function get_selected_byr_info($byr_buyer_id)
    {
        $byr_info = $this->all_used_fun->get_byr_info_by_byr_buyer_id($byr_buyer_id);

        return response()->json(['byr_info' => $byr_info]);
    }
    public function buyerJsonSetting(Request $request){


        // if(isset($request->byr_buyer_id)){
        //     $byr_buyer_id = $request->byr_buyer_id;
        // }else{
        //     $buyer_info = Auth::User()->ByrInfo;
        //     $byr_buyer_id =$buyer_info->byr_buyer_id;
        // }
        $byr_buyer_id =$request->byr_buyer_id;
        $byr_buyer_id =$byr_buyer_id?$byr_buyer_id:Auth::User()->ByrInfo->byr_buyer_id;

        $buyer_settings = byr_buyer::select('setting_information')->where('byr_buyer_id', $byr_buyer_id)->first();
        $result = json_decode($buyer_settings->setting_information);
        $cmn_connect_id = $this->all_used_fun->getCmnConnectId($request->adm_user_id,$request->byr_buyer_id);
        $p_table = 'data_orders';
        $pv_table = 'data_order_vouchers';
        $fieldname = 'mes_lis_ord_tra_goo_major_category';
        $cmnConnect = 'cmn_connect_id';
        switch($request->component_name){
            case 'receive_list':
            case 'slr_receive_list':
                        $p_table = 'data_receives';
                    $pv_table = 'data_receive_vouchers';
                    $fieldname = 'mes_lis_acc_tra_goo_major_category';
                break;
            case 'return_list':
                    $p_table = 'data_returns';
                    $pv_table = 'data_return_vouchers';
                    $fieldname = 'mes_lis_ret_tra_goo_major_category';
                break;
            case 'invoice_details':
                    $p_table = 'data_invoices';
                    $pv_table = 'data_invoice_pay_details';
                    $fieldname = 'mes_lis_inv_lin_det_goo_major_category';
                break;
            case 'payment_item_detail':
                    $p_table = 'data_payments';
                    $pv_table = 'data_payment_pay_details';
                    $fieldname = 'mes_lis_pay_lin_det_goo_major_category';
                break;
        }

        if($request->component_name=='payment_item_detail'){

            $catListitem = DB::select("SELECT
            $pv_table.$fieldname as category_code,
            CONCAT(COALESCE($pv_table.$fieldname,''),' | ',COALESCE(cmn_categories.category_name,'')) AS category_name
             FROM $p_table
             inner JOIN data_payment_pays on data_payment_pays.data_payment_id =data_payments.data_payment_id
             inner JOIN data_payment_pay_details on data_payment_pay_details.data_payment_pay_id =data_payment_pays.data_payment_pay_id
                left JOIN cmn_categories ON $pv_table.$fieldname=cmn_categories.category_code AND cmn_categories.byr_buyer_id='".$byr_buyer_id."'
                WHERE $p_table.$cmnConnect ='".$cmn_connect_id."' and $pv_table.$fieldname!='' AND $pv_table.deleted_at IS NULL
                GROUP BY $pv_table.$fieldname");
        }else{
            $catListitem = DB::select("SELECT
            $pv_table.$fieldname as category_code,
            CONCAT(COALESCE($pv_table.$fieldname,''),' | ',COALESCE(cmn_categories.category_name,'')) AS category_name
             FROM $p_table
                INNER JOIN $pv_table
                left JOIN cmn_categories ON $pv_table.$fieldname=cmn_categories.category_code AND cmn_categories.byr_buyer_id='".$byr_buyer_id."'
                WHERE $p_table.$cmnConnect ='".$cmn_connect_id."' and $pv_table.$fieldname!='' AND $pv_table.deleted_at IS NULL
                GROUP BY $pv_table.$fieldname");
        }


       // $buyer_category_list = $this->all_used_fun->get_allCategoryByByrId($byr_buyer_id);
        return response()->json([ 'buyer_settings' =>$result,'buyer_category_list'=>$catListitem ]);
    }
    public function getByrByOrderId(Request $request)
    {
        $data_order_id = $request->data_order_id;

        $result = DB::table('byr_orders')
            ->select('byr_buyers.*')
            ->join('cmn_connects', 'cmn_connects.cmn_connect_id', '=', 'byr_orders.cmn_connect_id')
            ->join('byr_buyers', 'byr_buyers.byr_buyer_id', '=', 'cmn_connects.byr_buyer_id')
            ->where('byr_orders.byr_order_id', $data_order_id)
            ->first();
        return response()->json(['byr_info' => $result]);
    }
}
