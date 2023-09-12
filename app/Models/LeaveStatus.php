<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveStatus extends Model
{
    use HasFactory;

    protected $table = 'leave_statuses';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];
}
