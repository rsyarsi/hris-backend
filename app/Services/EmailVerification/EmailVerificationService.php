<?php

namespace App\Services\EmailVerification;

use Illuminate\Support\Str;
use App\Models\EmailVerificationToken;
use App\Notifications\EmailVerification;
use Illuminate\Support\Facades\Notification;
use App\Services\EmailVerification\EmailVerificationServiceInterface;

class EmailVerificationService implements EmailVerificationServiceInterface
{
    function sendVerificationLink($user, $type)
    {
        Notification::send($user, new EmailVerification($this->generateVerificationLink($user->email), $type));
    }

    function generateVerificationLink($email)
    {
        $checkIfTokenExists = EmailVerificationToken::where('email', $email)->first();
        if ($checkIfTokenExists) $checkIfTokenExists->delete();
        $token = Str::uuid();
        // $url = config('app.url_career') . "?token=" . $token . "&email=" . $email;
        $url = route('verify-email', ['token' => $token, 'email' => $email]);
        $saveToken = EmailVerificationToken::create([
            'email' => $email,
            'token' => $token,
            'expired_at' => now()->addMinute(60),
        ]);
        if ($saveToken) {
            return $url;
        }
    }
}
