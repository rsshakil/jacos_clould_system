<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\ADM\User;

class modelHasrolesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::findOrFail($this->user_search('Jacos Super Admin'));
        $super_admin->assignRole('Super Admin', 'Admin', 'User');

        $admin = User::findOrFail($this->user_search('Jacos Admin'));
        $admin->assignRole('Admin', 'User');

        $user = User::findOrFail($this->user_search('Jacos User'));
        $user->assignRole('User');

        $byr1 = User::findOrFail($this->user_search('Byr1 User'));
        $byr1->assignRole('Byr');

        $byr2 = User::findOrFail($this->user_search('Byr2 User'));
        $byr2->assignRole('Byr');

        $byr3 = User::findOrFail($this->user_search('Byr3 User'));
        $byr3->assignRole('Byr');

        $slr1 = User::findOrFail($this->user_search('Slr1 User'));
        $slr1->assignRole('Slr');

        $slr2 = User::findOrFail($this->user_search('Slr2 User'));
        $slr2->assignRole('Slr');

        $slr3 = User::findOrFail($this->user_search('Slr3 User'));
        $slr3->assignRole('Slr');

        $slr4 = User::findOrFail($this->user_search('Slr4 User'));
        $slr4->assignRole('Slr');

        $slr5 = User::findOrFail($this->user_search('sakaki'));
        $slr5->assignRole('Slr');

        $biware_level3 = User::findOrFail($this->user_search('Biware_Level3'));
        $biware_level3->assignRole('Slr');
        $biware_level3->assignRole('Byr');

        $national_seller = User::findOrFail($this->user_search('national-seller'));
        $national_seller->assignRole('Slr');


        $national = User::findOrFail($this->user_search('national'));
        $national->assignRole('Byr');


        // $user_user = User::findOrFail($this->user_search('Chairman'));
        // $user_user->assignRole('Admin');
    }
    private function user_search($user_name)
    {
        $user_info=User::where('name', $user_name)->first();
        return $user_id=$user_info['id'];
    }
}
