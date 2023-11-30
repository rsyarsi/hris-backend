<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollComponent extends Model
{
    use HasFactory;

    protected $table = 'mpayrollcomponents';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active', 'group_component_payroll', 'order'];
}
