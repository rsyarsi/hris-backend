<?php
namespace App\Services\Firebase;

interface FirebaseServiceInterface
{
    public function sendNotification($firbaseId, $typeSend, $employee);
}
