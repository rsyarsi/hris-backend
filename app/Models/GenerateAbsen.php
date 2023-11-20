<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenerateAbsen extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'generate_absen';

    protected $primaryKey = 'id';

    protected $fillable =
    [
        'period', 'date', 'day', 'employee_id', 'shift_id', 'date_in_at', 'time_in_at',
        'date_out_at', 'time_out_at', 'schedule_date_in_at', 'schedule_time_in_at',
        'schedule_date_out_at', 'schedule_time_out_at', 'telat', 'pa', 'holiday',
        'night', 'national_holiday', 'note', 'leave_id', 'leave_type_id', 'leave_time_at',
        'leave_out_at', 'schedule_leave_time_at', 'schedule_leave_out_at', 'overtime_id',
        'overtime_at', 'overtime_time_at', 'overtime_out_at', 'schedule_overtime_time_at',
        'schedule_overtime_out_at', 'ot1', 'ot2', 'ot3', 'ot4', 'manual', 'user_manual_id',
        'input_manual_at', 'lock', 'gp_in', 'gp_out',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'id');
    }

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'leave_id', 'id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_manual_id', 'id');
    }
}
