<?php

namespace App\Console;

use App\Jobs\KickoffBankSlipBatchProcessing;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('horizon:snapshot')->everyFiveMinutes(); //Take a Horizon snapshot every five minutes
        $schedule->command('telescope:prune --hours=48')->daily(); //Prune Telescope entries older than 48 hours every day
        $schedule->command('queue:prune-batches --hours=48')->daily(); //Prune queued batches older than 48 hours every day
        $schedule->job(KickoffBankSlipBatchProcessing::class)->everyFiveSeconds(); //Kick off batch processing every five seconds
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
