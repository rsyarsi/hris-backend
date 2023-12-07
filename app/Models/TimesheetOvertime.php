<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimesheetOvertime extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'timesheet_overtimes';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'employee_name',
        'unitname',
        'positionname',
        'departmenname',
        'overtime_type',
        'realisasihours',
        'schedule_date_in_at',
        'schedule_time_in_at',
        'schedule_date_out_at',
        'schedule_time_out_at',
        'date_in_at',
        'time_in_at',
        'date_out_at',
        'time_out_at',
        'jamlemburawal',
        'jamlemburconvert',
        'jamlembur',
        'tuunjangan',
        'uanglembur',
        'period',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
