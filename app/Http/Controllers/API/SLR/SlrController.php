<?php

namespace App\Http\Controllers\API\SLR;

use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use App\Models\SLR\slr_seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SlrController extends Controller
{
    public static function getSlrInfoByUserId($adm_user_id)
    {
        Log::debug(__METHOD__.':start---');
        $query = cmn_companies_user::where('adm_user_id', $adm_user_id)
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
            ->join('cmn_companies', 'cmn_companies_users.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->select(
                'slr_sellers.slr_seller_id',
                'slr_sellers.cmn_company_id',
                'cmn_companies.company_name',
                'cmn_companies.company_name_kana',
                'cmn_companies.jcode',
                'cmn_companies.phone',
                'cmn_companies.fax',
                'cmn_companies.postal_code',
                'cmn_companies.address'
            );
        $result = $query->first();
        Log::debug(__METHOD__.':end---');
        return $result;
    }

    /**
     * slr_management
     *
     * @param  mixed $adm_user_id
     * @return void
     */
    public function slr_management($adm_user_id)
    {
        Log::debug(__METHOD__.':start---');
        $authUser = User::find($adm_user_id);
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $cmn_company_info = cmn_companies_user::where('adm_user_id', $adm_user_id)->first();
            $cmn_company_id = $cmn_company_info->cmn_company_id;
        }

        $query = DB::table('cmn_companies')
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies.cmn_company_id')
            ->groupBy('cmn_companies.cmn_company_id');
        if (!$authUser->hasRole(config('const.adm_role_name'))) {
            $query = $query->where('cmn_companies.cmn_company_id', $cmn_company_id);
        }
        $result = $query->get();
        Log::debug(__METHOD__.':end---');
        return response()->json(['slr_list' => $result]);
    }
    public function getSellerList(Request $request)
    {
        $cmn_connect_id = $request->cmn_connect_id;
        $selected_sellers = array();
        $seller_query = cmn_company::select('slr_sellers.slr_seller_id', 'cmn_companies.cmn_company_id', 'cmn_companies.company_name as seller_name', 'cmn_companies.jcode')
            ->join('slr_sellers', 'slr_sellers.cmn_company_id', '=', 'cmn_companies.cmn_company_id');
        $sellers = $seller_query->get();
        if ($cmn_connect_id != null) {
            $selected_sellers = $seller_query->join('cmn_connects', 'cmn_connects.slr_seller_id', '=', 'slr_sellers.slr_seller_id')
                ->where('cmn_connects.cmn_connect_id', $cmn_connect_id)->first();
        }
        return response()->json(['sellers' => $sellers, 'selected_sellers' => $selected_sellers]);
    }
    public function createSeller(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'company_name' => 'required|string|max:100',
            'fax' => 'required|string|max:30',
            'jcode' => 'required|string|min:3',
            'postal_code' => 'required|string|min:3',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|min:3',
        ]);

        $cmn_company_id = $request->cmn_company_id;
        $company_name = $request->company_name;
        $jcode = $request->jcode;
        $fax = $request->fax;
        $postal_code = $request->postal_code;
        $phone = $request->phone;
        $address = $request->address;

        $seller_company_array = array(
            'company_type' => 'seller',
            'company_name' => $company_name,
            'jcode' => $jcode,
            'fax' => $fax,
            'postal_code' => $postal_code,
            'phone' => $phone,
            'address' => $address,
        );
        if ($cmn_company_id != null) {
            cmn_company::where('cmn_company_id', $cmn_company_id)->update($seller_company_array);
            // $adm_role_info = slr_seller::where('cmn_company_id', $cmn_company_id)->first();
            // $adm_role_id = $adm_role_info->adm_role_id;
            // $this->all_used_fun->assignPermissionToRole($adm_role_id, $selected_permissions);
            // byr_buyer::where('cmn_company_id', $cmn_company_id)->update(['super_code' => $super_code]);
            return response()->json(['title' => "Updated!", 'message' => "updated", 'class_name' => 'success']);

        } else {
            // $role_last_id = Role::insertGetId(['name' => 'byr' . $jcode, 'guard_name' => 'web', 'role_description' => 'byr' . $jcode, 'is_system' => 0]);
            // $this->all_used_fun->assignPermissionToRole($role_last_id, $selected_permissions);
            $cmn_company_id = cmn_company::insertGetId($seller_company_array);
            slr_seller::insert(['cmn_company_id' => $cmn_company_id, 'adm_role_id' =>0]);
            return response()->json(['title' => "Created!", 'message' => "created", 'class_name' => 'success']);
        }
    }

    public function sellerDelete(Request $request){
        $cmn_company_id=$request->cmn_company_id;
        $slr_seller_id=$request->slr_seller_id;
        // slr_seller::where('slr_seller_id',$slr_seller_id)->delete();
        cmn_company::where('cmn_company_id',$cmn_company_id)->delete();
        $cmn_company_users = cmn_companies_user::select('adm_user_id')->where('cmn_company_id', $cmn_company_id)->get();
        foreach ($cmn_company_users as $key => $cmn_company_user) {
            $user = User::findOrFail($cmn_company_user->adm_user_id);
            $user->syncRoles();
            User::where('id',$cmn_company_user->adm_user_id)->delete();
        }
        return response()->json(['title' => "Deleted!", 'message' => "Deleted", 'class_name' => 'success']);
    }

    public function sellerUserDelete(Request $request){
        $adm_user_id=$request->adm_user_id;
        $user = User::findOrFail($adm_user_id);
        $user->syncRoles();
        User::where('id',$adm_user_id)->delete();
        return response()->json(['title' => "Deleted!", 'message' => "Deleted", 'class_name' => 'success']);
    }
}
