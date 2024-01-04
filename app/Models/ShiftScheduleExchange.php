<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftScheduleExchange extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'shift_schedule_exchanges';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employe_requested_id',
        'shift_schedule_date_requested',
        'shift_schedule_request_id',
        'shift_schedule_code_requested',
        'shift_schedule_name_requested',
        'shift_schedule_time_from_requested',
        'shift_schedule_time_end_requested',
        'shift_exchange_type',
        'to_employee_id',
        'to_shift_schedule_date',
        'to_shift_schedule_id',
        'to_shift_schedule_code',
        'to_shift_schedule_name',
        'to_shift_schedule_time_from',
        'to_shift_schedule_time_end',
        'exchange_employee_id',
        'exchange_date',
        'exchange_shift_schedule_id',
        'exchange_shift_schedule_code',
        'exchange_shift_schedule_name',
        'exchange_shift_schedule_time_from',
        'exchange_shift_schedule_time_end',
        'date_created',
        'date_updated',
        'user_created_id',
        'user_updated_id',
        'cancel',
        'notes',
    ];

    public function employeeRequest()
    {
        return $this->belongsTo(Employee::class, 'employe_requested_id', 'id');
    }

    public function employeeTo()
    {
        return $this->belongsTo(Employee::class, 'to_employee_id', 'id');
    }

    public function shiftScheduleRequest()
    {
        return $this->belongsTo(ShiftSchedule::class, 'shift_schedule_request_id', 'id');
    }

    public function shiftScheduleTo()
    {
        return $this->belongsTo(ShiftSchedule::class, 'to_shift_schedule_id', 'id');
    }

    public function exchangeShiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'exchange_shift_schedule_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_updated_id', 'id');
    }
}
