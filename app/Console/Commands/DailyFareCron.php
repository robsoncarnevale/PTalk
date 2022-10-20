<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyFareCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailyFare:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fare launch'; //Apurar diariamente tarifas a serem lançadas

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
        info("Cron Job running at ". now());
        return 0;
    }
}
