<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'leaves';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'leave_type_id',
        'from_date',
        'to_date',
        'duration',
        'note',
        'leave_status_id',
        'quantity_cuti_awal',
        'sisa_cuti',
        'file_url',
        'file_path',
        'file_disk',
        'shift_awal_id',
        'shift_schedule_id',
        'year'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function leaveStatus()
    {
        return $this->belongsTo(LeaveStatus::class, 'leave_status_id', 'id');
    }

    public function leaveHistory()
    {
        return $this->hasMany(LeaveHistory::class, 'leave_id');
    }

    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'shift_schedule_id', 'id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_awal_id', 'id');
    }

    // public function leaveApproval()
    // {
    //     return $this->hasMany(LeaveApproval::class, 'leave_id');
    // }
}
