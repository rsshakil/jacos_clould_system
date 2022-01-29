<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'MigrateInOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is for migrate sequently';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
         /** Specify the names of the migrations files in the order you want to 
        * loaded
        * $migrations =[ 
        *               'xxxx_xx_xx_000000_create_nameTable_table.php',
        *    ];
        */
        $migrations = [ 
            '2020_07_29_151128_adm_user.php',
            '2020_07_29_160950_create_adm_user_details_table.php',
            '2014_10_12_100000_create_password_resets_table.php',
            '2019_02_20_041757_create_permission_tables.php',
            '2020_07_29_194141_create_cmn_companies_table.php',
            '2020_07_29_190943_create_byr_buyers_table.php',
        ];

            foreach($migrations as $migration)
            {
            $basePath = 'database/migrations/';          
            $migrationName = trim($migration);
            $path = $basePath.$migrationName;
            $this->call('migrate:fresh', [
            '--path' => $path ,            
            ]);
            }
    }
}
