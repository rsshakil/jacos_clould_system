<?php

namespace App\Http\Controllers\API\ADM;

use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\API\AllUsedFunction;
use Auth;

class RoleController extends Controller
{
    private $all_used_functions;

    public function __construct()
    {
        $this->all_used_functions = new AllUsedFunction();
    }
    /**
     * Show the page to manage Role.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = $this->all_used_functions->get_roles();
        $role_permissions = array();
        foreach ($roles as $role):
            $role_info['role_id'] = $role->id;
            $role_info['role_name'] = $role->name;
            $role_info['role_description'] = $role->role_description;
            $role_info['guard_name'] = $role->guard_name;
            $role_info['is_system'] = $role->is_system;
            $role_info['role_permissions'] = $this->all_used_functions->get_role_permission_by_role_id($role->id);
            $role_info['permissions'] = $this->all_used_functions->get_permissions($role->id);
            $role_info['all_permissions'] = $this->all_used_functions->get_role_permission_by_role_id();
            $role_permissions[] = $role_info;
        endforeach;
        return response()->json([
            'role_permissions'=>$role_permissions,
            ]);
    }
    /**
     * Insert and update role
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!(Validator::make($request->all(), ['role_name' => 'required | max:50',])->passes())) {
            return response()->json(['alert_text' => 'required', 'class_name' => 'error','title'=>'Required!']);
        }
        $role_update_id = $request->role_update_id;
        $permission_full = $request->permissions;
        $role_name = $request->role_name;
        $permissions=array();
        foreach ($permission_full as $key => $value) {
            $permissions[]=$value['id'];
        }
            $role_description = $request->role_description;
            if ($role_update_id == null) {
                $roles = Role::where('name', $role_name)->get();
                $roles = json_decode($roles);
                if (empty($roles)) {
                    $role_last_id = Role::insertGetId(['name' => $role_name, 'guard_name' => 'web', 'role_description' => $role_description, 'is_system' => 0]);
                    $this->all_used_functions->assignPermissionToRole($role_last_id, $permissions);
                    return response()->json(['alert_text' => 'created', 'class_name' => 'success','title'=>'Created!']);
                } else {
                    return response()->json(['alert_text' =>'duplicated', 'class_name' => 'error','title'=>'Not Created!']);
                }
            } else {
                $roles_check = Role::where('id', $role_update_id)->first();
                if ($roles_check['name'] != $role_name) {
                    if (Role::where('name', '=', $role_name)->exists()) {
                        return response()->json(['alert_text' => 'duplicated', 'class_name' => 'error','title'=>'Not Updated!']);
                    }
                }
                Role::where('id', $role_update_id)->update([
                    'name' => $role_name,
                    'role_description' => $role_description]);
                $this->all_used_functions->assignPermissionToRole($role_update_id, $permissions);
                return response()->json(['alert_text' => 'updated', 'class_name' => 'success','title'=>'Updated!']);
            }
    }
/**
     * Delete a role
     *
     * @param  int $role_id
     * @return Success or fail message as json format
     */
    public function destroy($role_id)
    {
        $role_info=Role::where([['id', '=', $role_id], ['is_system', '=', 0]])->first();
        $role_name=$role_info['name'];
        $is_delete = Role::where([['id', '=', $role_id], ['is_system', '=', 0]])->delete();
        if ($is_delete) {
            return response()->json(['title'=>"Deleted!",'alert_text' => 'success', 'class_name' => 'success']);
        } else {
            return response()->json(['title'=>"Not Deleted!",'alert_text' =>'faild', 'class_name' => 'error']);
        }

    }
}
