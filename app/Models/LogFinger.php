<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogFinger extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'log_fingers';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'employee_id',
        'code_sn_finger',
        'datetime',
        'manual',
        'user_manual_id',
        'input_manual_at',
        'code_pin',
        'time_in',
        'time_out',
        'tgl_log',
        'absen_type',
        'function'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_manual_id', 'id');
    }
}
