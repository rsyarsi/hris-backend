<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeContract extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_contracts';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'transaction_number',
        'start_at',
        'end_at',
        'sk_number',
        'shift_group_id',
        'umk',
        'contract_type_id',
        'day',
        'hour',
        'hour_per_day',
        'istirahat_overtime',
        'vot1',
        'vot2',
        'vot3',
        'vot4',
        'unit_id',
        'position_id',
        'manager_id',
        'department_id',
        'supervisor_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function shiftGroup()
    {
        return $this->belongsTo(ShiftGroup::class, 'shift_group_id', 'id');
    }

    public function contractType()
    {
        return $this->belongsTo(ContractType::class, 'contract_type_id', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'supervisor_id', 'id');
    }

    public function employeeContractDetail()
    {
        return $this->hasMany(EmployeeContractDetail::class, 'employee_contract_id');
    }
}
