<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class permissionsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission_array=array(
            [
                'name' => 'home',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role_create',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role_delete',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission_create',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission_delete',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_role_to_user_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_role_to_user_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_role_to_user_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_permission_to_user_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_permission_to_user_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_permission_to_user_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'manage_user_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'users_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_profile_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_create',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_permission_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'change_password',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_delete',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'personal_profile_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'personal_user_update',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'personal_password_change',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'all_menu_show',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'dashboard_menu',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'dashboard_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'company_create',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'add_company_users',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'scenario_management',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'document_management',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'scenario_history',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'data_test',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
//adm permission


            // SLR Permission
            [
                'name' => 'slr_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'order_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_management',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'receive_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'receive_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'receive_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'order_corrected_receive',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'return_item_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'payment_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'invoice_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'byr_management',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            // Buyer Permissions
            [
                'name' => 'selected_buyer',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'byr_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            [
                'name' => 'item_master',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'item_category',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'pdf_platform_setting',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'pdf_platform_view',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            //bur Menu
            [
                'name' => 'slr_order_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_order_list_details',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            [
                'name' => 'slr_order_item_list_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_item_search',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_item_search_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_receive_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_receive_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_receive_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_return_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_return_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_return_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_return_item_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_invoice_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_invoice_details',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_payment_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_payment_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_payment_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            // New
            [
                'name' => 'blog',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'role',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'permission',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_role_to_user',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'assign_permission_to_user',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'users',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'password_reset',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'order_details_canvas',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'order_list_details',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'item_search',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'item_search_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'order_item_list_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            [
                'name' => 'voucher_setting',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'byr_company_user_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'byr_company_partner_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_job_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'adm_partner_list_manage',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'partner_list_manage',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'user_list_manage',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            [
                'name' => 'job_management',
                'guard_name' => 'web',
                'is_system' => 0,
            ],

            [
                'name' => 'slr_company_user_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_company_partner_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],


            [
                'name' => 'payment_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'payment_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'invoice_details',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'voucher_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'management_setting',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'return_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'return_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'return_item_detail',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'stock_item_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'slr_stock_item_list',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
        );
        Permission::insert($permission_array);
    }
}
