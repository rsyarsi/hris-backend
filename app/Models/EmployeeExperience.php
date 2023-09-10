<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeExperience extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_experiences';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'company_name',
        'company_field',
        'responsibility',
        'started_at',
        'ended_at',
        'start_position',
        'end_position',
        'stop_reason',
        'latest_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
