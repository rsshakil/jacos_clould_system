<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class roleHasPermissionsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_super_admin = Role::findByName('Super Admin');
        $permissions = Permission::all();
        $role_super_admin->givePermissionTo($permissions);

        $role_admin = Role::findByName('Admin');
        $role_admin->givePermissionTo('home','dashboard_menu','dashboard_view','role_menu','role_create','role_view','permission_menu','permission_create','permission_view','scenario_management','user_create','users_view','user_profile_view','user_permission_view','change_password','personal_profile_view','personal_user_update','personal_password_change','all_menu_show','scenario_history','data_test');

        $role_user = Role::findByName('User');
        $role_user->givePermissionTo('home','dashboard_menu','dashboard_view','personal_profile_view','personal_user_update','personal_password_change','all_menu_show');

        $role_saler = Role::findByName('Slr');
        $role_saler->givePermissionTo('home','selected_buyer','order_list_details','order_item_list_detail','management_setting','slr_view','add_company_users','order_list','slr_management','receive_list','receive_detail','order_corrected_receive','return_item_list','payment_list','invoice_list','invoice_details','byr_management','item_search','item_search_detail','receive_item_detail','payment_detail','payment_item_detail','return_list','return_detail','return_item_detail','stock_item_list');

        $role_byr = Role::findByName('Byr');
        $role_byr->givePermissionTo('home','voucher_setting','item_category','pdf_platform_setting','pdf_platform_view','byr_view','blog','item_master','item_category','slr_order_list','slr_receive_list','slr_return_list','slr_return_detail','slr_return_item_detail','slr_invoice_list','slr_payment_list','slr_invoice_details','byr_management','slr_item_search','slr_item_search_detail','slr_receive_item_detail','slr_payment_detail','slr_payment_item_detail','slr_order_list_details','slr_order_item_list_detail','slr_receive_detail','slr_return_item_list','slr_stock_item_list');

        // $role_byr = Role::findByName('Byr1');
        // $role_byr->givePermissionTo('home','voucher_setting','item_master','item_category','pdf_platform_setting','pdf_platform_view','byr_view');

        // $role_byr = Role::findByName('Byr2');
        // $role_byr->givePermissionTo('home','voucher_setting','item_master','item_category','pdf_platform_setting','pdf_platform_view','byr_view');

        // $role_byr = Role::findByName('Byr3');
        // $role_byr->givePermissionTo('home','voucher_setting','item_master','item_category','pdf_platform_setting','pdf_platform_view','byr_view');
    }
}
