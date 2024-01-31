<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginLog extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'login_logs';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'user_id',
        'login_type',
        'login_time',
        'logout_time',
        'ip_address',
        'user_agent',
        'device_info',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
