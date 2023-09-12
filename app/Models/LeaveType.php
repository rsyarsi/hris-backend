<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $table = 'leave_types';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'is_salary_deduction', 'active'];
}
