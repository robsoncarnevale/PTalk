<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshPrivileges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:privileges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh privileges table';

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
     * @return int
     */
    public function handle()
    {
        echo "Running seeder to \033[36m" . \DatabaseSeeder::$club_code . "\033[0m " . PHP_EOL . PHP_EOL;
        echo "\033[32mCreating privileges...\033[0m" . PHP_EOL;

        $seeder = new \DatabaseSeeder();
        $seeder->createPrivileges();
        $seeder->addPrivilegesAdmin();
        $seeder->addPrivilegesMember();

        echo "\033[32mFinalizated.\033[0m" . PHP_EOL;
    }
}
