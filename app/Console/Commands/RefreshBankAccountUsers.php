<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RefreshBankAccountUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:bankaccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh bank account users';

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
        echo "Checking users who don't have an bank account on \033[36m" . \DatabaseSeeder::$club_code . "\033[0m " . PHP_EOL . PHP_EOL;

        $users = \App\Models\User::select()
            ->where('deleted', false)
            ->orderBy('id')
            ->get();

        foreach($users as $user){
            $bank_account = $user->createBankAccount();

            if ($bank_account) {
                echo "Created bank account \033[36m" . $bank_account->account_number . " (" . $bank_account->account_holder . ")\033[0m to user ID \033[36m" . $user->id . "\033[0m " . PHP_EOL;
            }
        }

        echo "\033[32mFinalizated.\033[0m" . PHP_EOL;
    }
}
