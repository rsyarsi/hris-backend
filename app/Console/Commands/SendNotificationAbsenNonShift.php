<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendNotificationAbsenNonShift extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absen:absen-masuk-non-shift';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send notification for employee non shift weekdays';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $nonShiftGroupId = '01hfhe3aqcbw9r1fxvr2j2tb75';
        $employees = Employee::with('user')
                            ->where('shift_group_id', $nonShiftGroupId)
                            ->where('resigned_at', '3000-01-01 00:00:00')
                            ->get(['id', 'user_id', 'name']);
        // Log::info($employees);
        $firebaseIds = [];
        $names = [];
        foreach ($employees as $employee) {
            if ($employee->user && $employee->user->firebase_id) {
                $firebaseIds[] = $employee->user->firebase_id;
                $names = $employee->user->name;
                // Log::info($firebaseIds);
            }
        }
        Log::info("Hay, " . $names . ", Jangan lupa absen masuk ya....");
        Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION')
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $firebaseIds,
            'data' => [
                'extra_information' => 'Notification : ABSEN MASUK'
            ],
            'notification' => [
                'title' => '📢 📢📢 ABSEN MASUUK...',
                'body' => 'Hay '.$names.', Jangan lupa absen masuk ya....',
                'image' => ''
            ]
        ]);

        return Command::SUCCESS;
    }
}
