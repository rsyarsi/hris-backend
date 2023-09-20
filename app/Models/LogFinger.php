<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogFinger extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'log_fingers';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'log_at',
        'employee_id',
        'in_out',
        'code_sn_finger',
        'datetime',
        'manual',
        'user_manual_id',
        'input_manual_at',
        'code_pin'
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
