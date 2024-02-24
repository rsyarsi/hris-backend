<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderOvertime extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'order_overtimes';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'employee_staff_id',
        'employee_entry_id',
        'user_created_id',
        'from_date',
        'to_date',
        'note_order',
        'note_overtime',
        'type',
        'duration',
        'holiday',
        'status',
    ];

    public function employeeStaff()
    {
        return $this->belongsTo(Employee::class, 'employee_staff_id', 'id');
    }

    public function employeeEntry()
    {
        return $this->belongsTo(Employee::class, 'employee_entry_id', 'id');
    }

    public function userCreated()
    {
        return $this->belongsTo(User::class, 'user_created_id', 'id');
    }
}
