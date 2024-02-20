<?php
namespace App\Services\Firebase;

interface FirebaseServiceInterface
{
    public function sendNotification($firbaseId, $typeSend, $employee);
    public function sendNotificationInformation($firbaseId, $informationName, $imageUrl);
}
