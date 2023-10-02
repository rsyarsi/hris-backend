<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftSchedule extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'shift_schedules';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'shift_id',
        'date',
        'time_in',
        'time_out',
        'late_note',
        'shift_exchange_id',
        'user_exchange_id',
        'user_exchange_at',
        'created_user_id',
        'updated_user_id',
        'setup_user_id',
        'setup_at',
        'period',
        'leave_note',
        'holiday',
        'night',
        'national_holiday',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }

    public function shiftExcange()
    {
        return $this->belongsTo(Leave::class, 'shift_exchange_id', 'id');
    }

    public function userExchange()
    {
        return $this->belongsTo(User::class, 'user_exchange_id', 'id');
    }

    public function userCreate()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'id');
    }

    public function userUpdate()
    {
        return $this->belongsTo(User::class, 'updated_user_id', 'id');
    }

    public function userSetup()
    {
        return $this->belongsTo(User::class, 'setup_user_id', 'id');
    }
}
