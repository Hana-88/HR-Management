<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('logs:delete-old')->monthly();
    }

    protected $com = [
        \App\Console\Commands\InsertFakeEmployees::class,
    ];


    protected $command = [
        \App\Console\Commands\RemoveLogs::class,
    ];

    protected $commands = [
        \App\Console\Commands\ExportDatabase::class,
    ];



    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
