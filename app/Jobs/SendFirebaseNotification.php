<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Http;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendFirebaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $body;
    protected $url;

    public function __construct($title, $body, $url = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function handle()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION_INFORMATION')
        ])->post(env('FIREBASE_URL'), [
            'to' => '/topics/news',
            'data' => [
                'extra_information' => 'Notification : New Information'
            ],
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
                'image' => $this->url
            ]
        ]);

        if ($response->failed()) {
            // Log or handle the error as needed
            Log::error('Error sending notification: ' . $response->body());
        }
    }
}
