<?php
namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use App\Services\Firebase\FirebaseServiceInterface;

class FirebaseService implements FirebaseServiceInterface
{
    public function sendNotification($registrationIds, $typeSend, $employee)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION') // Make sure to prefix the authorization key with 'key='
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $registrationIds, // Remove the square brackets to send as an array
            'data' => [
                'extra_information' => 'Notification : ' . $typeSend
            ],
            'notification' => [
                'title' => $typeSend . ' Notification',
                'body' => 'Dear User, Anda mendapatkan ' .$typeSend. ' notifikasi baru, dari '.$employee,
                'image' => ''
            ]
        ]);
        // Check for errors
        if ($response->failed()) {
            // Log or handle the error as needed
            return 'Error sending notification: ' . $response->body();
        }
        return $response->body();
    }

    public function sendNotificationInformation($title, $body, $url = null)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION_INFORMATION') // Make sure to prefix the authorization key with 'key='
        ])->post(env('FIREBASE_URL'), [
            'to' => '/topics/tester',
            'data' => [
                'extra_information' => 'Notification : New Information'
            ],
            'notification' => [
                'title' => $title,
                'body' => $body,
                'image' => $url
            ]
        ]);
        // Check for errors
        if ($response->failed()) {
            // Log or handle the error as needed
            return 'Error sending notification: ' . $response->body();
        }
        return $response->body();
    }
}
