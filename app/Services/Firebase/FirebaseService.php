<?php
namespace App\Services\Firebase;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\Firebase\FirebaseServiceInterface;

class FirebaseService implements FirebaseServiceInterface
{
    public function sendNotification($registrationIds, $typeSend, $employee)
    {
        $body = 'Anda mendapatkan notifikasi baru, dari pengajuan '.$typeSend . ' : ' . $employee;
        Log::info($body);
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
                'body' => $body,
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

    public function sendNotificationLeave($registrationIds, $employee)
    {
        $body = 'Anda memiliki pengajuan Cuti/Izin dari : '. $employee . ', Silahkan lakukan Proses approval';
        $registrationIdsString = implode(', ', $registrationIds);
        // Log::info('Berhasil kirim ke: ' . $registrationIdsString);
        Log::info($body);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION') // Make sure to prefix the authorization key with 'key='
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $registrationIds, // Remove the square brackets to send as an array
            'data' => [
                'extra_information' => 'Notification Cuti/Izin'
            ],
            'notification' => [
                'title' => 'Cuti/Izin Notification',
                'body' => $body,
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

    public function sendNotificationOvertimeStaff($registrationIds, $startDate, $endDate)
    {
        $body = 'Anda memiliki Lembur, Tanggal '. $startDate . ' - '. $endDate;
        $registrationIdsString = implode(', ', $registrationIds);
        // Log::info('Berhasil kirim ke: ' . $registrationIdsString);
        Log::info($body);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION') // Make sure to prefix the authorization key with 'key='
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $registrationIds, // Remove the square brackets to send as an array
            'data' => [
                'extra_information' => 'Notification Lembur'
            ],
            'notification' => [
                'title' => 'Lembur Notification',
                'body' => $body,
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

    public function sendNotificationOvertimeLeader($registrationIds, $employee, $startDate, $endDate)
    {
        $body = 'Anda memiliki pengajuan Lembur dari : '. $employee . ', Tanggal '. $startDate . ' - '. $endDate. ', Silahkan lakukan Proses approval';
        $registrationIdsString = implode(', ', $registrationIds);
        // Log::info('Berhasil kirim ke: ' . $registrationIdsString);
        Log::info($body);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'key=' . env('FIREBASE_AUTHORIZATION') // Make sure to prefix the authorization key with 'key='
        ])->post('https://fcm.googleapis.com/fcm/send', [
            'registration_ids' => $registrationIds, // Remove the square brackets to send as an array
            'data' => [
                'extra_information' => 'Notification Lembur'
            ],
            'notification' => [
                'title' => 'Lembur Notification',
                'body' => $body,
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
            'to' => '/topics/news',
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
