<?php
namespace App\Services\Firebase;

interface FirebaseServiceInterface
{
    public function sendNotification($firbaseId, $typeSend, $employee);
    public function sendNotificationInformation($firbaseId, $informationName, $imageUrl);
    public function sendNotificationLeave($firbaseId, $employee);
    public function sendNotificationOvertimeLeader($firbaseId, $employee, $startDate, $endDate);
    public function sendNotificationOvertimeStaff($registrationIds, $startDate, $endDate);
}
