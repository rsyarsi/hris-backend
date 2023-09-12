<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveApproval extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'leave_approvals';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable =
    [
        'leave_id',
        'manager_id',
        'action',
        'action_at',
    ];

    public function leave()
    {
        return $this->belongsTo(Leave::class, 'leave_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id', 'id');
    }
}
