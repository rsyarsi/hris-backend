<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeContractDetail extends Model
{
    use HasFactory;

    protected $table = 'employee_contract_details';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_contract_id',
        'payroll_component_id',
        'nominal',
        'active',
    ];

    public function employeeContract()
    {
        return $this->belongsTo(EmployeeContract::class, 'employee_contract_id', 'id');
    }

    public function payrollComponent()
    {
        return $this->belongsTo(PayrollComponent::class, 'payroll_component_id', 'id');
    }
}
