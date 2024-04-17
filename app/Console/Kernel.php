<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\GenerateShiftScheduleNonShiftCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('generate:generate-shift-schedule-non-shift')->dailyAt('00:05');
        // $schedule->command('leave:cancel-leave')->dailyAt('00:35');
        $schedule->command('backup:clean')->dailyAt('01:28');
        $schedule->command('backup:run')->dailyAt('01:30');
        // $schedule->command('scheduller:sahur')->dailyAt('03:00');
        $schedule->command('absen:absen-masuk-non-shift')->weekdays()->at('07:30');
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
