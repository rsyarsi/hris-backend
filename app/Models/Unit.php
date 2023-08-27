<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $table = 'munits';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'active'];
}
