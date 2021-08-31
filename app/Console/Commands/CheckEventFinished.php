<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Event;

class CheckEventFinished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if event has finished';

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
        \Log::info("Checando eventos finalizados...");

        $events = Event::select()
            ->where('club_code', getClubCodeEnv())
            ->where('status', Event::ACTIVE_STATUS)
            ->where('deleted', false)
            ->where('date', '<=', date('Y-m-d 23:59:59', strtotime('-1 days')))
            ->get();

        foreach($events as $event) {
            $event->status = Event::REALIZED_STATUS;
            $event->save();
        }
    }
}
