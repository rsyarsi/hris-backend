<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    use HasFactory;

    protected $table = 'helpers';

    protected $fillable =
    [
        'employment_number',
        'telephone_invitation_interview',
        'email_invitation_interview'
    ];
}
