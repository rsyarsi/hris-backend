<?php

namespace App\Services\EmailVerification;

interface EmailVerificationServiceInterface
{
    public function sendVerificationLink($email, $type);
    public function generateVerificationLink($email);
}
