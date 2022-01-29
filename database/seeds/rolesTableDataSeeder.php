<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
class rolesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_array=array(
            [
                'name' => 'Super Admin',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'Admin',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'User',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'Slr',
                'guard_name' => 'web',
                'is_system' => 0,
            ],
            [
                'name' => 'Byr',
                'guard_name' => 'web',
                'is_system' => 0,
            ]
            // [
            //     'name' => 'Byr1',
            //     'guard_name' => 'web',
            //     'is_system' => 0,
            // ],
            // [
            //     'name' => 'Byr2',
            //     'guard_name' => 'web',
            //     'is_system' => 0,
            // ],
            // [
            //     'name' => 'Byr3',
            //     'guard_name' => 'web',
            //     'is_system' => 0,
            // ]
        );

        Role::insert($role_array);
    }
}
