<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeSkill extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_skills';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'skill_type_id',
        'employee_certificate_id',
        'description',
        'level',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function skillType()
    {
        return $this->belongsTo(SkillType::class, 'skill_type_id', 'id');
    }

    public function employeeCertificate()
    {
        return $this->belongsTo(EmployeeCertificate::class, 'employee_certificate_id', 'id');
    }
}
