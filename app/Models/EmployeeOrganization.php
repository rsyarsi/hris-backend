<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeOrganization extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'employee_organizations';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_id',
        'institution_name',
        'position',
        'started_year',
        'ended_year',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
