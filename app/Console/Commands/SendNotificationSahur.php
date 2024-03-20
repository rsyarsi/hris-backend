<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SendNotificationSahur extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduller:sahur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send notification sahur';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION_INFORMATION') // Make sure to prefix the authorization key with 'key='
        ])->post(env('FIREBASE_URL'), [
            'to' => '/topics/news',
            'data' => [
                'extra_information' => 'Notification : Sahur..'
            ],
            'notification' => [
                'title' => "📢 📢📢 Waktunya Sahur. Ayo... Banguuun...",
                'body' => "Hay... Jangan lupa sahur ya. Biar puasa mu lancar....",
                'image' => ""
            ]
        ]);
        // Check for errors
        if ($response->failed()) {
            // Log or handle the error as needed
            return 'Error sending notification: ' . $response->body();
        }
        return Command::SUCCESS;
    }
}
