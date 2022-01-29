<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\ADM\User;

class ModelHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $byr1 = self::userDetails('Byr1 User');
        // $byr1->givePermissionTo('order_list','receive_list','order_corrected_receive');

        // $byr2 = self::userDetails('Byr2 User');
        // $byr2->givePermissionTo('return_item_list');

        // $byr3 = self::userDetails('Byr3 User');
        // $byr3->givePermissionTo('payment_list','invoice_list','byr_management');
    }

    private function userDetails($user_name){
        return User::where('name',$user_name)->first();
    }
}
