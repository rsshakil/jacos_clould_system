<?php

namespace App\Models\ADM;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\ADM\adm_user_details;
use App\Models\CMN\cmn_companies_user;
use App\Models\CMN\cmn_company;
use App\Models\BYR\byr_buyer;
use App\Models\SLR\slr_seller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use Notifiable,HasRoles,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * Get the phone record associated with the user.
     */
    // public function userDetailsRel()
    // {
    //     // return $this->hasOne('App\adm_user_details', 'user_id');
    //     return $this->hasOne('App\adm_user_details', 'user_id', 'id');
    // }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    protected $table = 'adm_users';
    // public function getFirstNameAttribute()
    public function getImageAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $user_id= Auth::user()->id;
        $user_details = adm_user_details::where('user_id', $user_id)->first();
        if($user_details){
            $image_name = $user_details->image;
        } else {
            $image_name = null;
        }
        Log::debug(__METHOD__.':end---');
        return $image_name;
    }
    // For check permission like can in vue
    public function getAllPermissionsAttribute() {
        Log::debug(__METHOD__.':start---');
        $permissions = [];
          foreach (Permission::all() as $permission) {
            if (Auth::user()->can($permission->name)) {
              $permissions[] = $permission->name;
            }
          }
          Log::debug(__METHOD__.':end---');
        return $permissions;
    }
    // For check roles like canrole in vue
    public function getAllRolesAttribute() {
        Log::debug(__METHOD__.':start---');
        $roles = [];
          foreach (Role::all() as $role) {
            if (Auth::user()->hasrole($role->name)) {
              $roles[] = $role->name;
            }
          }
          Log::debug(__METHOD__.':end---');
        return $roles;
    }

    public static function getSlrInfoAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id= Auth::user()->id;
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
    public static function getByrInfoAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $adm_user_id= Auth::user()->id;
        $result = cmn_companies_user::select('bb.*')
        ->join('cmn_companies as cc','cc.cmn_company_id','=','cmn_companies_users.cmn_company_id')
        ->join('byr_buyers as bb','bb.cmn_company_id','=','cc.cmn_company_id')
        ->where('cmn_companies_users.adm_user_id',$adm_user_id)
        ->first();
        Log::debug(__METHOD__.':end---');
        return $result;
    }

    public function getCompanyIdAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $user_id= Auth::user()->id;
        $company_info = cmn_companies_user::where('adm_user_id', $user_id)->first();
        $cmn_company_id = null;
        if($company_info){
            $cmn_company_id = $company_info->cmn_company_id;
        }
        Log::debug(__METHOD__.':end---');
        return $cmn_company_id;
    }
    public function getCompanyNameAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $cmn_company_id = $this->getCompanyIdAttribute();
        $company_name = null;
        $company_info =array();
        if ($cmn_company_id !=null) {
            $company_info = cmn_company::select('company_name')->where('cmn_company_id', $cmn_company_id)->first();
        }

        if($company_info){
            $company_name = $company_info->company_name;
        }
        Log::debug(__METHOD__.':end---');
        return $company_name;
    }

    public function getUserTypeAttribute(){
        Log::debug(__METHOD__.':start---');
       $cmn_company_id= $this->getCompanyIdAttribute();
      $buyer_list = byr_buyer::where('cmn_company_id',$cmn_company_id)->first();
      $seller_list = slr_seller::where('cmn_company_id',$cmn_company_id)->first();
      Log::debug(__METHOD__.':end---');
      if($buyer_list){
        return 'byr';
      }else if($seller_list){
        return 'slr';
      }else{
        return 'others';
      }
    }

    public function getByrSlrIdAttribute()
    {
        Log::debug(__METHOD__.':start---');
        $cmn_company_id= $this->getCompanyIdAttribute();
        $user_type= $this->getUserTypeAttribute();
        if($user_type=='slr'){
          $company_user_list = slr_seller::where('cmn_company_id',$cmn_company_id)->get();
        }else{
          $company_user_list = byr_buyer::where('cmn_company_id',$cmn_company_id)->get();
        }
        Log::debug(__METHOD__.':end---');
        return $company_user_list;
    }

}
