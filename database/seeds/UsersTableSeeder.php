<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ADM\User;
use App\Models\ADM\adm_user_details;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_array=array(
            [
                'name' => 'Jacos Super Admin',
                'email' => 'super_admin@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Jacos Admin',
                'email' => 'admin@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Jacos User',
                'email' => 'user@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Byr1 User',
                'email' => 'byr01@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Slr1 User',
                'email' => 'slr01@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Slr2 User',
                'email' => 'slr02@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Slr3 User',
                'email' => 'slr03@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Slr4 User',
                'email' => 'slr04@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'biware_1 User',
                'email' => 'biware_1@jacos.co.jp',
                'password' => bcrypt('8NMS6TNT')
            ],

            [
                'name' => 'Byr2 User',
                'email' => 'byr02@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'Byr3 User',
                'email' => 'byr03@test.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'sakaki',
                'email' => 'sakaki@jacos.co.jp',
                'password' => bcrypt('jacos6571')
            ],
            [
                'name' => 'Biware_Level3',
                'email' => 'biware_level3@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'national',
                'email' => 'national@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],
            [
                'name' => 'national-seller',
                'email' => 'national-seller@jacos.co.jp',
                'password' => bcrypt('Qe75ymSr')
            ],

        );


        $user_details_array=array(
            ['user_id'=>1],
            ['user_id'=>2],
            ['user_id'=>3],
            ['user_id'=>4],
            ['user_id'=>5],
            ['user_id'=>6],
            ['user_id'=>7],
            ['user_id'=>8],
            ['user_id'=>9],
            ['user_id'=>10],
            ['user_id'=>11],
            ['user_id'=>12],
            ['user_id'=>13],
            ['user_id'=>14],
            ['user_id'=>15],
        );
        User::insert($user_array);
        adm_user_details::insert($user_details_array);
    }
}
