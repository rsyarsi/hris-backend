<?php
namespace App\Services\Firebase;

use Illuminate\Support\Facades\Http;
use App\Services\Firebase\FirebaseServiceInterface;

class FirebaseService implements FirebaseServiceInterface
{
    public function sendNotification($firebaseId, $typeSend, $employee)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => env('FIREBASE_AUTHORIZATION')
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'to' => $firebaseId,
            'data' => [
                'extra_information' => 'Type Notification : ' . $typeSend
            ],
            'notification' => [
                'title' => $typeSend . ' Notification',
                'body' => 'Dear User, Anda mendapatkan ' .$typeSend. ' notifikasi baru',
                'image' => ''
            ]
        ]);
        return $response->body();
    }
}
