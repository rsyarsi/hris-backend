<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusEmployment extends Model
{
    use HasFactory;

    protected $table = 'mstatusemployments';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
