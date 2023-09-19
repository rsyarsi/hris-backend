<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShiftGroup extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'shift_groups';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = ['name', 'hour', 'day', 'type'];
}
