<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'overtimes';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'task',
        'note',
        'overtime_status_id',
        'from_date',
        'to_date',
        'amount',
        'type',
        'duration',
        'libur',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function overtimeStatus()
    {
        return $this->belongsTo(OvertimeStatus::class, 'overtime_status_id', 'id');
    }

    public function overtimeHistory()
    {
        return $this->hasMany(OvertimeHistory::class, 'overtime_id');
    }

    public function shiftSchedule()
    {
        return $this->belongsTo(ShiftSchedule::class, 'from_date', 'date');
    }

    public function generateAbsen()
    {
        return $this->belongsTo(GenerateAbsen::class, 'id', 'overtime_id');
    }
}
