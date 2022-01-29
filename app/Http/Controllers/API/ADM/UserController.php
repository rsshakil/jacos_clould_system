<?php

namespace App\Http\Controllers\API\ADM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\AllUsedFunction;
use App\Models\ADM\User;
use App\Models\ADM\adm_user_details;
use App\Models\BYR\byr_order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $all_used_functions;

    public function __construct()
    {
        $this->all_used_functions = new AllUsedFunction();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $active = 'users';
        $users = $this->all_used_functions->allUsersAll();
        $roles = $this->all_used_functions->get_role_custom_field();
        $permissions = $this->all_used_functions->get_permission_custom_field();
        return response()->json(['users'=>$users,'roles'=>$roles,'permissions'=>$permissions,'active'=>$active]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!($validation_name = Validator::make($request->all(), ['name' => 'required|max:50'])->passes())) {
            return response()->json(['title'=>"Not Created!",'message' =>"name_required", 'class_name' => 'warning']);
        }
        if (!($validation_email = Validator::make($request->all(), ['email' => 'required|min:6|email'])->passes())) {
            return response()->json(['title'=>"Not Created!",'message' =>"email_required", 'class_name' => 'warning']);
        }
        if (!($validation_pass = Validator::make($request->all(), ['password' => 'required|min:6'])->passes())) {
            return response()->json(['title'=>"Not Created!",'message' =>"password_required", 'class_name' => 'warning']);
        }

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $roles = $request->roles;
        $permission_id = $request->permissions;

        $hash_password = Hash::make($password);
        $user_exist = User::where('email', $email)->first();
        if ($user_exist) {
            return response()->json(['title'=>"Exists!",'message' =>"exists", 'class_name' => 'error']);
        } else {
            $user = new User;
            $user->name = $name;
            $user->email = $email;
            $user->password = $hash_password;
            $user->save();
            $last_user_id = $user->id;
            $user_details = new adm_user_details;
            $user_details->user_id = $last_user_id;
            $user_details->save();
            $users = User::findOrFail($last_user_id);
            $role = Role::find($roles);
            $user_role = User::findOrFail($last_user_id);
            $user_role->syncRoles();
            $user_role->assignRole($roles);
            $users->syncPermissions($permission_id);
            return response()->json(['title'=>"Created!",'message' =>"created", 'class_name' => 'success']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id=$request->user_id;
        $auth_id=$request->auth_id;
        if (!(Validator::make($request->all(), ['f_name' => 'max:20'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'fname_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['l_name' => 'max:20'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'lname_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['name' => 'max:50'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'full_name_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['email' => 'required|min:6|email'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'email_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['phone' => 'max:50'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'phone_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['dob' => 'date'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'dob_required', 'class_name' => 'error']);
        }
        if (!(Validator::make($request->all(), ['zip' => 'max:20'])->passes())) {
            return response()->json(['title'=>"Error!",'message' =>'postal_required', 'class_name' => 'error']);
        }
        if ($user_id != $auth_id) {
            $authUser=User::find($auth_id);
            if (!($authUser->hasPermissionTo('user_update'))) {
                return response()->json(['title'=>"No permission!",'message' =>'no_permission', 'class_name' => 'error']);
            }
        }

        $f_name = $request->f_name;
        $l_name = $request->l_name;
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $dob = $request->dob;
        $gender = $request->gender;
        $postal_code = $request->zip;

        $user = User::find($user_id);
        $user_all_data = User::where('id', '=', $user_id)->first();
        $user_details_data = adm_user_details::where('user_id', '=', $user_id)->first();

        $user_email_exist = $user_all_data->email;
        if ($user_email_exist != $email) {
            if (User::where('email', '=', $email)->exists()) {
                return response()->json(['title'=>"Exists!",'message' =>'exists', 'class_name' => 'error']);
            }
        }
        $user->id = $user_id;
        $user->name = $name;
        $user->email = $email;
        $user->save();
        $file_name = '';
        $file_name_db = $user_details_data['image'];
        if($request->image_url){
            if ($file_name_db != '') {
                $image_exists = $file_name_db;
                $filename = storage_path() . '/app/' . config('const.USER_UPLOAD_IMAGES_PATH') . $image_exists;
                if (file_exists($filename)) {
                    @unlink($filename);
                }
            }
            $file_name = time().'.' . explode('/', explode(':', substr($request->image_url, 0, strpos($request->image_url, ';')))[1])[1];
            \Image::make($request->image_url)->save(storage_path("/app/".config('const.USER_UPLOAD_IMAGES_PATH')).$file_name);
            $request->merge(['image_url' => $file_name]);

        }else{
            $file_name = $file_name_db;
        }
        $update_array = array(
            'first_name' => $f_name,
            'last_name' => $l_name,
            'phone' => $phone,
            'date_of_birth' => $dob,
            'gender' => $gender,
            'postal_code' => $postal_code,
            'image' => $file_name,
        );
        adm_user_details::where('user_id', $user_id)->update($update_array);
        return response()->json(['title'=>"Updated!",'message' =>'updated', 'class_name' => 'success','image_name'=>$file_name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $deleted_at=$request->deleted_at;
        // return $id;
        // $user_info = User::where('id', $id)->first();
        // $user_name = $user_info['name'];
        // $detail_exist = adm_user_details::where('user_id', $id)->first();
        // User::where('id', $id)->delete();
        // if ($detail_exist) {
        //     $image_exists = $detail_exist['image'];
        //     $filename = public_path() . '/backend/images/users/' . $image_exists;
        //     if (file_exists($filename)) {
        //         @unlink($filename);
        //     }
        //     adm_user_details::where('user_id', $id)->delete();
        // }
        $own_id=Auth::User()->id;
        if ($own_id==$id) {
            return response()->json(['status'=>0,'title'=>"Not Deleted!",'message' =>'You can not delete or restore your ownself', 'class_name' => 'warning']);
        }
        if ($deleted_at==='null') {
            User::where('id', $id)->delete();
            adm_user_details::where('user_id', $id)->delete();
        }else{
            User::where('id', $id)->withTrashed()->restore();
            adm_user_details::where('user_id', $id)->withTrashed()->restore();
        }
        return response()->json(['status'=>1,'title'=>"Deleted!",'message' =>'deleted', 'class_name' => 'success','data_count'=>1]);
    }
    /**
     * A single user details.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    // public function userDetails(Request $request)
    public function userDetails($user_id)
    {
        $users = DB::select("select * from adm_users as u left join adm_user_details as ud on u.id=ud.user_id where u.id='$user_id'");
        return \response()->json(['users'=>$users]);
    }
    /**
 * Change password for an user.
 *
 * @param  Request $request
 * @return A success or fail message return as json formated
 */
public function changePassword(Request $request)
{
    $auth_id = $request->auth_id;
    $user_id = $request->user_id;
    $password = $request->password;
    $validation = Validator::make($request->all(), [
        'password' => 'required|min:6',
    ]);
    if ($validation->passes()) {
        if ($user_id != $auth_id) {
            $authUser=User::find($auth_id);
            if (!($authUser->hasPermissionTo('change_password'))) {
                return response()->json(['title'=>"No permission!",'message' =>'no_permission', 'class_name' => 'error']);
            }
        }
        $hashed_password = Hash::make($password);
        $user = User::find($user_id);
        $user->password = $hashed_password;
        $user->save();
        return response()->json(['title'=>"Updated!",'message' =>'password_changed', 'class_name' => 'success']);
    } else {
        return response()->json(['title'=>"Not Updated!",'message' =>'required', 'class_name' => 'error']);
    }
}

public function get_user_company_byr_slr_list(){
    $user_comp_info = array();
    $slr_order_info = array();
    $user_company_info = DB::table('cmn_companies_users')
    ->join('cmn_companies', 'cmn_companies.cmn_company_id', '=', 'cmn_companies_users.cmn_company_id')
    ->select('cmn_companies.cmn_company_id', 'cmn_companies.company_name')
    ->where('cmn_companies_users.adm_user_id',Auth::user()->id)
    ->first();
    $user_comp_info['cmn_company_id']='0';
    $user_comp_info['company_name']='';
    if(!empty($user_company_info)){
        $user_comp_info['cmn_company_id']=$user_company_info->cmn_company_id;
        $user_comp_info['company_name']=$user_company_info->company_name;
        $slr_order_info=byr_order::select(DB::raw('count(byr_orders.byr_order_id) as total_order'),'byr_buyers.byr_buyer_id','cmn_companies.company_name as buyer_name')
        ->join('cmn_connects','cmn_connects.cmn_connect_id','=','byr_orders.cmn_connect_id')
        ->join('byr_buyers','byr_buyers.byr_buyer_id','=','cmn_connects.byr_buyer_id')
        ->join('cmn_companies','cmn_companies.cmn_company_id','=','byr_buyers.cmn_company_id')
        ->where('cmn_connects.slr_seller_id',5)
        ->get();
    }
    return response()->json(['user_company_info'=>$user_comp_info,'byr_slr_list'=>$slr_order_info]);
}
}
