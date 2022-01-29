<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(permissionsTableDataSeeder::class);
        $this->call(rolesTableDataSeeder::class);
        $this->call(roleHasPermissionsTableDataSeeder::class);
        $this->call(modelHasrolesTableDataSeeder::class);
        $this->call(ModelHasPermissionSeeder::class);
        // JCS Seeders
        $this->call(cmn_companySeeder::class);
       // $this->call(Byr_shipment_detailSeeder::class);
        // $this->call(lv3_trigger_scheduleSeeder::class);
        // $this->call(lv3_historySeeder::class);
        $this->call(cmn_companies_userSeeder::class);
        $this->call(cmn_scenarioSeeder::class);
        $this->call(cmn_jobSeeder::class);
        $this->call(cmn_connectSeeder::class);
        $this->call(byr_buyerSeeder::class);
        $this->call(slr_sellerSeeder::class);
        $this->call(cmnPdfCanvasSeeder::class);
        $this->call(CmnPdfPlatformSeeder::class);
        $this->call(cmn_blogSeeder::class);
        // Level3 Seeder
        $this->call(Lv3ServiceSeeder::class);
        $this->call(Lv3TriggerFilePathSeeder::class);
        $this->call(Lv3ScheduleSeeder::class);
        $this->call(Lv3JobSeeder::class);
    }
}
