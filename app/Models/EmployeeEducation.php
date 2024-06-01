<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeEducation extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_educations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'education_id',
        'institution_name',
        'major',
        'started_year',
        'ended_year',
        'final_score',
        'is_passed',
        'verified_at',
        'file_url',
        'file_path',
        'file_disk',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id', 'id');
    }
}
