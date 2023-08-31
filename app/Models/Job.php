<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'mjobs';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
