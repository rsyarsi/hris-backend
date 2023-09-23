<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeStatus extends Model
{
    use HasFactory;

    protected $table = 'overtime_statuses';

    protected $primaryKey = 'id';

    protected $fillable = ['name'];
}
