<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogFingerTemp extends Model
{
    use HasFactory;

    protected $table = 'log_finger_temps';

    protected $primaryssssssKey = 'id';

    protected $fillable =
    [
        'date_log',
        'employee_id',
        'function',
        'snfinger',
        'manual',
        'user_manual',
        'manual_date',
        'pin',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_manual', 'id');
    }
}
