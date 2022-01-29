<?php

namespace App\Http\Controllers\API\ADM;

use App\Http\Controllers\API\AllUsedFunction;
use App\Http\Controllers\Controller;
use App\Models\ADM\User;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AssignRoleModel extends Controller
{
    private $all_used_functions;

    public function __construct()
    {
        $this->all_used_functions = new AllUsedFunction();
    }
    /**
     * Assign role to an user.
     *
     * @return \Illuminate\Http\Response
     */
    public function allUsersAndRoles()
    {
        $active = 'assign_role_model';
        $users = $this->all_used_functions->allUsers();
        $roles = $this->all_used_functions->get_role_custom_field();
        return \response()->json(['users' => $users, 'roles' => $roles, 'active' => $active]);
    }
    /**
     * Get roles for a desired user.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function getRoleById($id)
    {
        $model_data = DB::table('adm_model_has_roles as mhr')->select('adm_roles.id as role_id', 'adm_roles.name as role_name')
            ->join('adm_roles', 'adm_roles.id', '=', 'mhr.role_id')
            ->where('mhr.model_id', $id)
            ->get();
        return response()->json(['model_data' => $model_data]);

    }
    /**
     * Assign roles to a user.
     *
     * @param  int $user_id
     * @param  array $roles
     * @return revoke previous roles and set new roles and return a success message
     */
    public function assignModelRole(Request $request)
    {
        $user_id = $request->user_id;
        $roles = $request->roles;
        $role = Role::find($roles);
        $user = User::findOrFail($user_id);
        $user->syncRoles();
        $user->assignRole($role);
        return response()->json(['title' => "Assigned!", 'message' => "success", 'class_name' => 'success']);
    }
}
