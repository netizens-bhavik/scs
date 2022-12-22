<?php

namespace App\Console;

use App\Http\Controllers\GmLeadsMailListController;
use App\Http\Controllers\NotesMasterController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            NotesMasterController::reminder_mail();
        })->everyFiveMinutes();

        $schedule->call(function () {
            GmLeadsMailListController::sendMail();
        })->dailyAt('14:00');

        $schedule->call(function () {
            NotesMasterController::sendMail();
        })->dailyAt('19:00');
    }

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
