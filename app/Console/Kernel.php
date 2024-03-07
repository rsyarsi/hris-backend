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
        // $schedule->command('inspire')->hourly();
        // $schedule->command('shiftSchedule')->dailyAt('00:01');
        // $schedule->command('generate:generate-shift-schedule-non-shift')->everyMinute();
        $schedule->command('generate:generate-shift-schedule-non-shift')->dailyAt('00:05');
        $schedule->command('leave:cancel-leave')->dailyAt('00:35');
        // $schedule->command('backup:clean')->daily()->at('01:20');
        // $schedule->command('backup:run')->daily()->at('01:30');
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
